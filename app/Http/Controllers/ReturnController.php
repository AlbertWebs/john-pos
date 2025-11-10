<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\ReturnModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = ReturnModel::with(['sale', 'part', 'user']);

        // Date filters
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $returns = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('returns.index', compact('returns'));
    }

    public function create(Request $request)
    {
        $saleId = $request->get('sale_id');
        $sale = null;
        $saleItems = collect();
        $recentSales = Sale::with('customer')
            ->orderByDesc('created_at')
            ->take(15)
            ->get();

        if ($saleId) {
            $sale = Sale::with(['saleItems.part', 'customer'])->findOrFail($saleId);
            $saleItems = $sale->saleItems;
        }

        return view('returns.create', compact('sale', 'saleItems', 'recentSales'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'sale_item_id' => 'required|exists:sale_items,id',
            'part_id' => 'required|exists:inventory,id',
            'quantity' => 'required|integer|min:1',
            'refund_amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $saleItem = SaleItem::with('sale')->findOrFail($validated['sale_item_id']);
            
            // Check if quantity doesn't exceed sold quantity
            if ($validated['quantity'] > $saleItem->quantity) {
                throw new \Exception("Return quantity cannot exceed sold quantity.");
            }

            // Check if item was already returned
            $alreadyReturned = ReturnModel::where('sale_item_id', $validated['sale_item_id'])
                ->sum('quantity_returned');
            
            if (($alreadyReturned + $validated['quantity']) > $saleItem->quantity) {
                throw new \Exception("Total returned quantity cannot exceed sold quantity.");
            }

            // Create return record
            $return = ReturnModel::create([
                'sale_id' => $validated['sale_id'],
                'sale_item_id' => $validated['sale_item_id'],
                'part_id' => $validated['part_id'],
                'quantity_returned' => $validated['quantity'],
                'refund_amount' => $validated['refund_amount'],
                'status' => 'completed',
                'reason' => $validated['reason'] ?? null,
                'user_id' => Auth::id(),
            ]);

            // Restore inventory stock
            $inventory = Inventory::findOrFail($validated['part_id']);
            $inventory->increment('stock_quantity', $validated['quantity']);

            // Create inventory movement
            InventoryMovement::create([
                'part_id' => $validated['part_id'],
                'change_quantity' => $validated['quantity'],
                'movement_type' => 'return',
                'reference_id' => $return->id,
                'user_id' => Auth::id(),
                'timestamp' => now(),
            ]);

            // Update sale payment status if needed
            // If credit, we might want to adjust customer balance or points

            DB::commit();

            return redirect()->route('returns.index')
                ->with('success', 'Return processed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(ReturnModel $return)
    {
        $return->load(['sale.customer', 'sale.user', 'part', 'user']);
        return view('returns.show', compact('return'));
    }

    public function getSaleItems($saleId)
    {
        $sale = Sale::with(['saleItems.part'])->findOrFail($saleId);
        
        $items = $sale->saleItems->map(function($item) {
            $returned = ReturnModel::where('sale_item_id', $item->id)->sum('quantity_returned');
            return [
                'id' => $item->id,
                'part_id' => $item->part_id,
                'part_name' => $item->part->name,
                'part_number' => $item->part->part_number,
                'quantity_sold' => $item->quantity,
                'quantity_returned' => $returned,
                'quantity_available' => $item->quantity - $returned,
                'price' => $item->price,
                'subtotal' => $item->subtotal,
            ];
        });

        return response()->json($items);
    }
}
