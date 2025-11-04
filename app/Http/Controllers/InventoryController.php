<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Category;
use App\Models\Brand;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Inventory::with(['category', 'brand', 'vehicleMake', 'vehicleModel']);

        // Search
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

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by vehicle make
        if ($request->filled('vehicle_make_id')) {
            $query->where('vehicle_make_id', $request->vehicle_make_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter low stock items
        if ($request->filled('low_stock')) {
            $query->lowStock();
        }

        // Sort
        $sortBy = $request->get('sort_by', 'name');
        $sortDir = $request->get('sort_dir', 'asc');
        $query->orderBy($sortBy, $sortDir);

        $inventory = $query->paginate(15);
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('brand_name')->get();
        $vehicleMakes = VehicleMake::orderBy('make_name')->get();

        // Low stock count
        $lowStockCount = Inventory::active()->lowStock()->count();

        return view('inventory.index', compact('inventory', 'categories', 'brands', 'vehicleMakes', 'lowStockCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('brand_name')->get();
        $vehicleMakes = VehicleMake::orderBy('make_name')->get();
        
        // Load all vehicle models grouped by make for multi-select
        $allVehicleModels = VehicleModel::with('vehicleMake')
            ->orderBy('vehicle_make_id')
            ->orderBy('model_name')
            ->get();

        return view('inventory.create', compact('categories', 'brands', 'vehicleMakes', 'allVehicleModels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'part_number' => 'required|string|unique:inventory,part_number|max:255',
            'sku' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|unique:inventory,barcode|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',
            'vehicle_make_id' => 'nullable|exists:vehicle_makes,id',
            'vehicle_model_id' => 'nullable|exists:vehicle_models,id',
            'vehicle_model_ids' => 'nullable|array',
            'vehicle_model_ids.*' => 'exists:vehicle_models,id',
            'year_range' => 'nullable|string|max:255',
            'cost_price' => 'required|numeric|min:0',
            'min_price' => 'required|numeric|min:0|lte:selling_price',
            'selling_price' => 'required|numeric|min:0|gte:min_price',
            'stock_quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $vehicleModelIds = $validated['vehicle_model_ids'] ?? [];
        unset($validated['vehicle_model_ids']);

        $inventory = Inventory::create($validated);
        
        // Sync vehicle models (many-to-many)
        if (!empty($vehicleModelIds)) {
            $inventory->vehicleModels()->sync($vehicleModelIds);
        }

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        $inventory->load(['category', 'brand', 'vehicleMake', 'vehicleModel', 'priceHistories.changedBy']);
        
        return view('inventory.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('brand_name')->get();
        $vehicleMakes = VehicleMake::orderBy('make_name')->get();
        
        // Load all vehicle models grouped by make for multi-select
        $allVehicleModels = VehicleModel::with('vehicleMake')
            ->orderBy('vehicle_make_id')
            ->orderBy('model_name')
            ->get();
        
        // Get currently selected vehicle models
        $inventory->load('vehicleModels');
        $selectedVehicleModelIds = $inventory->vehicleModels->pluck('id')->toArray();

        return view('inventory.edit', compact('inventory', 'categories', 'brands', 'vehicleMakes', 'allVehicleModels', 'selectedVehicleModelIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'part_number' => 'required|string|max:255|unique:inventory,part_number,' . $inventory->id,
            'sku' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255|unique:inventory,barcode,' . $inventory->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',
            'vehicle_make_id' => 'nullable|exists:vehicle_makes,id',
            'vehicle_model_id' => 'nullable|exists:vehicle_models,id',
            'vehicle_model_ids' => 'nullable|array',
            'vehicle_model_ids.*' => 'exists:vehicle_models,id',
            'year_range' => 'nullable|string|max:255',
            'cost_price' => 'required|numeric|min:0',
            'min_price' => 'required|numeric|min:0|lte:selling_price',
            'selling_price' => 'required|numeric|min:0|gte:min_price',
            'stock_quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $vehicleModelIds = $validated['vehicle_model_ids'] ?? [];
        unset($validated['vehicle_model_ids']);

        // Track price changes
        if ($request->selling_price != $inventory->selling_price) {
            \App\Models\PriceHistory::create([
                'part_id' => $inventory->id,
                'old_price' => $inventory->selling_price,
                'new_price' => $request->selling_price,
                'changed_by' => Auth::id(),
                'changed_at' => now(),
            ]);
        }

        $inventory->update($validated);
        
        // Sync vehicle models (many-to-many)
        $inventory->vehicleModels()->sync($vehicleModelIds);

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        // Check if item has been used in sales
        if ($inventory->saleItems()->count() > 0) {
            return redirect()->route('inventory.index')
                ->with('error', 'Cannot delete inventory item that has been used in sales. Consider marking it as inactive instead.');
        }

        $inventory->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item deleted successfully.');
    }

    /**
     * Bulk delete inventory items
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|exists:inventory,id',
        ]);

        DB::beginTransaction();
        try {
            $deleted = 0;
            $failed = 0;

            foreach ($validated['ids'] as $id) {
                $inventory = Inventory::find($id);
                
                // Check if item has been used in sales
                if ($inventory && $inventory->saleItems()->count() > 0) {
                    $failed++;
                    continue;
                }

                if ($inventory) {
                    $inventory->delete();
                    $deleted++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Deleted {$deleted} item(s)" . ($failed > 0 ? ". {$failed} item(s) could not be deleted (used in sales)." : ""),
                'deleted' => $deleted,
                'failed' => $failed,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete items: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get vehicle models by make (AJAX)
     */
    public function getVehicleModels(Request $request)
    {
        $models = VehicleModel::where('vehicle_make_id', $request->make_id)
            ->orderBy('model_name')
            ->get(['id', 'model_name', 'year_start', 'year_end']);

        return response()->json($models);
    }
}
