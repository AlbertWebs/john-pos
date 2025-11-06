<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Category;
use App\Models\Brand;
use App\Models\VehicleModel;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        $baseUrl = url('/shop');
        
        $urls = [
            // Static pages
            ['loc' => $baseUrl, 'changefreq' => 'daily', 'priority' => '1.0'],
            ['loc' => $baseUrl . '/products', 'changefreq' => 'daily', 'priority' => '0.9'],
            ['loc' => $baseUrl . '/cart', 'changefreq' => 'monthly', 'priority' => '0.5'],
            ['loc' => $baseUrl . '/checkout', 'changefreq' => 'monthly', 'priority' => '0.5'],
        ];

        // Products
        $products = Inventory::where('status', 'active')
            ->select('id', 'updated_at')
            ->get();
        
        foreach ($products as $product) {
            $urls[] = [
                'loc' => $baseUrl . '/product/' . $product->id,
                'lastmod' => $product->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        // Categories
        $categories = Category::select('id', 'updated_at')->get();
        foreach ($categories as $category) {
            $urls[] = [
                'loc' => $baseUrl . '/products?category=' . $category->id,
                'lastmod' => $category->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }

        // Brands
        $brands = Brand::select('id', 'updated_at')->get();
        foreach ($brands as $brand) {
            $urls[] = [
                'loc' => $baseUrl . '/products?brand=' . $brand->id,
                'lastmod' => $brand->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }

        // Vehicle Models
        $vehicleModels = VehicleModel::select('id', 'updated_at')->get();
        foreach ($vehicleModels as $model) {
            $urls[] = [
                'loc' => $baseUrl . '/products?model=' . $model->id,
                'lastmod' => $model->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }

        return response()->view('sitemap.index', compact('urls'))
            ->header('Content-Type', 'text/xml');
    }
}
