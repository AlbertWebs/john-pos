<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Customer;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index()
    {
        return view('pos.index');
    }

    public function search(Request $request)
    {
        $query = Inventory::active()->where('stock_quantity', '>', 0);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('part_number', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $items = $query->with(['category', 'brand', 'vehicleMake', 'vehicleModel'])
            ->orderBy('name')
            ->limit(20)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'part_number' => $item->part_number,
                    'selling_price' => $item->selling_price,
                    'stock_quantity' => $item->stock_quantity,
                    'min_price' => $item->min_price,
                    'category' => $item->category ? $item->category->name : null,
                    'brand' => $item->brand ? $item->brand->brand_name : null,
                ];
            });

        return response()->json($items);
    }

    public function getItem($id)
    {
        $item = Inventory::where('status', 'active')->with(['category', 'brand'])->findOrFail($id);
        
        return response()->json([
            'id' => $item->id,
            'name' => $item->name,
            'part_number' => $item->part_number,
            'selling_price' => $item->selling_price,
            'stock_quantity' => $item->stock_quantity,
            'min_price' => $item->min_price,
            'category' => $item->category ? $item->category->name : null,
            'brand' => $item->brand ? $item->brand->brand_name : null,
        ]);
    }
}
