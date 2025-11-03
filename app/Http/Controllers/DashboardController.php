<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\Inventory;
use App\Models\Customer;

class DashboardController extends Controller
{
    // Middleware is applied in routes/web.php, no need to apply here in Laravel 11
    
    public function index()
    {
        $user = Auth::user();

        // Get dashboard stats based on role
        $stats = $this->getDashboardStats();

        return view('dashboard.index', compact('stats'));
    }

    protected function getDashboardStats()
    {
        $user = Auth::user();

        // Basic stats that everyone can see
        $stats = [
            'today_sales' => Sale::whereDate('date', today())->sum('total_amount') ?? 0,
            'today_transactions' => Sale::whereDate('date', today())->count() ?? 0,
            'low_stock_items' => Inventory::whereColumn('stock_quantity', '<=', 'reorder_level')
                ->where('status', 'active')
                ->count() ?? 0,
        ];

        // Super admin gets additional stats
        if ($user->isSuperAdmin()) {
            $stats['total_inventory_value'] = Inventory::sum(DB::raw('stock_quantity * cost_price')) ?? 0;
            $stats['total_customers'] = Customer::count() ?? 0;
            $stats['pending_orders'] = Sale::where('payment_status', 'pending')->count() ?? 0;
        }

        // Chart data for daily, weekly, monthly trends
        $stats['daily_sales'] = $this->getDailySalesData();
        $stats['weekly_sales'] = $this->getWeeklySalesData();
        $stats['monthly_sales'] = $this->getMonthlySalesData();
        $stats['payment_methods'] = $this->getPaymentMethodData();
        $stats['top_selling_items'] = $this->getTopSellingItems();

        return $stats;
    }

    private function getDailySalesData()
    {
        // SQLite compatible version
        $isSqlite = config('database.default') === 'sqlite';
        
        if ($isSqlite) {
            // SQLite: Use strftime for date
            $sales = Sale::select(
                DB::raw("strftime('%Y-%m-%d', date) as sale_date"),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->where('date', '>=', now()->subDays(7))
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->get();
        } else {
            // MySQL/MariaDB
            $sales = Sale::select(
                DB::raw('DATE(date) as sale_date'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->where('date', '>=', now()->subDays(7))
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->get();
        }

        return [
            'labels' => $sales->pluck('sale_date')->map(fn($date) => date('M d', strtotime($date)))->toArray(),
            'revenue' => $sales->pluck('total')->toArray(),
            'transactions' => $sales->pluck('count')->toArray(),
        ];
    }

    private function getWeeklySalesData()
    {
        // SQLite compatible version
        $isSqlite = config('database.default') === 'sqlite';
        
        if ($isSqlite) {
            // SQLite: Calculate week number manually
            $sales = Sale::select(
                DB::raw("strftime('%Y', date) as year"),
                DB::raw("CAST((julianday(date) - julianday(strftime('%Y', date) || '-01-01')) / 7 AS INTEGER) + 1 as week_number"),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->where('date', '>=', now()->subWeeks(8))
            ->groupBy('year', 'week_number')
            ->orderBy('year')
            ->orderBy('week_number')
            ->get();
        } else {
            // MySQL/MariaDB
            $sales = Sale::select(
                DB::raw('WEEK(date) as week_number'),
                DB::raw('YEAR(date) as year'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->where('date', '>=', now()->subWeeks(8))
            ->groupBy('year', 'week_number')
            ->orderBy('year')
            ->orderBy('week_number')
            ->get();
        }

        return [
            'labels' => $sales->map(fn($sale) => "Week {$sale->week_number}")->toArray(),
            'revenue' => $sales->pluck('total')->toArray(),
            'transactions' => $sales->pluck('count')->toArray(),
        ];
    }

    private function getMonthlySalesData()
    {
        // SQLite compatible version
        $isSqlite = config('database.default') === 'sqlite';
        
        if ($isSqlite) {
            // SQLite: Use strftime for month and year
            $sales = Sale::select(
                DB::raw("CAST(strftime('%m', date) AS INTEGER) as month"),
                DB::raw("CAST(strftime('%Y', date) AS INTEGER) as year"),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->where('date', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        } else {
            // MySQL/MariaDB
            $sales = Sale::select(
                DB::raw('MONTH(date) as month'),
                DB::raw('YEAR(date) as year'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->where('date', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        }

        return [
            'labels' => $sales->map(fn($sale) => date('M Y', mktime(0, 0, 0, $sale->month, 1, $sale->year)))->toArray(),
            'revenue' => $sales->pluck('total')->toArray(),
            'transactions' => $sales->pluck('count')->toArray(),
        ];
    }

    private function getPaymentMethodData()
    {
        $payments = DB::table('payments')
            ->select('payment_method', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->get();

        return [
            'labels' => $payments->pluck('payment_method')->toArray(),
            'amounts' => $payments->pluck('total')->toArray(),
            'counts' => $payments->pluck('count')->toArray(),
        ];
    }

    private function getTopSellingItems()
    {
        return DB::table('sale_items')
            ->join('inventory', 'sale_items.part_id', '=', 'inventory.id')
            ->select(
                'inventory.name',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.subtotal) as total_revenue')
            )
            ->groupBy('inventory.id', 'inventory.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get()
            ->map(fn($item) => [
                'name' => $item->name,
                'quantity' => $item->total_quantity,
                'revenue' => $item->total_revenue,
            ])
            ->toArray();
    }
}
