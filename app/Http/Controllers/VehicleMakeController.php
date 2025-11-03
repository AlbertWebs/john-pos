<?php

namespace App\Http\Controllers;

use App\Models\VehicleMake;
use Illuminate\Http\Request;

class VehicleMakeController extends Controller
{
    public function index(Request $request)
    {
        if (request()->wantsJson()) {
            return response()->json(VehicleMake::orderBy('make_name')->get());
        }
        
        $vehicleMakes = VehicleMake::withCount('vehicleModels')
            ->orderBy('make_name')
            ->paginate(15);
        return view('vehicle-makes.index', compact('vehicleMakes'));
    }

    public function create()
    {
        return view('vehicle-makes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'make_name' => 'required|string|max:255|unique:vehicle_makes,make_name',
        ]);

        VehicleMake::create($validated);

        return redirect()->route('vehicle-makes.index')
            ->with('success', 'Vehicle make created successfully.');
    }

    public function show(VehicleMake $vehicleMake)
    {
        $vehicleMake->load('vehicleModels');
        return view('vehicle-makes.show', compact('vehicleMake'));
    }

    public function edit(VehicleMake $vehicleMake)
    {
        return view('vehicle-makes.edit', compact('vehicleMake'));
    }

    public function update(Request $request, VehicleMake $vehicleMake)
    {
        $validated = $request->validate([
            'make_name' => 'required|string|max:255|unique:vehicle_makes,make_name,' . $vehicleMake->id,
        ]);

        $vehicleMake->update($validated);

        return redirect()->route('vehicle-makes.index')
            ->with('success', 'Vehicle make updated successfully.');
    }

    public function destroy(VehicleMake $vehicleMake)
    {
        if ($vehicleMake->vehicleModels()->count() > 0 || $vehicleMake->inventory()->count() > 0) {
            return redirect()->route('vehicle-makes.index')
                ->with('error', 'Cannot delete vehicle make that has models or inventory items.');
        }

        $vehicleMake->delete();

        return redirect()->route('vehicle-makes.index')
            ->with('success', 'Vehicle make deleted successfully.');
    }
}
