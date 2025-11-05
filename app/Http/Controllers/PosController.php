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
                  ->orWhere('barcode', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $items = $query->with(['category', 'brand', 'vehicleMake', 'vehicleModel', 'vehicleModels'])
            ->orderByRaw("CASE 
                WHEN barcode = ? THEN 1 
                WHEN part_number = ? THEN 2 
                WHEN sku = ? THEN 3 
                ELSE 4 
            END", [$request->search ?? '', $request->search ?? '', $request->search ?? ''])
            ->orderBy('name')
            ->limit(20)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'part_number' => $item->part_number,
                    'barcode' => $item->barcode,
                    'sku' => $item->sku,
                    'selling_price' => $item->selling_price,
                    'stock_quantity' => $item->stock_quantity,
                    'min_price' => $item->min_price,
                    'category' => $item->category ? $item->category->name : null,
                    'brand' => $item->brand ? $item->brand->brand_name : null,
                    'vehicle_make' => $item->vehicleMake ? $item->vehicleMake->make_name : null,
                    'vehicle_model' => $item->vehicleModel ? $item->vehicleModel->model_name : null,
                    'year_range' => $item->year_range,
                    'vehicle_models' => $item->vehicleModels->map(function($model) {
                        return [
                            'id' => $model->id,
                            'name' => $model->model_name,
                            'year_start' => $model->year_start,
                            'year_end' => $model->year_end,
                        ];
                    })->toArray(),
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
