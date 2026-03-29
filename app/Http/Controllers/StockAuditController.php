<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\StockAudit;
use App\Models\StockAuditLine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StockAuditController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->startOfMonth()->startOfDay();
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        $query = Inventory::query()
            ->with('category')
            ->orderBy('name');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('part_number', 'like', "%{$s}%")
                    ->orWhere('sku', 'like', "%{$s}%");
            });
        }

        $paginator = $query->paginate(50)->appends($request->query());
        $partIds = $paginator->getCollection()->pluck('id');

        if ($partIds->isEmpty()) {
            $totals = $this->emptyTotals();
            $rows = $paginator;
        } else {
            $aggregates = $this->buildAggregates($partIds, $startDate, $endDate);
            $physicalMap = $this->latestPhysicalCounts($partIds);

            $paginator->getCollection()->transform(function (Inventory $item) use ($aggregates, $physicalMap) {
                $id = $item->id;
                $closing = (int) $item->stock_quantity;
                $net = (int) ($aggregates['net_movement'][$id] ?? 0);
                $opening = $closing - $net;
                $sold = (int) ($aggregates['sold'][$id] ?? 0);
                $purchases = (int) ($aggregates['purchases'][$id] ?? 0);
                $returns = (int) ($aggregates['returns'][$id] ?? 0);
                $other = (int) ($aggregates['other'][$id] ?? 0);
                $physical = $physicalMap[$id] ?? null;
                $variance = $physical !== null ? $physical - $closing : null;

                return (object) [
                    'part' => $item,
                    'opening_stock' => $opening,
                    'purchases' => $purchases,
                    'items_sold' => $sold,
                    'returns' => $returns,
                    'other_movements' => $other,
                    'closing_stock' => $closing,
                    'physical_stock' => $physical,
                    'variance' => $variance,
                ];
            });

            $totals = $this->computeTotalsForPage($paginator);
            $rows = $paginator;
        }

        $categories = Category::orderBy('name')->get();
        $stockAuditTablesReady = Schema::hasTable('stock_audits') && Schema::hasTable('stock_audit_lines');
        $recentAudits = $stockAuditTablesReady
            ? StockAudit::with('user')->orderByDesc('created_at')->limit(10)->get()
            : collect();

        return view('reports.stock-audit', [
            'rows' => $rows,
            'totals' => $totals,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'categories' => $categories,
            'recentAudits' => $recentAudits,
            'stockAuditTablesReady' => $stockAuditTablesReady,
        ]);
    }

    public function storePhysical(Request $request)
    {
        if (! Schema::hasTable('stock_audits') || ! Schema::hasTable('stock_audit_lines')) {
            return redirect()
                ->route('reports.stock-audit')
                ->with('error', 'Stock audit tables are missing. Run: php artisan migrate');
        }

        $validated = $request->validate([
            'period_from' => 'required|date',
            'period_to' => 'required|date|after_or_equal:period_from',
            'title' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:2000',
            'physical' => 'nullable|array',
            'physical.*' => 'nullable|integer|min:0',
        ]);

        $audit = StockAudit::create([
            'period_from' => $validated['period_from'],
            'period_to' => $validated['period_to'],
            'title' => $validated['title'] ?? 'Stock count ' . now()->format('Y-m-d H:i'),
            'notes' => $validated['notes'] ?? null,
            'user_id' => Auth::id(),
        ]);

        $physical = $validated['physical'] ?? [];
        foreach ($physical as $partId => $qty) {
            if ($qty === null || $qty === '') {
                continue;
            }
            StockAuditLine::create([
                'stock_audit_id' => $audit->id,
                'part_id' => (int) $partId,
                'physical_stock' => (int) $qty,
            ]);
        }

        return redirect()
            ->route('reports.stock-audit', [
                'start_date' => $validated['period_from'],
                'end_date' => $validated['period_to'],
            ])
            ->with('success', 'Physical stock counts saved.');
    }

    private function buildAggregates($partIds, Carbon $startDate, Carbon $endDate): array
    {
        $ids = $partIds->all();

        $netMovement = DB::table('inventory_movements')
            ->whereIn('part_id', $ids)
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->select('part_id', DB::raw('SUM(change_quantity) as net'))
            ->groupBy('part_id')
            ->pluck('net', 'part_id');

        $purchases = DB::table('inventory_movements')
            ->whereIn('part_id', $ids)
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->where('movement_type', 'purchase')
            ->select('part_id', DB::raw('SUM(change_quantity) as qty'))
            ->groupBy('part_id')
            ->pluck('qty', 'part_id');

        $returns = DB::table('inventory_movements')
            ->whereIn('part_id', $ids)
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->where('movement_type', 'return')
            ->select('part_id', DB::raw('SUM(change_quantity) as qty'))
            ->groupBy('part_id')
            ->pluck('qty', 'part_id');

        $other = DB::table('inventory_movements')
            ->whereIn('part_id', $ids)
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->whereIn('movement_type', ['adjust', 'damage'])
            ->select('part_id', DB::raw('SUM(change_quantity) as qty'))
            ->groupBy('part_id')
            ->pluck('qty', 'part_id');

        $sold = DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->whereIn('sale_items.part_id', $ids)
            ->whereBetween('sales.date', [$startDate->toDateString(), $endDate->toDateString()])
            ->select('sale_items.part_id', DB::raw('SUM(sale_items.quantity) as qty'))
            ->groupBy('sale_items.part_id')
            ->pluck('qty', 'part_id');

        return [
            'net_movement' => $netMovement,
            'purchases' => $purchases,
            'returns' => $returns,
            'other' => $other,
            'sold' => $sold,
        ];
    }

    private function latestPhysicalCounts($partIds): array
    {
        if ($partIds->isEmpty() || ! Schema::hasTable('stock_audit_lines') || ! Schema::hasTable('stock_audits')) {
            return [];
        }

        $rows = DB::table('stock_audit_lines as sal')
            ->join('stock_audits as sa', 'sa.id', '=', 'sal.stock_audit_id')
            ->whereIn('sal.part_id', $partIds->all())
            ->whereNotNull('sal.physical_stock')
            ->orderByDesc('sa.created_at')
            ->get(['sal.part_id', 'sal.physical_stock']);

        $map = [];
        foreach ($rows as $row) {
            if (! isset($map[$row->part_id])) {
                $map[$row->part_id] = (int) $row->physical_stock;
            }
        }

        return $map;
    }

    private function emptyTotals(): object
    {
        return (object) [
            'opening_stock' => 0,
            'purchases' => 0,
            'items_sold' => 0,
            'returns' => 0,
            'other_movements' => 0,
            'closing_stock' => 0,
            'physical_stock' => null,
            'variance' => null,
        ];
    }

    private function computeTotalsForPage($paginator): object
    {
        $t = $this->emptyTotals();
        $physicalSum = 0;
        $physicalCount = 0;
        $varianceSum = 0;
        $varianceCount = 0;

        foreach ($paginator as $row) {
            $t->opening_stock += $row->opening_stock;
            $t->purchases += $row->purchases;
            $t->items_sold += $row->items_sold;
            $t->returns += $row->returns;
            $t->other_movements += $row->other_movements;
            $t->closing_stock += $row->closing_stock;
            if ($row->physical_stock !== null) {
                $physicalSum += $row->physical_stock;
                $physicalCount++;
                $varianceSum += $row->variance;
                $varianceCount++;
            }
        }

        $t->physical_stock = $physicalCount > 0 ? $physicalSum : null;
        $t->variance = $varianceCount > 0 ? $varianceSum : null;

        return $t;
    }
}
