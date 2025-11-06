<?php

namespace App\Services;

use App\Models\SeoSettings;
use Illuminate\Support\Facades\Cache;

class SeoService
{
    /**
     * Get SEO settings for a page type
     */
    public static function getSeoSettings($pageType, $dynamicData = [])
    {
        $cacheKey = "seo_settings_{$pageType}";
        
        $seoSettings = Cache::remember($cacheKey, 3600, function () use ($pageType) {
            return SeoSettings::where('page_type', $pageType)->first();
        });

        if (!$seoSettings) {
            // Return default SEO settings
            return self::getDefaultSeoSettings($pageType, $dynamicData);
        }

        // Replace dynamic placeholders
        $metaTitle = self::replacePlaceholders($seoSettings->meta_title ?? '', $dynamicData);
        $metaDescription = self::replacePlaceholders($seoSettings->meta_description ?? '', $dynamicData);
        $ogTitle = self::replacePlaceholders($seoSettings->og_title ?? $metaTitle, $dynamicData);
        $ogDescription = self::replacePlaceholders($seoSettings->og_description ?? $metaDescription, $dynamicData);

        return [
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'meta_keywords' => self::replacePlaceholders($seoSettings->meta_keywords ?? '', $dynamicData),
            'og_title' => $ogTitle,
            'og_description' => $ogDescription,
            'og_image' => $seoSettings->og_image ?? asset('images/default-og-image.jpg'),
            'structured_data' => $seoSettings->structured_data ?? null,
            'custom_meta_tags' => $seoSettings->custom_meta_tags ?? '',
        ];
    }

    /**
     * Get default SEO settings if none are configured
     */
    private static function getDefaultSeoSettings($pageType, $dynamicData = [])
    {
        $defaults = [
            'homepage' => [
                'meta_title' => 'Top Auto Spares Shop in Wangige | Quality Car Parts & Vehicle Spares',
                'meta_description' => 'Find quality auto spares, car parts, and vehicle spares in Wangige. Top-selling auto spares shop with wide range of categories, brands, and vehicle models. Fast delivery and competitive prices.',
                'meta_keywords' => 'auto spares, car parts, vehicle spares, Wangige, auto parts shop, car accessories, vehicle parts, auto spares Wangige',
            ],
            'products' => [
                'meta_title' => 'Auto Spares & Car Parts in Wangige | Browse Our Products',
                'meta_description' => 'Browse our extensive collection of auto spares and car parts in Wangige. Quality products from top brands. Find the perfect parts for your vehicle.',
                'meta_keywords' => 'auto spares, car parts, vehicle parts, Wangige, auto parts, car accessories',
            ],
            'product_detail' => [
                'meta_title' => '{product_name} | Auto Spares in Wangige',
                'meta_description' => 'Buy {product_name} in Wangige. Quality auto spares and car parts. Part number: {part_number}. Stock available.',
                'meta_keywords' => '{product_name}, auto spares, car parts, Wangige, {category_name}, {brand_name}',
            ],
            'categories' => [
                'meta_title' => 'Auto Spares Categories | Browse by Category in Wangige',
                'meta_description' => 'Browse auto spares by category in Wangige. Find car parts organized by category. Quality products from top brands.',
                'meta_keywords' => 'auto spares categories, car parts categories, vehicle parts, Wangige',
            ],
            'category_detail' => [
                'meta_title' => '{category_name} Auto Spares in Wangige | Quality Car Parts',
                'meta_description' => 'Find {category_name} auto spares in Wangige. Quality car parts and vehicle spares. Browse our collection of {category_name} products.',
                'meta_keywords' => '{category_name}, auto spares, car parts, Wangige, vehicle parts',
            ],
            'brands' => [
                'meta_title' => 'Auto Spares Brands | Top Brands in Wangige',
                'meta_description' => 'Browse auto spares by brand in Wangige. Quality products from top brands. Find the perfect parts for your vehicle.',
                'meta_keywords' => 'auto spares brands, car parts brands, vehicle parts brands, Wangige',
            ],
            'brand_detail' => [
                'meta_title' => '{brand_name} Auto Spares in Wangige | Quality Car Parts',
                'meta_description' => 'Find {brand_name} auto spares in Wangige. Quality car parts and vehicle spares from {brand_name}. Browse our collection.',
                'meta_keywords' => '{brand_name}, auto spares, car parts, Wangige, vehicle parts',
            ],
            'vehicle_models' => [
                'meta_title' => 'Vehicle Model Auto Spares in Wangige | Find Parts by Model',
                'meta_description' => 'Find auto spares by vehicle model in Wangige. Quality car parts for all vehicle models. Browse by make and model.',
                'meta_keywords' => 'vehicle model spares, car parts by model, auto spares by vehicle, Wangige',
            ],
            'vehicle_model_detail' => [
                'meta_title' => '{make_name} {model_name} Auto Spares in Wangige | Car Parts',
                'meta_description' => 'Find {make_name} {model_name} auto spares in Wangige. Quality car parts and vehicle spares for {make_name} {model_name}.',
                'meta_keywords' => '{make_name} {model_name}, auto spares, car parts, Wangige, vehicle parts',
            ],
        ];

        $default = $defaults[$pageType] ?? $defaults['homepage'];
        
        return [
            'meta_title' => self::replacePlaceholders($default['meta_title'] ?? '', $dynamicData),
            'meta_description' => self::replacePlaceholders($default['meta_description'] ?? '', $dynamicData),
            'meta_keywords' => self::replacePlaceholders($default['meta_keywords'] ?? '', $dynamicData),
            'og_title' => self::replacePlaceholders($default['meta_title'] ?? '', $dynamicData),
            'og_description' => self::replacePlaceholders($default['meta_description'] ?? '', $dynamicData),
            'og_image' => asset('images/default-og-image.jpg'),
            'structured_data' => null,
            'custom_meta_tags' => '',
        ];
    }

