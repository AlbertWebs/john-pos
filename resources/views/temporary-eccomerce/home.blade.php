@extends('layouts.app')

@section('title', 'Speed and Style Hub - Trendy Fashion & Beauty Products in Kenya')
@section('description', 'Shop trendy fashion and premium beauty products in Kenya. Discover stylish outfits, skincare, makeup, accessories and more at Speed and Style Hub.')
@section('keywords', 'fashion Kenya, beauty products, makeup Nairobi, skincare Kenya, women clothing, men fashion, Speed and Style Hub, online clothing store')
@section('og_title', 'Speed and Style Hub - Trendy Fashion & Beauty Products in Kenya')
@section('og_description', 'Shop trendy fashion and premium beauty products in Kenya. Discover stylish outfits, skincare, makeup, accessories and more.')
@section('og_type', 'website')
@section('og_image', asset('images/logo.svg'))

@section('structured_data')
@php
    $socialUrls = \App\Helpers\SocialMediaHelper::getSameAsArray();
    $sameAsJson = '';
    if (!empty($socialUrls)) {
        $sameAsJson = '"' . implode('","', $socialUrls) . '"';
    } else {
        $sameAsJson = '"https://example.com"';
    }
@endphp
{!! '<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Speed and Style Hub",
    "url": "' . url('/') . '",
    "logo": "' . asset('images/logo.svg') . '",
    "description": "Your go-to shop for stylish clothing and quality beauty products in Kenya",
    "address": {
        "@type": "PostalAddress",
        "addressCountry": "KE",
        "addressLocality": "Nairobi"
    },
    "contactPoint": {
        "@type": "ContactPoint",
        "contactType": "customer service"
    },
    "sameAs": [
        ' . $sameAsJson . '
    ]
}
</script>' !!}

