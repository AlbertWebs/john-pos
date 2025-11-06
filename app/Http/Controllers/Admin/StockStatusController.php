<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Mail\StockStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
}
