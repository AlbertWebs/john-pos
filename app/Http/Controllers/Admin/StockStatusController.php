<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
