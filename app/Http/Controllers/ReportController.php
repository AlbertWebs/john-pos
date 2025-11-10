<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Inventory;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
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
}
