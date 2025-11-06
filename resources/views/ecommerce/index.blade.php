@extends('layouts.ecommerce')

@section('title', 'Home')

@php
    $settings = $settings ?? \Illuminate\Support\Facades\DB::table('settings')->pluck('value', 'key')->toArray();
@endphp

@section('content')
<!-- Hero Section -->


<!-- Category Products Sections -->
@if(isset($categoriesWithProducts) && $categoriesWithProducts->count() > 0)
    @foreach($categoriesWithProducts as $category)
    <section class="py-16 {{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">{{ $category->name }}</h2>

            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                @foreach($category->products as $product)
                <article class="bg-white border border-gray-200 overflow-hidden group" data-product-id="{{ $product->id }}">
                    <div class="relative">
                        <!-- Product Image with Link - Fixed Size -->
                        <a href="{{ route('ecommerce.product', $product->id) }}" class="block w-full bg-gray-100" style="height: 250px; display: flex; align-items: center; justify-content: center;">
                            @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-contain" style="max-width: 100%; max-height: 100%;">
                            @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                            </div>
                            @endif
                        </a>
                        
                        <!-- Sale Badge -->
                        <div class="absolute top-2 left-2">
                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                Sale
                            </span>
                        </div>
                        
                        <!-- Action Buttons Container -->
                        <div class="absolute top-2 right-2 flex flex-col space-y-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-out">
                            <!-- Add to Cart Button -->
                            <button class="bg-white rounded-full p-2 shadow-md hover:bg-blue-50 transition-all duration-200 z-10 w-8 h-8 flex items-center justify-center add-to-cart-btn"
                                    onclick="addToCart({{ $product->id }}, 1, this)"
                                    title="Add to Cart"
                                    data-product-id="{{ $product->id }}">
                                <i class="fas fa-shopping-cart text-gray-400 hover:text-blue-600 transition-colors text-sm"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Content Section -->
                    <div class="p-3 sm:p-4 flex flex-col" style="min-height: 120px;">
                        <!-- Product Name with Link -->
                        <a href="{{ route('ecommerce.product', $product->id) }}">
                            <h3 class="text-xs sm:text-sm font-medium text-gray-900 hover:text-blue-600 transition-colors line-clamp-2">{{ $product->name }}</h3>
                        </a>
                        
                        <!-- Price -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-auto">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-2">
                                @php
                                    $originalPrice = $product->selling_price * 1.3;
                                    $salePrice = $product->selling_price;
                                @endphp
                                <span class="text-xs sm:text-sm text-gray-500 line-through order-2 sm:order-1">KES {{ number_format($originalPrice, 2) }}</span>
                                <span class="text-base sm:text-lg font-bold text-gray-900 order-1 sm:order-2">KES {{ number_format($salePrice, 2) }}</span>
                            </div>
                            <span class="text-xs text-gray-500 mt-1 sm:mt-0">({{ $product->stock_quantity }})</span>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('ecommerce.products', ['category' => $category->id]) }}" class="inline-block border-2 border-gray-900 text-gray-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-900 hover:text-white transition-colors">
                    View All {{ $category->name }} →
                </a>
            </div>
        </div>
    </section>
    @endforeach
