<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSettings;
use Illuminate\Http\Request;

class SeoSettingsController extends Controller
{
    public function index()
    {
        $seoSettings = SeoSettings::orderBy('page_type')->get();
        
        // Default page types
        $pageTypes = [
            'homepage' => 'Homepage',
            'products' => 'Products Listing',
            'product_detail' => 'Product Detail',
            'categories' => 'Categories Listing',
            'category_detail' => 'Category Detail',
            'brands' => 'Brands Listing',
            'brand_detail' => 'Brand Detail',
            'vehicle_models' => 'Vehicle Models Listing',
            'vehicle_model_detail' => 'Vehicle Model Detail',
            'cart' => 'Shopping Cart',
            'checkout' => 'Checkout',
        ];

        return view('admin.seo-settings.index', compact('seoSettings', 'pageTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'page_type' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:500',
            'structured_data' => 'nullable|json',
            'custom_meta_tags' => 'nullable|string',
        ]);

        SeoSettings::updateOrCreate(
            ['page_type' => $validated['page_type']],
            $validated
        );

        return redirect()->route('admin.seo-settings.index')
            ->with('success', 'SEO settings saved successfully');
    }

    public function show($id)
    {
        $seoSettings = SeoSettings::findOrFail($id);
        return response()->json($seoSettings);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:500',
            'structured_data' => 'nullable|json',
            'custom_meta_tags' => 'nullable|string',
        ]);

        $seoSettings = SeoSettings::findOrFail($id);
        $seoSettings->update($validated);

        return redirect()->route('admin.seo-settings.index')
            ->with('success', 'SEO settings updated successfully');
    }

    public function destroy($id)
    {
        $seoSettings = SeoSettings::findOrFail($id);
        $seoSettings->delete();

        return redirect()->route('admin.seo-settings.index')
            ->with('success', 'SEO settings deleted successfully');
    }
}
