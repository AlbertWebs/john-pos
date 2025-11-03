<?php

namespace App\Http\Controllers;

use App\Models\VehicleModel;
use App\Models\VehicleMake;
use Illuminate\Http\Request;

class VehicleModelController extends Controller
{
    public function index(Request $request, $makeId = null)
    {
        if (request()->wantsJson()) {
            $models = VehicleModel::query();
            if ($makeId) {
                $models->where('vehicle_make_id', $makeId);
            }
            return response()->json($models->orderBy('model_name')->get());
        }
        
        $query = VehicleModel::with('vehicleMake');
        
        if ($request->filled('make_id')) {
            $query->where('vehicle_make_id', $request->make_id);
        }
        
        $vehicleModels = $query->orderBy('model_name')->paginate(15);
        $vehicleMakes = VehicleMake::orderBy('make_name')->get();
        
        return view('vehicle-models.index', compact('vehicleModels', 'vehicleMakes'));
    }

    public function create()
    {
        $vehicleMakes = VehicleMake::orderBy('make_name')->get();
        return view('vehicle-models.create', compact('vehicleMakes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_make_id' => 'required|exists:vehicle_makes,id',
            'model_name' => 'required|string|max:255',
            'year_start' => 'nullable|integer|min:1900|max:2100',
            'year_end' => 'nullable|integer|min:1900|max:2100|gte:year_start',
        ]);

        VehicleModel::create($validated);

        return redirect()->route('vehicle-models.index')
            ->with('success', 'Vehicle model created successfully.');
    }

    public function show(VehicleModel $vehicleModel)
    {
        $vehicleModel->load('vehicleMake');
        return view('vehicle-models.show', compact('vehicleModel'));
    }

    public function edit(VehicleModel $vehicleModel)
    {
        $vehicleMakes = VehicleMake::orderBy('make_name')->get();
        return view('vehicle-models.edit', compact('vehicleModel', 'vehicleMakes'));
    }

    public function update(Request $request, VehicleModel $vehicleModel)
    {
        $validated = $request->validate([
            'vehicle_make_id' => 'required|exists:vehicle_makes,id',
            'model_name' => 'required|string|max:255',
            'year_start' => 'nullable|integer|min:1900|max:2100',
            'year_end' => 'nullable|integer|min:1900|max:2100|gte:year_start',
        ]);

        $vehicleModel->update($validated);

        return redirect()->route('vehicle-models.index')
            ->with('success', 'Vehicle model updated successfully.');
    }

    public function destroy(VehicleModel $vehicleModel)
    {
        if ($vehicleModel->inventory()->count() > 0) {
            return redirect()->route('vehicle-models.index')
                ->with('error', 'Cannot delete vehicle model that has inventory items.');
        }

        $vehicleModel->delete();

        return redirect()->route('vehicle-models.index')
            ->with('success', 'Vehicle model deleted successfully.');
    }
}
