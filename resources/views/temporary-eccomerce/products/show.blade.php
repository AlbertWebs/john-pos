@extends('layouts.app')

@section('title', $product->name . ' - ' . $product->category->name . ' | Speed and Style Hub Kenya')
@section('description', $product->description ?: 'Buy ' . $product->name . ' in Kenya. Quality ' . $product->category->name . ' at competitive prices. Fast delivery and excellent customer service from Speed and Style Hub.')
@section('keywords', $product->name . ', ' . $product->category->name . ', Fashion and Personal Care Products Kenya, Speed and Style Hub, buy online Kenya')
@section('og_title', $product->name . ' - ' . $product->category->name . ' | Speed and Style Hub Kenya')
@section('og_description', $product->description ?: 'Buy ' . $product->name . ' in Kenya. Quality ' . $product->category->name . ' at competitive prices.')
@section('og_type', 'product')
@section('og_image', $product->main_image_url)

@section('structured_data')
@php
    $description = $product->description ?: 'Buy ' . $product->name . ' in Kenya. Quality ' . $product->category->name . ' at competitive prices.';
@endphp
{!! '<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "' . addslashes($product->name) . '",
    "description": "' . addslashes($description) . '",
    "image": "' . $product->main_image_url . '",
    "url": "' . request()->url() . '",
    "category": "' . addslashes($product->category->name) . '",
    "brand": {
        "@type": "Brand",
        "name": "' . addslashes($product->brand ?? 'Speed and Style Hub') . '"
    },
    "offers": {
        "@type": "Offer",
        "price": "' . $product->price . '",
        "priceCurrency": "KES",
        "availability": "' . ($product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock') . '",
        "url": "' . request()->url() . '",
        "seller": {
            "@type": "Organization",
            "name": "Speed and Style Hub"
        }
    },
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "' . $product->rating . '",
        "reviewCount": "' . $product->reviews_count . '",
        "bestRating": "5",
        "worstRating": "1"
    }
}
</script>' !!}
@endsection

