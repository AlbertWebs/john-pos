<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\ReturnModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exports\SoldVsCostExport;
use App\Exports\ProductHistoryExport;
use App\Exports\StockValueExport;
use App\Exports\DoubleEntryExport;
use App\Exports\AccountsReceivableExport;
use App\Services\AccountsReceivableService;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Cost price vs average sold price per item (optional sale date range for averages).
     */
    public function soldVsCost(Request $request)
    {
        $query = $this->soldVsCostBaseQuery($request);

        if ($request->get('export') === 'excel') {
            $items = $query->get();
            $filename = 'sold-vs-cost-' . now()->format('Y-m-d-His') . '.xlsx';

            return Excel::download(new SoldVsCostExport($items), $filename);
        }

        $items = $query->paginate(50)->appends($request->query());

        $categories = \App\Models\Category::orderBy('name')->get();

        return view('reports.sold-vs-cost', [
            'items' => $items,
            'categories' => $categories,
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder<\App\Models\Inventory>
     */
    protected function soldVsCostBaseQuery(Request $request)
    {
        $salesAgg = DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->when($request->filled('start_date'), function ($q) use ($request) {
                $q->whereDate('sales.date', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function ($q) use ($request) {
                $q->whereDate('sales.date', '<=', $request->end_date);
            })
            ->select(
                'sale_items.part_id',
                DB::raw('SUM(sale_items.quantity) as qty_sold'),
                DB::raw('SUM(sale_items.subtotal) / NULLIF(SUM(sale_items.quantity), 0) as avg_sold_price')
            )
            ->groupBy('sale_items.part_id');

        $query = Inventory::query()
            ->with('category')
            ->leftJoinSub($salesAgg, 'agg', 'agg.part_id', '=', 'inventory.id')
            ->select('inventory.*', 'agg.qty_sold', 'agg.avg_sold_price')
            ->orderBy('inventory.name');

        if ($request->filled('category_id')) {
            $query->where('inventory.category_id', $request->category_id);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('inventory.name', 'like', "%{$s}%")
                    ->orWhere('inventory.part_number', 'like', "%{$s}%")
                    ->orWhere('inventory.sku', 'like', "%{$s}%");
            });
        }

        return $query;
    }

    public function sales(Request $request)
    {
        $query = Sale::with(['customer', 'user', 'saleItems.part', 'payments']);

        // Date filters
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // Period filter
        if ($request->filled('period')) {
            switch ($request->period) {
                case 'today':
                    $query->whereDate('date', today());
                    break;
                case 'week':
                    $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('date', now()->month)
                          ->whereYear('date', now()->year);
                    break;
                case 'year':
                    $query->whereYear('date', now()->year);
                    break;
            }
        }

        // Payment method filter
        if ($request->filled('payment_method')) {
            $query->whereHas('payments', function($q) use ($request) {
                $q->where('payment_method', $request->payment_method);
            });
        }

        $sales = $query->orderBy('date', 'desc')->get();

        // Calculate totals
        $totals = [
            'total_sales' => $sales->sum('total_amount'),
            'total_transactions' => $sales->count(),
            'total_subtotal' => $sales->sum('subtotal'),
            'total_tax' => $sales->sum('tax'),
            'total_discount' => $sales->sum('discount'),
            'avg_sale' => $sales->count() > 0 ? $sales->sum('total_amount') / $sales->count() : 0,
        ];

        // Payment method breakdown
        $paymentBreakdown = Payment::whereIn('sale_id', $sales->pluck('id'))
            ->select('payment_method', DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->payment_method => $item->total];
            });

        $view = view('reports.sales', compact('sales', 'totals', 'paymentBreakdown'));

        // Export logic
        if ($request->filled('export')) {
            if ($request->export === 'pdf') {
                return $this->exportSalesPDF($sales, $totals, $paymentBreakdown);
            } elseif ($request->export === 'excel') {
                return $this->exportSalesExcel($sales, $totals, $paymentBreakdown);
            }
        }

        return $view;
    }

    public function inventory(Request $request)
    {
        $query = Inventory::with(['category', 'brand', 'vehicleMake', 'vehicleModel']);

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Low stock filter
        if ($request->filled('low_stock') && $request->low_stock == '1') {
            $query->whereColumn('stock_quantity', '<=', 'reorder_level');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('part_number', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('name')->get();

        // Calculate totals
        $totals = [
            'total_items' => $items->count(),
            'total_value' => $items->sum(function($item) {
                return $item->stock_quantity * $item->cost_price;
            }),
            'low_stock_count' => $items->filter(function($item) {
                return $item->isLowStock();
            })->count(),
            'out_of_stock_count' => $items->filter(function($item) {
                return $item->stock_quantity == 0;
            })->count(),
        ];

        // Export logic
        if ($request->filled('export')) {
            if ($request->export === 'pdf') {
                return $this->exportInventoryPDF($items, $totals);
            } elseif ($request->export === 'excel') {
                return $this->exportInventoryExcel($items, $totals);
            }
        }

        $categories = \App\Models\Category::orderBy('name')->get();
        
        return view('reports.inventory', compact('items', 'totals', 'categories'));
    }

    /**
     * Stock on hand valued at cost and at list selling price.
     */
    public function stockValue(Request $request)
    {
        $query = $this->applyStockValueFilters(
            Inventory::query()->with(['category', 'brand']),
            $request
        );

        $totals = $this->computeStockValueTotals($query);
        $categoryBreakdown = $this->computeStockValueByCategory($request);

        if ($request->filled('export')) {
            $items = (clone $query)->orderByRaw('(stock_quantity * cost_price) DESC')->get();

            if ($request->export === 'pdf') {
                return $this->exportStockValuePDF($items, $totals, $categoryBreakdown);
            }
            if ($request->export === 'excel') {
                $filename = 'stock-value-' . now()->format('Y-m-d') . '.xlsx';

                return Excel::download(new StockValueExport($items, $totals), $filename);
            }
        }

        $items = (clone $query)
            ->orderByRaw('(stock_quantity * cost_price) DESC')
            ->paginate(50)
            ->appends($request->query());

        $categories = \App\Models\Category::orderBy('name')->get();

        return view('reports.stock-value', compact(
            'items',
            'totals',
            'categoryBreakdown',
            'categories'
        ));
    }

    protected function applyStockValueFilters(Builder $query, Request $request): Builder
    {
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('low_stock') && $request->low_stock == '1') {
            $query->whereColumn('stock_quantity', '<=', 'reorder_level');
        }

        if ($request->input('in_stock_only', '1') !== '0') {
            $query->where('stock_quantity', '>', 0);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('part_number', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    protected function computeStockValueTotals(Builder $query): array
    {
        $stats = (clone $query)->selectRaw(
            'COUNT(*) as total_items,
             COALESCE(SUM(stock_quantity), 0) as total_units,
             COALESCE(SUM(stock_quantity * cost_price), 0) as cost_value,
             COALESCE(SUM(stock_quantity * selling_price), 0) as retail_value'
        )->first();

        $costValue = (float) $stats->cost_value;
        $retailValue = (float) $stats->retail_value;

        return [
            'total_items' => (int) $stats->total_items,
            'total_units' => (int) $stats->total_units,
            'cost_value' => $costValue,
            'retail_value' => $retailValue,
            'potential_profit' => $retailValue - $costValue,
        ];
    }

    protected function computeStockValueByCategory(Request $request): Collection
    {
        $query = $this->applyStockValueFilters(
            Inventory::query()->leftJoin('categories', 'categories.id', '=', 'inventory.category_id'),
            $request
        );

        return $query
            ->select(
                'categories.name as category_name',
                DB::raw('COUNT(inventory.id) as item_count'),
                DB::raw('COALESCE(SUM(inventory.stock_quantity), 0) as total_units'),
                DB::raw('COALESCE(SUM(inventory.stock_quantity * inventory.cost_price), 0) as cost_value'),
                DB::raw('COALESCE(SUM(inventory.stock_quantity * inventory.selling_price), 0) as retail_value')
            )
            ->groupBy('inventory.category_id', 'categories.name')
            ->orderByDesc('cost_value')
            ->get()
            ->map(function ($row) {
                $row->category_name = $row->category_name ?? 'Uncategorized';
                $row->potential_profit = (float) $row->retail_value - (float) $row->cost_value;

                return $row;
            });
    }

    private function exportStockValuePDF($items, array $totals, Collection $categoryBreakdown)
    {
        $html = view('reports.exports.stock-value-pdf', [
            'items' => $items,
            'totals' => $totals,
            'categoryBreakdown' => $categoryBreakdown,
            'date' => now()->format('Y-m-d H:i:s'),
        ])->render();

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return response()->streamDownload(function () use ($dompdf) {
            echo $dompdf->output();
        }, 'stock-value-' . now()->format('Y-m-d') . '.pdf');
    }

    public function topSelling(Request $request)
    {
        $limit = (int) $request->get('limit', 10);
        $topSelling = $this->prepareTopSelling($request, $limit);

        if ($request->filled('export')) {
            if ($request->export === 'pdf') {
                return $this->exportTopSellingPDF($topSelling);
            } elseif ($request->export === 'excel') {
                return $this->exportTopSellingExcel($topSelling);
            }
        }

        $pageTitle = 'Top Selling Parts';
        $pageDescription = 'View best-selling inventory items';

        return view('reports.top-selling', compact('topSelling', 'limit', 'pageTitle', 'pageDescription'));
    }

    /**
     * Timeline of stock received (purchases) and sales for a single product.
     */
    public function productHistory(Request $request)
    {
        $product = null;
        $events = collect();
        $summary = [
            'purchased_qty' => 0,
            'sold_qty' => 0,
            'returned_qty' => 0,
        ];
        $searchResults = collect();

        if ($request->filled('part_id')) {
            $product = Inventory::with('category')->find($request->part_id);
        }

        if ($request->filled('search') && ! $product) {
            $s = $request->search;
            $searchResults = Inventory::query()
                ->orderBy('name')
                ->where(function ($q) use ($s) {
                    $q->where('name', 'like', "%{$s}%")
                        ->orWhere('part_number', 'like', "%{$s}%")
                        ->orWhere('sku', 'like', "%{$s}%");
                })
                ->limit(20)
                ->get();
        }

        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : null;
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : null;

        if ($product) {
            $events = $this->buildProductHistoryTimeline($product->id, $startDate, $endDate);
            $summary = [
                'purchased_qty' => $events->where('type', 'purchase')->sum('quantity'),
                'sold_qty' => $events->where('type', 'sale')->sum('quantity'),
                'returned_qty' => $events->where('type', 'return')->sum('quantity'),
            ];

            if ($request->get('export') === 'excel') {
                $filename = 'product-history-' . ($product->part_number ?: $product->id) . '-' . now()->format('Y-m-d') . '.xlsx';

                return Excel::download(
                    new ProductHistoryExport($product, $events, $startDate, $endDate, $summary),
                    $filename
                );
            }
        }

        return view('reports.product-history', [
            'product' => $product,
            'events' => $events,
            'summary' => $summary,
            'searchResults' => $searchResults,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    protected function buildProductHistoryTimeline(int $partId, ?Carbon $startDate, ?Carbon $endDate): Collection
    {
        $events = collect();

        $purchasesQuery = InventoryMovement::query()
            ->where('part_id', $partId)
            ->whereIn('movement_type', ['purchase', 'return'])
            ->with('user');

        if ($startDate) {
            $purchasesQuery->where('timestamp', '>=', $startDate);
        }
        if ($endDate) {
            $purchasesQuery->where('timestamp', '<=', $endDate);
        }

        foreach ($purchasesQuery->orderBy('timestamp')->get() as $movement) {
            $isReturn = $movement->movement_type === 'return';
            $events->push([
                'sort_key' => $movement->timestamp->timestamp,
                'date' => $movement->timestamp,
                'type' => $isReturn ? 'return' : 'purchase',
                'type_label' => $isReturn ? 'Return (stock in)' : 'Stock received',
                'quantity' => (int) $movement->change_quantity,
                'quantity_display' => '+' . $movement->change_quantity,
                'unit_price' => null,
                'line_total' => null,
                'reference' => $isReturn ? 'Return #' . $movement->reference_id : 'Stock receipt',
                'reference_url' => null,
                'customer' => null,
                'user' => $movement->user?->name,
                'notes' => $movement->notes,
            ]);
        }

        $salesQuery = SaleItem::query()
            ->where('part_id', $partId)
            ->with(['sale.customer', 'sale.user']);

        if ($startDate || $endDate) {
            $salesQuery->whereHas('sale', function ($q) use ($startDate, $endDate) {
                if ($startDate) {
                    $q->where('date', '>=', $startDate);
                }
                if ($endDate) {
                    $q->where('date', '<=', $endDate);
                }
            });
        }

        foreach ($salesQuery->orderBy('id')->get() as $item) {
            $sale = $item->sale;
            if (! $sale) {
                continue;
            }

            $events->push([
                'sort_key' => $sale->date->timestamp,
                'date' => $sale->date,
                'type' => 'sale',
                'type_label' => 'Sold',
                'quantity' => (int) $item->quantity,
                'quantity_display' => '-' . $item->quantity,
                'unit_price' => (float) $item->price,
                'line_total' => (float) $item->subtotal,
                'reference' => $sale->invoice_number,
                'reference_url' => route('sales.show', $sale),
                'customer' => $sale->customer?->name ?? 'Walk-in',
                'user' => $sale->user?->name,
                'notes' => null,
            ]);
        }

        return $events
            ->sortByDesc('sort_key')
            ->values()
            ->map(fn (array $row) => collect($row)->except('sort_key')->all());
    }

    public function mostSelling(Request $request)
    {
        $limit = (int) $request->get('limit', 20);
        $topSelling = $this->prepareTopSelling($request, $limit);

        if ($request->filled('export')) {
            if ($request->export === 'pdf') {
                return $this->exportTopSellingPDF($topSelling);
            } elseif ($request->export === 'excel') {
                return $this->exportTopSellingExcel($topSelling);
            }
        }

        $pageTitle = 'Most Selling Items';
        $pageDescription = 'Frequently purchased items based on recent sales';

        return view('reports.top-selling', compact('topSelling', 'limit', 'pageTitle', 'pageDescription'));
    }

    // PDF Export Methods
    private function exportSalesPDF($sales, $totals, $paymentBreakdown)
    {
        $html = view('reports.exports.sales-pdf', [
            'sales' => $sales,
            'totals' => $totals,
            'paymentBreakdown' => $paymentBreakdown,
            'date' => now()->format('Y-m-d H:i:s'),
        ])->render();

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return response()->streamDownload(function() use ($dompdf) {
            echo $dompdf->output();
        }, 'sales-report-' . now()->format('Y-m-d') . '.pdf');
    }

    private function exportInventoryPDF($items, $totals)
    {
        $html = view('reports.exports.inventory-pdf', [
            'items' => $items,
            'totals' => $totals,
            'date' => now()->format('Y-m-d H:i:s'),
        ])->render();

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return response()->streamDownload(function() use ($dompdf) {
            echo $dompdf->output();
        }, 'inventory-report-' . now()->format('Y-m-d') . '.pdf');
    }

    private function exportTopSellingPDF($topSelling)
    {
        $html = view('reports.exports.top-selling-pdf', [
            'topSelling' => $topSelling,
            'date' => now()->format('Y-m-d H:i:s'),
        ])->render();

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response()->streamDownload(function() use ($dompdf) {
            echo $dompdf->output();
        }, 'top-selling-parts-' . now()->format('Y-m-d') . '.pdf');
    }

    // Excel Export Methods
    private function exportSalesExcel($sales, $totals, $paymentBreakdown)
    {
        return Excel::download(new \App\Exports\SalesExport($sales, $totals, $paymentBreakdown), 
            'sales-report-' . now()->format('Y-m-d') . '.xlsx');
    }

    private function exportInventoryExcel($items, $totals)
    {
        return Excel::download(new \App\Exports\InventoryExport($items, $totals), 
            'inventory-report-' . now()->format('Y-m-d') . '.xlsx');
    }

    private function exportTopSellingExcel($topSelling)
    {
        return Excel::download(new \App\Exports\TopSellingExport($topSelling), 
            'top-selling-parts-' . now()->format('Y-m-d') . '.xlsx');
    }

    private function prepareTopSelling(Request $request, int $limit)
    {
        $query = SaleItem::with(['part.category', 'part.brand', 'sale']);

        if ($request->filled('start_date')) {
            $query->whereHas('sale', function($q) use ($request) {
                $q->whereDate('date', '>=', $request->start_date);
            });
        }
        if ($request->filled('end_date')) {
            $query->whereHas('sale', function($q) use ($request) {
                $q->whereDate('date', '<=', $request->end_date);
            });
        }

        if ($request->filled('period')) {
            switch ($request->period) {
                case 'today':
                    $query->whereHas('sale', function($q) {
                        $q->whereDate('date', today());
                    });
                    break;
                case 'week':
                    $query->whereHas('sale', function($q) {
                        $q->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
                    });
                    break;
                case 'month':
                    $query->whereHas('sale', function($q) {
                        $q->whereMonth('date', now()->month)
                          ->whereYear('date', now()->year);
                    });
                    break;
                case 'year':
                    $query->whereHas('sale', function($q) {
                        $q->whereYear('date', now()->year);
                    });
                    break;
            }
        }

        $limit = max(1, $limit);

        $topSellingData = $query->select('part_id', 
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(subtotal) as total_revenue'),
                DB::raw('COUNT(DISTINCT sale_id) as transaction_count')
            )
            ->groupBy('part_id')
            ->orderBy('total_quantity', 'desc')
            ->limit($limit)
            ->get();

        $partIds = $topSellingData->pluck('part_id');
        $parts = Inventory::whereIn('id', $partIds)
            ->with(['category', 'brand'])
            ->get()
            ->keyBy('id');

        return $topSellingData->map(function($item) use ($parts) {
            $part = $parts->get($item->part_id);
            return [
                'part' => $part,
                'total_quantity' => $item->total_quantity,
                'total_revenue' => $item->total_revenue,
                'transaction_count' => $item->transaction_count,
            ];
        })->filter(function($item) {
            return $item['part'] !== null;
        })->values();
    }

    /**
     * Accounts receivable / debtors report with aging and collections.
     */
    public function accountsReceivable(Request $request, AccountsReceivableService $arService)
    {
        $asOf = $request->filled('as_of')
            ? Carbon::parse($request->as_of)->endOfDay()
            : now()->endOfDay();

        $collectionsFrom = $request->filled('collections_from')
            ? Carbon::parse($request->collections_from)->startOfDay()
            : now()->startOfMonth()->startOfDay();

        $collectionsTo = $request->filled('collections_to')
            ? Carbon::parse($request->collections_to)->endOfDay()
            : now()->endOfDay();

        $report = $arService->buildReport($asOf, $collectionsFrom, $collectionsTo);

        if ($request->get('export') === 'excel') {
            $filename = 'accounts-receivable-' . $asOf->format('Y-m-d') . '.xlsx';

            return Excel::download(new AccountsReceivableExport($report), $filename);
        }

        if ($request->get('export') === 'pdf') {
            return $this->exportAccountsReceivablePDF($report);
        }

        return view('reports.accounts-receivable', $report);
    }

    private function exportAccountsReceivablePDF(array $report)
    {
        $html = view('reports.exports.accounts-receivable-pdf', array_merge($report, [
            'date' => now()->format('Y-m-d H:i:s'),
        ]))->render();

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return response()->streamDownload(function () use ($dompdf) {
            echo $dompdf->output();
        }, 'accounts-receivable-' . $report['asOf']->format('Y-m-d') . '.pdf');
    }

    /**
     * Double-entry journal for a period (sales, stock purchases, returns).
     */
    public function doubleEntry(Request $request)
    {
        [$startDate, $endDate] = $this->resolveDoubleEntryPeriod($request);

        $entries = $this->buildDoubleEntryEntries($startDate, $endDate);
        $rows = $this->flattenDoubleEntryRows($entries);
        $trialBalance = $this->computeDoubleEntryTrialBalance($rows);
        $totals = $this->computeDoubleEntryTotals($rows);

        if ($request->get('export') === 'excel') {
            $filename = 'double-entry-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.xlsx';

            return Excel::download(
                new DoubleEntryExport($rows, $startDate, $endDate, $totals),
                $filename
            );
        }

        if ($request->get('export') === 'pdf') {
            return $this->exportDoubleEntryPDF($rows, $trialBalance, $totals, $startDate, $endDate);
        }

        return view('reports.double-entry', [
            'rows' => $rows,
            'trialBalance' => $trialBalance,
            'totals' => $totals,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'entryCount' => $entries->count(),
        ]);
    }

    protected function resolveDoubleEntryPeriod(Request $request): array
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->startOfMonth()->startOfDay();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        return [$startDate, $endDate];
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    protected function buildDoubleEntryEntries(Carbon $startDate, Carbon $endDate): Collection
    {
        $entries = collect();

        $sales = Sale::with(['payments', 'saleItems.part'])
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();

        foreach ($sales as $sale) {
            $lines = [];
            $paid = (float) $sale->payments->sum('amount');

            foreach ($sale->payments as $payment) {
                $lines[] = [
                    'side' => 'debit',
                    'account' => $payment->payment_method,
                    'amount' => (float) $payment->amount,
                ];
            }

            $receivable = round((float) $sale->total_amount - $paid, 2);
            if ($receivable > 0.01) {
                $lines[] = [
                    'side' => 'debit',
                    'account' => 'Accounts Receivable',
                    'amount' => $receivable,
                ];
            }

            $lines[] = [
                'side' => 'credit',
                'account' => 'Sales Revenue',
                'amount' => (float) $sale->total_amount,
            ];

            $cogs = $sale->saleItems->sum(function ($item) {
                if (! $item->part) {
                    return 0;
                }

                return $item->quantity * (float) $item->part->cost_price;
            });

            if ($cogs > 0) {
                $lines[] = ['side' => 'debit', 'account' => 'Cost of Goods Sold', 'amount' => round($cogs, 2)];
                $lines[] = ['side' => 'credit', 'account' => 'Inventory', 'amount' => round($cogs, 2)];
            }

            $entries->push([
                'sort' => $sale->date->timestamp,
                'date' => $sale->date,
                'reference' => $sale->invoice_number,
                'type' => 'Sale',
                'url' => route('sales.show', $sale),
                'lines' => $lines,
            ]);
        }

        $purchases = InventoryMovement::with(['part', 'supply'])
            ->where('movement_type', 'purchase')
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->orderBy('timestamp')
            ->get();

        foreach ($purchases as $movement) {
            if (! $movement->part) {
                continue;
            }

            $value = round($movement->change_quantity * (float) $movement->part->cost_price, 2);
            if ($value <= 0) {
                continue;
            }

            $supplyLabel = $movement->supply?->name ?? 'Purchases';

            $entries->push([
                'sort' => $movement->timestamp->timestamp,
                'date' => $movement->timestamp,
                'reference' => 'Stock-in #' . $movement->id,
                'type' => 'Stock received',
                'url' => route('inventory.show', $movement->part_id),
                'lines' => [
                    ['side' => 'debit', 'account' => 'Inventory', 'amount' => $value],
                    ['side' => 'credit', 'account' => $supplyLabel, 'amount' => $value],
                ],
            ]);
        }

        $returns = ReturnModel::with(['part', 'sale'])
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at')
            ->get();

        foreach ($returns as $return) {
            $lines = [];
            $refund = (float) $return->refund_amount;

            if ($refund > 0) {
                $lines[] = ['side' => 'debit', 'account' => 'Sales Returns', 'amount' => $refund];
                $lines[] = ['side' => 'credit', 'account' => 'Cash / Refunds', 'amount' => $refund];
            }

            if ($return->part) {
                $restoreValue = round($return->quantity_returned * (float) $return->part->cost_price, 2);
                if ($restoreValue > 0) {
                    $lines[] = ['side' => 'debit', 'account' => 'Inventory', 'amount' => $restoreValue];
                    $lines[] = ['side' => 'credit', 'account' => 'Cost of Goods Sold', 'amount' => $restoreValue];
                }
            }

            if (count($lines) === 0) {
                continue;
            }

            $entries->push([
                'sort' => $return->created_at->timestamp,
                'date' => $return->created_at,
                'reference' => 'Return #' . $return->id . ($return->sale ? ' (' . $return->sale->invoice_number . ')' : ''),
                'type' => 'Return',
                'url' => $return->sale ? route('sales.show', $return->sale) : null,
                'lines' => $lines,
            ]);
        }

        return $entries->sortBy('sort')->values();
    }

    /**
     * @param Collection<int, array<string, mixed>> $entries
     */
    protected function flattenDoubleEntryRows(Collection $entries): Collection
    {
        $rows = collect();
        $journalNo = 1;

        foreach ($entries as $entry) {
            $entryDebits = collect($entry['lines'])->where('side', 'debit')->sum('amount');
            $entryCredits = collect($entry['lines'])->where('side', 'credit')->sum('amount');

            foreach ($entry['lines'] as $index => $line) {
                $rows->push([
                    'journal_no' => $journalNo,
                    'date' => $entry['date'],
                    'reference' => $entry['reference'],
                    'type' => $entry['type'],
                    'url' => $entry['url'] ?? null,
                    'account' => $line['account'],
                    'side' => $line['side'],
                    'amount' => $line['amount'],
                    'is_first_line' => $index === 0,
                    'entry_debits' => $entryDebits,
                    'entry_credits' => $entryCredits,
                    'entry_balanced' => abs($entryDebits - $entryCredits) < 0.01,
                ]);
            }

            $journalNo++;
        }

        return $rows;
    }

    protected function computeDoubleEntryTrialBalance(Collection $rows): Collection
    {
        $accounts = [];

        foreach ($rows as $row) {
            $account = $row['account'];
            if (! isset($accounts[$account])) {
                $accounts[$account] = ['account' => $account, 'debits' => 0.0, 'credits' => 0.0];
            }
            if ($row['side'] === 'debit') {
                $accounts[$account]['debits'] += $row['amount'];
            } else {
                $accounts[$account]['credits'] += $row['amount'];
            }
        }

        return collect($accounts)
            ->map(function ($row) {
                $row['debits'] = round($row['debits'], 2);
                $row['credits'] = round($row['credits'], 2);
                $row['net'] = round($row['debits'] - $row['credits'], 2);

                return (object) $row;
            })
            ->sortBy('account')
            ->values();
    }

    protected function computeDoubleEntryTotals(Collection $rows): array
    {
        $totalDebits = round($rows->where('side', 'debit')->sum('amount'), 2);
        $totalCredits = round($rows->where('side', 'credit')->sum('amount'), 2);

        return [
            'total_debits' => $totalDebits,
            'total_credits' => $totalCredits,
            'balanced' => abs($totalDebits - $totalCredits) < 0.01,
            'difference' => round($totalDebits - $totalCredits, 2),
        ];
    }

    private function exportDoubleEntryPDF(Collection $rows, Collection $trialBalance, array $totals, Carbon $startDate, Carbon $endDate)
    {
        $html = view('reports.exports.double-entry-pdf', [
            'rows' => $rows,
            'trialBalance' => $trialBalance,
            'totals' => $totals,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'date' => now()->format('Y-m-d H:i:s'),
        ])->render();

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return response()->streamDownload(function () use ($dompdf) {
            echo $dompdf->output();
        }, 'double-entry-' . $startDate->format('Y-m-d') . '.pdf');
    }
}
