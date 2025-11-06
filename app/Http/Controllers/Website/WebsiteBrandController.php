<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebsiteBrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('brand_name')->paginate(20);
        
        return view('website.brands.index', compact('brands'));
    }

    public function edit(Brand $brand)
    {
        return view('website.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($brand->image && Storage::disk('public')->exists($brand->image)) {
                Storage::disk('public')->delete($brand->image);
            }
            
            // Store new image
            $validated['image'] = $request->file('image')->store('brands', 'public');
        } else {
            // Keep existing image if no new one uploaded
            unset($validated['image']);
        }

        $brand->update($validated);

        return redirect()->route('website.brands.index')
            ->with('success', 'Brand updated successfully.');
    }
}