@section('content')
    <!-- Breadcrumb -->
    <section class="bg-gray-50 py-4">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-500">
                            <svg class="flex-shrink-0 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <a href="{{ route('products.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Products</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">{{ $product->category->name }}</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500">{{ $product->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </section>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Product Images -->
            <div class="space-y-4">
                <!-- Main Image -->
                <div class="aspect-w-1 aspect-h-1 w-full">
                    <img id="mainImage" src="{{ $product->main_image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-lg" style="aspect-ratio: 1/1;">
                </div>

                <!-- Thumbnail Gallery -->
                @if(count($product->all_images_urls) > 1)
                    <div class="relative">
                        <div class="flex space-x-2 overflow-x-auto scrollbar-hide" id="thumbnailContainer">
                            @foreach($product->all_images_urls as $index => $imageUrl)
                                <img src="{{ $imageUrl }}"
                                     alt="{{ $product->name }} - Image {{ $index + 1 }}"
                                     class="flex-shrink-0 w-20 h-20 object-cover rounded cursor-pointer border-2 {{ $index === 0 ? 'border-blue-500' : 'border-transparent hover:border-gray-300' }} thumbnail-image"
                                     data-image="{{ $imageUrl }}"
                                     style="width: 80px; height: 80px; min-width: 80px; max-width: 80px;">
                            @endforeach
                        </div>

                        <!-- Navigation Buttons -->
                        @if(count($product->all_images_urls) > 4)
                            <button class="scroll-left-btn absolute left-0 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-90 hover:bg-opacity-100 text-gray-800 p-1 rounded-full shadow-lg transition-all duration-200 z-10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button class="scroll-right-btn absolute right-0 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-90 hover:bg-opacity-100 text-gray-800 p-1 rounded-full shadow-lg transition-all duration-200 z-10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="space-y-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
                    <p class="text-gray-500 mt-2">{{ $product->category->name }}</p>
                </div>

                <!-- Rating -->
                <div class="flex items-center">
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $product->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="ml-2 text-sm text-gray-500">{{ $product->rating }} out of 5</span>
                    <span class="mx-2 text-gray-300">•</span>
                    <span class="text-sm text-gray-500">{{ $product->reviews_count }} reviews</span>
                </div>

                <!-- Price -->
                <div class="flex items-center space-x-4">
                    <span class="text-3xl font-bold text-gray-900">{{ $product->formatted_price }}</span>
                    @if($product->old_price && $product->old_price > $product->price)
                        <span class="text-xl text-gray-500 line-through">{{ $product->formatted_old_price }}</span>
                        <span class="bg-red-100 text-red-800 text-sm font-medium px-2 py-1 rounded">-{{ $product->discount_percentage }}%</span>
                    @endif
                </div>

                <!-- Badge -->
                @if($product->badge)
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ $product->badge }}
                    </div>
                @endif

                <!-- Description -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                </div>

                <!-- Stock Status -->
                <div class="flex items-center">
                    @if($product->stock_quantity > 0)
                        <div class="flex items-center text-green-600">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">In Stock ({{ $product->stock_quantity }} available)</span>
                        </div>
                    @else
                        <div class="flex items-center text-red-600">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">Out of Stock</span>
                        </div>
                    @endif
                </div>

                <!-- Quantity Selection -->
                <div class="flex items-center space-x-4">
                    <label class="text-sm font-medium text-gray-700">Quantity:</label>
                    <div class="flex items-center border border-gray-300 rounded-lg">
                        <button type="button" onclick="updateQuantity(-1)" class="px-3 py-2 text-gray-600 hover:text-gray-800">-</button>
                        <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="w-16 text-center border-0 focus:ring-0" readonly>
                        <button type="button" onclick="updateQuantity(1)" class="px-3 py-2 text-gray-600 hover:text-gray-800">+</button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-4">
                    <button onclick="addToCartWithQuantity({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, '{{ $product->main_image_url }}')"
                            class="flex-1 bg-black text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-800 transition-colors">
                        Add to Cart
                    </button>
                    <button onclick="toggleWishlist({{ $product->id }}, '{{ $product->name }}')"
                            class="w-12 h-12 border border-gray-300 rounded-lg flex items-center justify-center hover:border-gray-400 transition-colors wishlist-btn">
                        <i class="fas fa-heart text-gray-400"></i>
                    </button>
                </div>

                <!-- Quick Info -->
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">{{ $product->rating }}</div>
                        <div class="text-sm text-gray-500">Rating</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">{{ $product->reviews_count }}</div>
                        <div class="text-sm text-gray-500">Reviews</div>
                    </div>
                </div>
            </div>
        </div>

        @if($product->specifications)
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Specifications</h2>
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($product->specifications as $key => $value)
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-700">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                <span class="text-gray-600">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if($relatedProducts->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Products</h2>
                <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        <x-product-card :product="$relatedProduct" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
<style>
.scrollbar-hide {
    -ms-overflow-style: none;  /* Internet Explorer 10+ */
    scrollbar-width: none;  /* Firefox */
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;  /* Safari and Chrome */
}

.thumbnail-image {
    transition: all 0.2s ease-in-out;
    cursor: pointer;
    user-select: none;
}

.thumbnail-image:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.thumbnail-image:active {
    transform: scale(0.98);
}

#mainImage {
    transition: opacity 0.3s ease-in-out;
}

#mainImage.loading {
    opacity: 0.6;
}

/* Ensure thumbnails are clearly interactive */
.thumbnail-image {
    position: relative;
}

.thumbnail-image::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.1);
    opacity: 0;
    transition: opacity 0.2s ease-in-out;
    pointer-events: none;
}

.thumbnail-image:hover::after {
    opacity: 1;
}

/* Remove number input spinners */
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield; /* Firefox */
}

/* Ensure the quantity input looks clean */
#quantity {
    -webkit-appearance: none;
    -moz-appearance: textfield;
    appearance: textfield;
}
</style>
@endpush