    /**
     * Replace placeholders in SEO text
     */
    private static function replacePlaceholders($text, $data)
    {
        $placeholders = [
            '{product_name}' => $data['product_name'] ?? '',
            '{part_number}' => $data['part_number'] ?? '',
            '{category_name}' => $data['category_name'] ?? '',
            '{brand_name}' => $data['brand_name'] ?? '',
            '{make_name}' => $data['make_name'] ?? '',
            '{model_name}' => $data['model_name'] ?? '',
            '{price}' => isset($data['price']) ? 'KES ' . number_format($data['price'], 2) : '',
            '{company_name}' => $data['company_name'] ?? 'Johlly Auto Spares',
        ];

        foreach ($placeholders as $placeholder => $value) {
            $text = str_replace($placeholder, $value, $text);
        }

        return $text;
    }

    /**
     * Generate structured data for a product
     */
    public static function generateProductStructuredData($product, $settings = [])
    {
        $companyName = $settings['company_name'] ?? 'Johlly Auto Spares';
        $siteUrl = url('/');
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => $product->description ?? $product->name,
            'sku' => $product->part_number,
            'brand' => [
                '@type' => 'Brand',
                'name' => $product->brand->brand_name ?? 'Unknown',
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => $product->selling_price,
                'priceCurrency' => 'KES',
                'availability' => $product->stock_quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller' => [
                    '@type' => 'LocalBusiness',
                    'name' => $companyName,
                    'address' => [
                        '@type' => 'PostalAddress',
                        'addressLocality' => 'Wangige',
                        'addressCountry' => 'KE',
                    ],
                ],
            ],
            'image' => $product->image ? asset('storage/' . $product->image) : null,
        ];
    }

    /**
     * Generate structured data for LocalBusiness
     */
    public static function generateLocalBusinessStructuredData($settings = [])
    {
        $companyName = $settings['company_name'] ?? 'Johlly Auto Spares';
        $phone = $settings['phone'] ?? '';
        $email = $settings['email'] ?? '';
        $address = $settings['address'] ?? 'Wangige, Kenya';
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => $companyName,
            'description' => 'Top-selling auto spares shop in Wangige. Quality car parts, vehicle spares, and auto accessories.',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Wangige',
                'addressRegion' => 'Kiambu',
                'addressCountry' => 'KE',
                'streetAddress' => $address,
            ],
            'telephone' => $phone,
            'email' => $email,
            'url' => url('/'),
            'priceRange' => '$$',
            'areaServed' => [
                '@type' => 'City',
                'name' => 'Wangige',
            ],
        ];
    }
}