{!! '<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "Speed and Style Hub",
    "url": "' . url('/') . '",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "' . url('/products') . '?search={search_term_string}",
        "query-input": "required name=search_term_string"
    }
}
</script>' !!}
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="bg-white py-0 lg:py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Categories Sidebar -->
                <div class="hidden lg:block lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold mb-4">Top Categories</h3>
                        <div class="space-y-3">
                            @foreach($categories->take(6) as $category)
                                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg">
                                    <i class="{{ $category->icon }} text-gray-600"></i>
                                    <span class="text-gray-700">{{ $category->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Main Banner Carousel -->
                <div class="lg:col-span-3 relative">
                    @if($carouselSlides->count() > 0)
                        <div class="carousel-container relative overflow-hidden rounded-none lg:rounded-lg -mx-4 lg:mx-0">
                            @foreach($carouselSlides as $index => $slide)
                                <div class="carousel-slide {{ $index === 0 ? 'active' : '' }} bg-gradient-to-r {{ $slide->background_classes }} p-4 lg:p-8">
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-8 items-center">
                                        <div>
                                            <span class="{{ $slide->text_color_class }} font-semibold text-sm lg:text-base">{{ $slide->title }}</span>
                                            <h2 class="text-2xl lg:text-3xl xl:text-4xl font-bold text-gray-900 mt-2 mb-4">{{ $slide->title }}</h2>
                                            <p class="text-gray-600 mb-6 text-sm lg:text-base">{{ $slide->description }}</p>
                                            @if($slide->button_text)
                                                <a href="{{ $slide->button_link ?? route('products.index') }}" class="inline-block bg-black text-white px-4 py-2 lg:px-6 lg:py-3 rounded-lg font-semibold hover:bg-gray-800 text-sm lg:text-base">
                                                    {{ $slide->button_text }} →
                                                </a>
                                            @endif
                                        </div>
                                        @if($slide->image)
                                            <div class="hidden lg:flex justify-center lg:justify-end">
                                                <img src="{{ Storage::url($slide->image) }}" alt="{{ $slide->title }}" class="max-w-xs lg:max-w-sm object-cover rounded-lg shadow-lg">
                                            </div>
                                        @else
                                            <div class="hidden lg:flex justify-center lg:justify-end">
                                                <div class="w-48 h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            <!-- Carousel Navigation -->
                            <button class="carousel-btn carousel-prev absolute left-2 lg:left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 text-gray-800 p-2 rounded-full shadow-lg transition-all duration-200">
                                <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button class="carousel-btn carousel-next absolute right-2 lg:right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 text-gray-800 p-2 rounded-full shadow-lg transition-all duration-200">
                                <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Carousel Indicators -->
                        <div class="absolute bottom-2 lg:bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-1 lg:space-x-2 z-20">
                            @foreach($carouselSlides as $index => $slide)
                                <button class="carousel-dot w-6 h-1 lg:w-8 lg:h-1 bg-gray-800 {{ $index === 0 ? 'bg-opacity-80' : 'bg-opacity-50' }} hover:bg-opacity-100 rounded-full transition-all duration-200" data-slide="{{ $index }}"></button>
                            @endforeach
                        </div>
                    @else
                        <!-- Fallback when no slides are available -->
                        <div class="bg-gradient-to-r from-blue-100 to-blue-200 p-4 lg:p-8 rounded-none lg:rounded-lg -mx-4 lg:mx-0">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-8 items-center">
                                <div>
                                    <span class="text-blue-600 font-semibold text-sm lg:text-base">Welcome</span>
                                    <h2 class="text-2xl lg:text-3xl xl:text-4xl font-bold text-gray-900 mt-2 mb-4">Speed and Style Hub</h2>
                                    <p class="text-gray-600 mb-6 text-sm lg:text-base">Your trusted source for quality home and fashion</p>
                                    <a href="{{ route('products.index') }}" class="inline-block bg-black text-white px-4 py-2 lg:px-6 lg:py-3 rounded-lg font-semibold hover:bg-gray-800 text-sm lg:text-base">
                                        Shop Now →
                                    </a>
                                </div>
                                <div class="hidden lg:flex justify-center lg:justify-end">
                                    <img src="{{ asset('assets/images/holder.jpeg') }}" alt="Fashion Product" class="max-w-xs lg:max-w-sm object-cover rounded-lg shadow-lg">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Trending Products -->
    <section class="py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Our Trending Products</h2>

            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                @foreach($trendingProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('products.index') }}" class="inline-block border-2 border-gray-900 text-gray-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-900 hover:text-white transition-colors">
                    Explore More →
                </a>
            </div>
        </div>
    </section>

    <!-- Brand Logos -->
    <section class="bg-gray-100 py-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-center items-center gap-4 sm:gap-6 lg:gap-12">
                <div class="text-center">
                    <div class="w-16 h-12 sm:w-16 sm:h-16 bg-white rounded-full flex items-center justify-center mb-2">
                        <span class="text-xs sm:text-sm font-semibold text-gray-600">LC Waikiki</span>
                    </div>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white rounded-full flex items-center justify-center mb-2">
                        <span class="text-xs font-semibold text-gray-600">Dr Davey</span>
                    </div>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white rounded-full flex items-center justify-center mb-2">
                        <span class="text-xs sm:text-sm font-semibold text-gray-600">Vaseline</span>
                    </div>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white rounded-full flex items-center justify-center mb-2">
                        <span class="text-xs sm:text-sm font-semibold text-gray-600">Dear Body</span>
                    </div>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white rounded-full flex items-center justify-center mb-2">
                        <span class="text-xs sm:text-sm font-semibold text-gray-600">St Ives</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Big Sale Banner -->
    <x-banner
        title="Big Sale Up To 70% Off"
        subtitle="Exclusive Offers For Limited Time"
        buttonText="Explore Your Order"
        buttonLink="{{ route('products.index') }}"
        backgroundColor="bg-gray-900"
    />

    <!-- Trending Categories -->
    <section class="py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Trending Categories</h2>

            <div class="grid grid-cols-3 md:grid-cols-3 lg:grid-cols-6 gap-4 md:gap-6">
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="text-center block">
                        <div class="w-16 h-16 md:w-20 md:h-20 border-2 border-gray-200 rounded-full flex items-center justify-center mx-auto mb-3 hover:border-gray-400 transition-colors">
                            <i class="{{ $category->icon }} text-xl md:text-2xl text-gray-600"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-700 hidden md:block">{{ $category->name }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Product Grids -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Top Seller -->
                <div>
                    <h3 class="text-xl font-semibold mb-6">Top Seller</h3>
                    <div class="grid grid-cols-2 gap-3 md:gap-4 lg:space-y-4 lg:grid-cols-1">
                        @foreach($topSellers as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                </div>

                <!-- Featured Products -->
                <div>
                    <h3 class="text-xl font-semibold mb-6">Featured Products</h3>
                    <div class="grid grid-cols-2 gap-3 md:gap-4 lg:space-y-4 lg:grid-cols-1">
                        @foreach($featuredProducts as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                </div>

                <!-- Recent Products -->
                <div class="md:col-span-2 lg:col-span-1">
                    <h3 class="text-xl font-semibold mb-6">Recent Products</h3>
                    <div class="grid grid-cols-2 gap-3 md:gap-4 lg:space-y-4 lg:grid-cols-1">
                        @foreach($recentProducts as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