@else
    <!-- Fallback: Show all products if no categories with products -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Our Trending Products</h2>
            @php
                $fallbackProducts = \App\Models\Inventory::where('status', 'active')
                    ->where('stock_quantity', '>', 0)
                    ->with(['brand', 'category'])
                    ->orderBy('stock_quantity', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->limit(8)
                    ->get();
            @endphp
            @if($fallbackProducts->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                @foreach($fallbackProducts as $product)
                <article class="bg-white border border-gray-200 overflow-hidden group" data-product-id="{{ $product->id }}">
                    <div class="relative">
                        <a href="{{ route('ecommerce.product', $product->id) }}" class="block w-full bg-gray-100" style="height: 250px; display: flex; align-items: center; justify-content: center;">
                            @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-contain" style="max-width: 100%; max-height: 100%;">
                            @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                            </div>
                            @endif
                        </a>
                        <div class="absolute top-2 left-2">
                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">Sale</span>
                        </div>
                        <div class="absolute top-2 right-2 flex flex-col space-y-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-out">
                            <button class="bg-white rounded-full p-2 shadow-md hover:bg-blue-50 transition-all duration-200 z-10 w-8 h-8 flex items-center justify-center add-to-cart-btn"
                                    onclick="addToCart({{ $product->id }}, 1, this)"
                                    title="Add to Cart"
                                    data-product-id="{{ $product->id }}">
                                <i class="fas fa-shopping-cart text-gray-400 hover:text-blue-600 transition-colors text-sm"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-3 sm:p-4 flex flex-col" style="min-height: 120px;">
                        <a href="{{ route('ecommerce.product', $product->id) }}">
                            <h3 class="text-xs sm:text-sm font-medium text-gray-900 hover:text-blue-600 transition-colors line-clamp-2">{{ $product->name }}</h3>
                        </a>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-auto">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-2">
                                @php
                                    $originalPrice = $product->selling_price * 1.3;
                                    $salePrice = $product->selling_price;
                                @endphp
                                <span class="text-xs sm:text-sm text-gray-500 line-through order-2 sm:order-1">KES {{ number_format($originalPrice, 2) }}</span>
                                <span class="text-base sm:text-lg font-bold text-gray-900 order-1 sm:order-2">KES {{ number_format($salePrice, 2) }}</span>
                            </div>
                            <span class="text-xs text-gray-500 mt-1 sm:mt-0">({{ $product->stock_quantity }})</span>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <p class="text-gray-500">No products available at the moment.</p>
            </div>
            @endif
        </div>
    </section>
@endif

<!-- Brand Logos -->
@if($brands->count() > 0)
<section class="bg-gray-100 py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-center items-center gap-4 sm:gap-6 lg:gap-12">
            @foreach($brands as $brand)
            <div class="text-center">
                <div class="w-16 h-12 sm:w-16 sm:h-16 bg-white rounded-full flex items-center justify-center mb-2">
                    @if($brand->image)
                    <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->brand_name }}" class="max-h-12 w-auto object-contain">
                    @else
                    <span class="text-xs sm:text-sm font-semibold text-gray-600">{{ $brand->brand_name }}</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Big Sale Banner -->
<section class="bg-gray-900 text-white py-16">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl md:text-5xl font-bold mb-4">Big Sale Up To 70% Off</h2>
        <p class="text-xl mb-8 text-gray-300">Exclusive Offers For Limited Time</p>
        <a href="{{ route('ecommerce.products') }}" class="inline-block bg-white text-gray-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
            Explore Your Order
        </a>
    </div>
</section>

