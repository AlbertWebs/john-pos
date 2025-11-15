<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Mail\StockStatusNotification;
use App\Jobs\SendDailySalesReport;
use App\Jobs\SendHourlyStockStatus;
use App\Jobs\SendLowStockAlert;
use App\Jobs\SendNextOrderReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;

class StockStatusController extends Controller
{
    public function index(Request $request)
    {
        // Check authentication
        if (!Auth::check()) {
            abort(401, 'Unauthenticated.');
        }

        // Only allow super_admin access (not cashiers)
        if (Auth::user()->isCashier()) {
            abort(403, 'Unauthorized access.');
        }

        $query = Inventory::with(['category', 'brand'])
            ->where('status', 'active')
            ->orderBy('name');

        // Filter by low stock only
        if ($request->filled('low_stock_only') && $request->low_stock_only == '1') {
            $query->whereColumn('stock_quantity', '<=', 'reorder_level');
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $inventory = $query->get();

        // Get categories for filter
        $categories = \App\Models\Category::orderBy('name')->get();

        return view('admin.stock-status.index', compact('inventory', 'categories'));
    }

    public function sendEmail(Request $request)
    {
        // Check authentication
        if (!Auth::check() || Auth::user()->isCashier()) {
            abort(403, 'Unauthorized access.');
        }

        // Get notification email from settings
        $settings = DB::table('settings')->pluck('value', 'key')->toArray();
        $notificationEmail = $settings['admin_email'] ?? $settings['email'] ?? null;

        if (!$notificationEmail) {
            return back()->with('error', 'Notification email is not configured. Please set it in Settings.');
        }

        // Get inventory data (same logic as index)
        $query = Inventory::with(['category', 'brand'])
            ->where('status', 'active')
            ->orderBy('name');

        $lowStockOnly = $request->boolean('low_stock_only', false);
        
        // Filter by low stock only if requested
        if ($lowStockOnly) {
            $query->whereColumn('stock_quantity', '<=', 'reorder_level');
        }

        // Filter by category if provided
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $inventory = $query->get();

        try {
            Mail::to($notificationEmail)->send(new StockStatusNotification($inventory, $settings, $lowStockOnly));
            
            return back()->with('success', "Stock status report sent successfully to {$notificationEmail}.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    public function runJob(Request $request)
    {
        if (!Auth::check() || Auth::user()->isCashier()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'job' => 'required|string',
        ]);

        $job = $request->input('job');

        try {
            switch ($job) {
                case 'daily_sales':
                    dispatch(new SendDailySalesReport);
                    $message = 'Daily Sales Report job dispatched.';
                    break;
                case 'hourly_stock':
                    dispatch(new SendHourlyStockStatus);
                    $message = 'Hourly Stock Status job dispatched.';
                    break;
                case 'low_stock_alert':
                    dispatch(new SendLowStockAlert);
                    $message = 'Low Stock Alert job dispatched.';
                    break;
                case 'next_order':
                    dispatch(new SendNextOrderReminder);
                    $message = 'Next Order Reminder job dispatched.';
                    break;
                case 'all':
                    Artisan::call('jobs:run-all');
                    $message = 'All scheduled jobs dispatched.';
                    break;
                default:
                    return back()->with('error', 'Invalid job selected.');
            }

            return back()->with('success', $message . ' Ensure the queue worker is running to process jobs.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to run job: ' . $e->getMessage());
        }
    }
}
