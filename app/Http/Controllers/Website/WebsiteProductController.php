<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebsiteProductController extends Controller
{
    public function index()
    {
        $products = Inventory::with(['brand', 'category'])
            ->orderBy('name')
            ->paginate(20);
        
        return view('website.products.index', compact('products'));
    }

    public function edit(Inventory $product)
    {
        return view('website.products.edit', compact('product'));
    }

    public function update(Request $request, Inventory $product)
    {
        $validated = $request->validate([
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            
            // Store new image
            $validated['image'] = $request->file('image')->store('products', 'public');
        } else {
            // Keep existing image if no new one uploaded
            unset($validated['image']);
        }

        $product->update($validated);

        return redirect()->route('website.products.index')
            ->with('success', 'Product updated successfully.');
    }
}