<!-- Trending Categories -->
@if($categories->count() > 0)
<section class="py-16">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-12">Trending Categories</h2>

        <div class="grid grid-cols-3 md:grid-cols-3 lg:grid-cols-6 gap-4 md:gap-6">
            @foreach($categories as $category)
                <a href="{{ route('ecommerce.products', ['category' => $category->id]) }}" class="text-center block">
                    <div class="w-16 h-16 md:w-20 md:h-20 border-2 border-gray-200 rounded-full flex items-center justify-center mx-auto mb-3 hover:border-gray-400 transition-colors">
                        @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-12 h-12 object-contain rounded-full">
                        @else
                        <i class="fas fa-tag text-xl md:text-2xl text-gray-600"></i>
                        @endif
                    </div>
                    <p class="text-sm font-medium text-gray-700 hidden md:block">{{ $category->name }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Product Grids -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Top Seller -->
            <div>
                <h3 class="text-xl font-semibold mb-6">Top Seller</h3>
                <div class="grid grid-cols-2 gap-3 md:gap-4 lg:space-y-4 lg:grid-cols-1">
                    @foreach($featuredProducts->take(3) as $product)
                    <article class="bg-white border border-gray-200 overflow-hidden group">
                        <a href="{{ route('ecommerce.product', $product->id) }}" class="flex">
                            <div class="w-24 h-24 flex-shrink-0">
                                @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                                </div>
                                @endif
                            </div>
                            <div class="p-3 flex-1">
                                <div class="flex items-start gap-2 mb-1">
                                    <span class="bg-red-500 text-white px-2 py-0.5 rounded text-xs font-semibold">Sale</span>
                                </div>
                                <h4 class="font-semibold text-gray-900 text-sm mb-1 line-clamp-2">{{ $product->name }}</h4>
                                <div class="flex items-center justify-between">
                                    @php
                                        $originalPrice = $product->selling_price * 1.3;
                                        $salePrice = $product->selling_price;
                                    @endphp
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-gray-900">KES {{ number_format($salePrice, 2) }}</span>
                                        <span class="text-xs text-gray-500 line-through">KES {{ number_format($originalPrice, 2) }}</span>
                                    </div>
                                    <span class="text-xs text-gray-500">({{ $product->stock_quantity }})</span>
                                </div>
                            </div>
                        </a>
                    </article>
                    @endforeach
                </div>
            </div>

            <!-- Featured Products -->
            <div>
                <h3 class="text-xl font-semibold mb-6">Featured Products</h3>
                <div class="grid grid-cols-2 gap-3 md:gap-4 lg:space-y-4 lg:grid-cols-1">
                    @foreach($featuredProducts->take(3) as $product)
                    <article class="bg-white border border-gray-200 overflow-hidden group">
                        <a href="{{ route('ecommerce.product', $product->id) }}" class="flex">
                            <div class="w-24 h-24 flex-shrink-0">
                                @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                                </div>
                                @endif
                            </div>
                            <div class="p-3 flex-1">
                                <h4 class="font-semibold text-gray-900 text-sm mb-1 line-clamp-2">{{ $product->name }}</h4>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-gray-900">KES {{ number_format($product->selling_price, 2) }}</span>
                                    <span class="text-xs text-gray-500">({{ $product->stock_quantity }})</span>
                                </div>
                            </div>
                        </a>
                    </article>
                    @endforeach
                </div>
            </div>

            <!-- Recent Products -->
            <div class="md:col-span-2 lg:col-span-1">
                <h3 class="text-xl font-semibold mb-6">Recent Products</h3>
                <div class="grid grid-cols-2 gap-3 md:gap-4 lg:space-y-4 lg:grid-cols-1">
                    @foreach($recentProducts->take(3) as $product)
                    <article class="bg-white border border-gray-200 overflow-hidden group">
                        <a href="{{ route('ecommerce.product', $product->id) }}" class="flex">
                            <div class="w-24 h-24 flex-shrink-0">
                                @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                                </div>
                                @endif
                            </div>
                            <div class="p-3 flex-1">
                                <div class="flex items-start gap-2 mb-1">
                                    <span class="bg-red-500 text-white px-2 py-0.5 rounded text-xs font-semibold">Sale</span>
                                </div>
                                <h4 class="font-semibold text-gray-900 text-sm mb-1 line-clamp-2">{{ $product->name }}</h4>
                                <div class="flex items-center justify-between">
                                    @php
                                        $originalPrice = $product->selling_price * 1.3;
                                        $salePrice = $product->selling_price;
                                    @endphp
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-gray-900">KES {{ number_format($salePrice, 2) }}</span>
                                        <span class="text-xs text-gray-500 line-through">KES {{ number_format($originalPrice, 2) }}</span>
                                    </div>
                                    <span class="text-xs text-gray-500">({{ $product->stock_quantity }})</span>
                                </div>
                            </div>
                        </a>
                    </article>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
