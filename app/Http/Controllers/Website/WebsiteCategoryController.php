<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebsiteCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->paginate(20);
        
        return view('website.categories.index', compact('categories'));
    }

    public function edit(Category $category)
    {
        return view('website.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            
            // Store new image
            $validated['image'] = $request->file('image')->store('categories', 'public');
        } else {
            // Keep existing image if no new one uploaded
            unset($validated['image']);
        }

        $category->update($validated);

        return redirect()->route('website.categories.index')
            ->with('success', 'Category updated successfully.');
    }
}
