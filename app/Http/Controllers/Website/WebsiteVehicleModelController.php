<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebsiteVehicleModelController extends Controller
{
    public function index()
    {
        $models = VehicleModel::with('vehicleMake')
            ->orderBy('model_name')
            ->paginate(20);
        
        return view('website.vehicle-models.index', compact('models'));
    }

    public function edit(VehicleModel $vehicleModel)
    {
        return view('website.vehicle-models.edit', compact('vehicleModel'));
    }

    public function update(Request $request, VehicleModel $vehicleModel)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($vehicleModel->image && Storage::disk('public')->exists($vehicleModel->image)) {
                Storage::disk('public')->delete($vehicleModel->image);
            }
            
            // Store new image
            $validated['image'] = $request->file('image')->store('vehicle-models', 'public');
        } else {
            // Keep existing image if no new one uploaded
            unset($validated['image']);
        }

        $vehicleModel->update($validated);

        return redirect()->route('website.vehicle-models.index')
            ->with('success', 'Vehicle Model updated successfully.');
    }
}
