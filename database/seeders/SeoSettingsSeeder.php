<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SeoSettings;
use Illuminate\Support\Facades\DB;

class SeoSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seoSettings = [
            [
                'page_type' => 'homepage',
                'meta_title' => 'Top Auto Spares Shop in Wangige | Quality Car Parts & Vehicle Spares',
                'meta_description' => 'Find quality auto spares, car parts, and vehicle spares in Wangige. Top-selling auto spares shop with wide range of categories, brands, and vehicle models. Fast delivery and competitive prices.',
                'meta_keywords' => 'auto spares, car parts, vehicle spares, Wangige, auto parts shop, car accessories, vehicle parts, auto spares Wangige, top auto spares shop Wangige',
                'og_title' => 'Top Auto Spares Shop in Wangige | Quality Car Parts',
                'og_description' => 'Find quality auto spares, car parts, and vehicle spares in Wangige. Top-selling auto spares shop with wide range of products.',
                'og_image' => null,
                'structured_data' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'LocalBusiness',
                    'name' => 'Johlly Auto Spares',
                    'description' => 'Top-selling auto spares shop in Wangige. Quality car parts, vehicle spares, and auto accessories.',
                    'address' => [
                        '@type' => 'PostalAddress',
                        'addressLocality' => 'Wangige',
                        'addressRegion' => 'Kiambu',
                        'addressCountry' => 'KE',
                    ],
                    'areaServed' => [
                        '@type' => 'City',
                        'name' => 'Wangige',
                    ],
                ]),
                'custom_meta_tags' => '<meta name="geo.region" content="KE-20"><meta name="geo.placename" content="Wangige">',
            ],
            [
                'page_type' => 'products',
                'meta_title' => 'Auto Spares & Car Parts in Wangige | Browse Our Products',
                'meta_description' => 'Browse our extensive collection of auto spares and car parts in Wangige. Quality products from top brands. Find the perfect parts for your vehicle. Categories, brands, and vehicle models available.',
                'meta_keywords' => 'auto spares, car parts, vehicle parts, Wangige, auto parts, car accessories, vehicle spares, auto spares shop Wangige',
                'og_title' => 'Auto Spares & Car Parts in Wangige',
                'og_description' => 'Browse our extensive collection of auto spares and car parts in Wangige. Quality products from top brands.',
                'og_image' => null,
                'structured_data' => null,
                'custom_meta_tags' => null,
            ],
            [
                'page_type' => 'product_detail',
                'meta_title' => '{product_name} | Auto Spares in Wangige | {brand_name}',
                'meta_description' => 'Buy {product_name} in Wangige. Quality auto spares and car parts from {brand_name}. Part number: {part_number}. Stock available. Fast delivery.',
                'meta_keywords' => '{product_name}, auto spares, car parts, Wangige, {category_name}, {brand_name}, vehicle parts',
                'og_title' => '{product_name} | Auto Spares in Wangige',
                'og_description' => 'Buy {product_name} in Wangige. Quality auto spares and car parts. Part number: {part_number}.',
                'og_image' => null,
                'structured_data' => null,
                'custom_meta_tags' => null,
            ],
            [
                'page_type' => 'categories',
                'meta_title' => 'Auto Spares Categories | Browse by Category in Wangige',
                'meta_description' => 'Browse auto spares by category in Wangige. Find car parts organized by category. Quality products from top brands. All vehicle parts categories available.',
                'meta_keywords' => 'auto spares categories, car parts categories, vehicle parts, Wangige, auto parts by category',
                'og_title' => 'Auto Spares Categories in Wangige',
                'og_description' => 'Browse auto spares by category in Wangige. Find car parts organized by category.',
                'og_image' => null,
                'structured_data' => null,
                'custom_meta_tags' => null,
            ],
            [
                'page_type' => 'category_detail',
                'meta_title' => '{category_name} Auto Spares in Wangige | Quality Car Parts',
                'meta_description' => 'Find {category_name} auto spares in Wangige. Quality car parts and vehicle spares. Browse our collection of {category_name} products. Top brands available.',
                'meta_keywords' => '{category_name}, auto spares, car parts, Wangige, vehicle parts, {category_name} Wangige',
                'og_title' => '{category_name} Auto Spares in Wangige',
                'og_description' => 'Find {category_name} auto spares in Wangige. Quality car parts and vehicle spares.',
                'og_image' => null,
                'structured_data' => null,
                'custom_meta_tags' => null,
            ],
            [
                'page_type' => 'brands',
                'meta_title' => 'Auto Spares Brands | Top Brands in Wangige',
                'meta_description' => 'Browse auto spares by brand in Wangige. Quality products from top brands. Find the perfect parts for your vehicle. All major brands available.',
                'meta_keywords' => 'auto spares brands, car parts brands, vehicle parts brands, Wangige, auto parts brands',
                'og_title' => 'Auto Spares Brands in Wangige',
                'og_description' => 'Browse auto spares by brand in Wangige. Quality products from top brands.',
                'og_image' => null,
                'structured_data' => null,
                'custom_meta_tags' => null,
            ],
            [
                'page_type' => 'brand_detail',
                'meta_title' => '{brand_name} Auto Spares in Wangige | Quality Car Parts',
                'meta_description' => 'Find {brand_name} auto spares in Wangige. Quality car parts and vehicle spares from {brand_name}. Browse our collection. Fast delivery available.',
                'meta_keywords' => '{brand_name}, auto spares, car parts, Wangige, vehicle parts, {brand_name} Wangige',
                'og_title' => '{brand_name} Auto Spares in Wangige',
                'og_description' => 'Find {brand_name} auto spares in Wangige. Quality car parts and vehicle spares.',
                'og_image' => null,
                'structured_data' => null,
                'custom_meta_tags' => null,
            ],
            [
                'page_type' => 'vehicle_models',
                'meta_title' => 'Vehicle Model Auto Spares in Wangige | Find Parts by Model',
                'meta_description' => 'Find auto spares by vehicle model in Wangige. Quality car parts for all vehicle models. Browse by make and model. All major vehicle brands supported.',
                'meta_keywords' => 'vehicle model spares, car parts by model, auto spares by vehicle, Wangige, vehicle parts by model',
                'og_title' => 'Vehicle Model Auto Spares in Wangige',
                'og_description' => 'Find auto spares by vehicle model in Wangige. Quality car parts for all vehicle models.',
                'og_image' => null,
                'structured_data' => null,
                'custom_meta_tags' => null,
            ],
            [
                'page_type' => 'vehicle_model_detail',
                'meta_title' => '{make_name} {model_name} Auto Spares in Wangige | Car Parts',
                'meta_description' => 'Find {make_name} {model_name} auto spares in Wangige. Quality car parts and vehicle spares for {make_name} {model_name}. Browse our collection. Fast delivery.',
                'meta_keywords' => '{make_name} {model_name}, auto spares, car parts, Wangige, vehicle parts, {make_name} {model_name} spares',
                'og_title' => '{make_name} {model_name} Auto Spares in Wangige',
                'og_description' => 'Find {make_name} {model_name} auto spares in Wangige. Quality car parts and vehicle spares.',
                'og_image' => null,
                'structured_data' => null,
                'custom_meta_tags' => null,
            ],
            [
                'page_type' => 'cart',
                'meta_title' => 'Shopping Cart | Auto Spares Shop Wangige',
                'meta_description' => 'Review your shopping cart at the top auto spares shop in Wangige. Quality car parts and vehicle spares ready for checkout.',
                'meta_keywords' => 'shopping cart, auto spares, car parts, Wangige',
                'og_title' => 'Shopping Cart | Auto Spares Shop Wangige',
                'og_description' => 'Review your shopping cart at the top auto spares shop in Wangige.',
                'og_image' => null,
                'structured_data' => null,
                'custom_meta_tags' => '<meta name="robots" content="noindex, follow">',
            ],
            [
                'page_type' => 'checkout',
                'meta_title' => 'Checkout | Auto Spares Shop Wangige',
                'meta_description' => 'Complete your order at the top auto spares shop in Wangige. Secure checkout with M-Pesa and cash on delivery options.',
                'meta_keywords' => 'checkout, auto spares, car parts, Wangige, order',
                'og_title' => 'Checkout | Auto Spares Shop Wangige',
                'og_description' => 'Complete your order at the top auto spares shop in Wangige.',
                'og_image' => null,
                'structured_data' => null,
                'custom_meta_tags' => '<meta name="robots" content="noindex, follow">',
            ],
        ];

        foreach ($seoSettings as $setting) {
            SeoSettings::updateOrCreate(
                ['page_type' => $setting['page_type']],
                $setting
            );
        }

        $this->command->info('SEO settings seeded successfully!');
    }
}
