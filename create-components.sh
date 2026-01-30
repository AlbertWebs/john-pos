#!/bin/bash

# Script to create missing Blade components on the server
# Run this from your Laravel project root: bash create-components.sh

COMPONENTS_DIR="resources/views/components"

# Create components directory if it doesn't exist
mkdir -p "$COMPONENTS_DIR"

# Create input-label component
cat > "$COMPONENTS_DIR/input-label.blade.php" << 'EOF'
@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}
</label>
EOF

# Create text-input component
cat > "$COMPONENTS_DIR/text-input.blade.php" << 'EOF'
@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) }}>
EOF

# Create input-error component
cat > "$COMPONENTS_DIR/input-error.blade.php" << 'EOF'
@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
EOF

# Create primary-button component
cat > "$COMPONENTS_DIR/primary-button.blade.php" << 'EOF'
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
EOF

# Create guest-layout component
cat > "$COMPONENTS_DIR/guest-layout.blade.php" << 'EOF'
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
EOF

# Create application-logo component
cat > "$COMPONENTS_DIR/application-logo.blade.php" << 'EOF'
<svg viewBox="0 0 316 316" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <path d="M305.8 81.125C305.77 80.995 305.69 80.885 305.65 80.755C305.56 80.525 305.49 80.285 305.37 80.075C305.29 79.935 305.17 79.815 305.07 79.685C304.94 79.515 304.83 79.325 304.68 79.175C304.55 79.045 304.39 78.955 304.25 78.845C304.09 78.715 303.95 78.575 303.77 78.475L251.32 48.275C249.97 47.495 248.31 47.495 246.96 48.275L194.51 78.475C194.33 78.575 194.19 78.725 194.03 78.845C193.89 78.955 193.73 79.045 193.6 79.175C193.45 79.325 193.34 79.515 193.21 79.685C193.11 79.815 192.99 79.935 192.91 80.075C192.79 80.285 192.71 80.525 192.63 80.755C192.58 80.875 192.51 80.995 192.48 81.125C192.38 81.495 192.33 81.875 192.33 82.265V139.625L148.62 164.795V52.575C148.62 52.185 148.57 51.805 148.47 51.435C148.44 51.305 148.36 51.195 148.32 51.065C148.23 50.835 148.16 50.595 148.04 50.385C147.96 50.245 147.84 50.125 147.74 49.995C147.61 49.825 147.5 49.635 147.35 49.485C147.22 49.355 147.06 49.265 146.92 49.155C146.76 49.025 146.62 48.885 146.44 48.785L93.99 18.585C92.64 17.805 90.98 17.805 89.63 18.585L37.18 48.785C37 48.885 36.86 49.035 36.7 49.155C36.56 49.265 36.4 49.355 36.27 49.485C36.12 49.635 36.01 49.825 35.88 49.995C35.78 50.125 35.66 50.245 35.58 50.385C35.46 50.595 35.38 50.835 35.3 51.065C35.25 51.185 35.18 51.305 35.15 51.435C35.05 51.805 35 52.185 35 52.575V232.235C35 233.795 35.84 235.245 37.19 236.025L142.1 296.425C142.33 296.555 142.58 296.635 142.82 296.725C142.93 296.765 143.04 296.835 143.16 296.865C143.53 296.965 143.9 297.015 144.28 297.015C144.66 297.015 145.03 296.965 145.4 296.865C145.5 296.835 145.59 296.775 145.69 296.745C145.95 296.655 146.21 296.565 146.45 296.435L251.36 236.035C252.72 235.255 253.55 233.815 253.55 232.245V174.885L303.81 145.945C305.17 145.165 306 143.725 306 142.155V82.265C305.95 81.875 305.89 81.495 305.8 81.125ZM144.2 227.205L100.57 202.515L146.39 176.135L196.66 147.195L240.33 172.335L208.29 190.625L144.2 227.205ZM244.75 114.995V164.795L226.39 154.225L201.03 139.625V89.825L219.39 100.395L244.75 114.995ZM249.12 57.105L292.81 82.265L249.12 107.425L205.43 82.265L249.12 57.105ZM114.49 184.425L96.13 194.995V85.305L121.49 70.705L139.85 60.135V169.815L114.49 184.425ZM91.76 27.425L135.45 52.585L91.76 77.745L48.07 52.585L91.76 27.425ZM43.67 60.135L62.03 70.705L87.39 85.305V202.545V202.555V202.565C87.39 202.735 87.44 202.895 87.46 203.055C87.49 203.265 87.49 203.485 87.55 203.695V203.705C87.6 203.875 87.69 204.035 87.76 204.195C87.84 204.375 87.89 204.575 87.99 204.745C87.99 204.745 87.99 204.755 88 204.755C88.09 204.905 88.22 205.035 88.33 205.175C88.45 205.335 88.55 205.495 88.69 205.635L88.7 205.645C88.82 205.765 88.98 205.855 89.12 205.965C89.28 206.085 89.42 206.225 89.59 206.325C89.6 206.325 89.6 206.325 89.61 206.335C89.62 206.335 89.62 206.345 89.63 206.345L139.87 234.775V285.065L43.67 229.705V60.135ZM244.75 229.705L148.58 285.075V234.775L219.8 194.115L244.75 179.875V229.705ZM297.2 139.625L253.49 164.795V114.995L278.85 100.395L297.21 89.825V139.625H297.2Z"/>
</svg>
EOF

# Create auth-session-status component
cat > "$COMPONENTS_DIR/auth-session-status.blade.php" << 'EOF'
@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600']) }}>
        {{ $status }}
    </div>
@endif
EOF

# Create danger-button component
cat > "$COMPONENTS_DIR/danger-button.blade.php" << 'EOF'
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
EOF

# Create secondary-button component
cat > "$COMPONENTS_DIR/secondary-button.blade.php" << 'EOF'
<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
EOF

# Create dropdown-link component
cat > "$COMPONENTS_DIR/dropdown-link.blade.php" << 'EOF'
<a {{ $attributes->merge(['class' => 'block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out']) }}>{{ $slot }}</a>
EOF

# Create dropdown component
cat > "$COMPONENTS_DIR/dropdown.blade.php" << 'EOF'
@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
$alignmentClasses = match ($align) {
    'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
    'top' => 'origin-top',
    default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};

$width = match ($width) {
    '48' => 'w-48',
    default => $width,
};
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute z-50 mt-2 {{ $width }} rounded-md shadow-lg {{ $alignmentClasses }}"
            style="display: none;"
            @click="open = false">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
EOF

# Create nav-link component
cat > "$COMPONENTS_DIR/nav-link.blade.php" << 'EOF'
@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
EOF

# Create responsive-nav-link component
cat > "$COMPONENTS_DIR/responsive-nav-link.blade.php" << 'EOF'
@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-400 text-start text-base font-medium text-indigo-700 bg-indigo-50 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
EOF

# Create modal component
cat > "$COMPONENTS_DIR/modal.blade.php" << 'EOF'
@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl'
])

@php
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth];
@endphp

<div
    x-data="{
        show: @js($show),
        focusables() {
            // All focusable element types...
            let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'
            return [...$el.querySelectorAll(selector)]
                // All non-disabled elements...
                .filter(el => ! el.hasAttribute('disabled'))
        },
        firstFocusable() { return this.focusables()[0] },
        lastFocusable() { return this.focusables().slice(-1)[0] },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) -1 },
    }"
    x-init="$watch('show', value => {
        if (value) {
            document.body.classList.add('overflow-y-hidden');
            {{ $attributes->has('focusable') ? 'setTimeout(() => firstFocusable().focus(), 100)' : '' }}
        } else {
            document.body.classList.remove('overflow-y-hidden');
        }
    })"
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="$event.detail == '{{ $name }}' ? show = false : null"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
    x-on:keydown.shift.tab.prevent="prevFocusable().focus()"
    x-show="show"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
    style="display: {{ $show ? 'block' : 'none' }};"
>
    <div
        x-show="show"
        class="fixed inset-0 transform transition-all"
        x-on:click="show = false"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    <div
        x-show="show"
        class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full {{ $maxWidth }} sm:mx-auto"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        {{ $slot }}
    </div>
</div>
EOF

# Create app-layout component
cat > "$COMPONENTS_DIR/app-layout.blade.php" << 'EOF'
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title', config('app.name', 'Speed and Style Hub') . ' - Fashion & Beauty Delivered')</title>
    <meta name="description" content="@yield('description', 'Speed and Style Hub - Your ultimate destination for stylish clothing and premium beauty products. Discover the latest fashion trends and self-care essentials.')">
    <meta name="keywords" content="@yield('keywords', 'fashion, clothing, beauty, skincare, makeup, style, Speed and Style Hub, online shopping')">
    <meta name="author" content="Speed and Style Hub">
    <meta name="robots" content="index, follow">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('og_title', config('app.name', 'Speed and Style Hub'))">
    <meta property="og:description" content="@yield('og_description', 'Shop the latest fashion and beauty trends at Speed and Style Hub')">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/logo.svg'))">
    <meta property="og:site_name" content="Speed and Style Hub">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', config('app.name', 'Speed and Style Hub'))">
    <meta name="twitter:description" content="@yield('twitter_description', 'Shop fashion-forward clothing and beauty must-haves at Speed and Style Hub')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/logo.svg'))">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ request()->url() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

    <!-- Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://fonts.bunny.net">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Structured Data -->
    @yield('structured_data')
</head>
<body class="bg-gray-50">
    @include('temporary-eccomerce.components.header')

    <main>
        @if(isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        {{ $slot }}
    </main>

    @include('temporary-eccomerce.components.footer')
</body>
</html>
EOF

# Create product-card component (in case it's needed)
cat > "$COMPONENTS_DIR/product-card.blade.php" << 'EOF'
@props(['product'])

<article class="bg-white border border-gray-200 overflow-hidden group" data-product-id="{{ $product->id }}" itemscope itemtype="https://schema.org/Product">
    <div class="relative">
        <!-- Product Image with Link - Square aspect ratio -->
        <a href="{{ route('products.show', $product->slug) }}" 
        class="block flex justify-center items-center w-full bg-gray-100">
            <img src="{{ $product->main_image_url }}"
             alt="{{ $product->name }}" 
             class="w-[95%] aspect-square object-contain"
             itemprop="image"
             loading="lazy"
             onerror="this.src='https://via.placeholder.com/300x200/cccccc/ffffff?text=No+Image'; console.log('Image failed to load:', this.src);"
             onload="console.log('Image loaded successfully:', this.src);">
        </a>
        
        <!-- Sale Badge -->
        @if($product->badge)
            <div class="absolute top-2 left-2">
                <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                    {{ $product->badge }}
                </span>
            </div>
        @endif
        
        <!-- Action Buttons Container - Hidden by default, animated on hover -->
        <div class="absolute top-2 right-2 flex flex-col space-y-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-out">
            <!-- Wishlist Button -->
            <button class="wishlist-btn bg-white rounded-full p-2 shadow-md hover:bg-red-50 transition-all duration-200 z-10 w-8 h-8 flex items-center justify-center" 
                    onclick="toggleWishlist('{{ $product->id }}', '{{ $product->name }}')"
                    data-product-id="{{ $product->id }}"
                    title="Add to Wishlist">
                <i class="fas fa-heart text-gray-400 hover:text-red-500 transition-colors text-sm wishlist-icon"></i>
            </button>
            
            <!-- Remove from Wishlist Button (hidden by default) -->
            <button class="remove-wishlist-btn bg-white rounded-full p-2 shadow-md hover:bg-red-50 transition-all duration-200 z-10 w-8 h-8 flex items-center justify-center hidden" 
                    onclick="removeFromWishlist('{{ $product->id }}')"
                    data-product-id="{{ $product->id }}"
                    title="Remove from Wishlist">
                <i class="fas fa-times text-red-500 text-sm"></i>
            </button>
            
            <!-- Add to Cart Button -->
            <button class="bg-white rounded-full p-2 shadow-md hover:bg-blue-50 transition-all duration-200 z-10 w-8 h-8 flex items-center justify-center"
                    onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, '{{ $product->main_image_url }}')"
                    title="Add to Cart">
                <i class="fas fa-shopping-cart text-gray-400 hover:text-blue-600 transition-colors text-sm"></i>
            </button>
        </div>
    </div>
    
    <!-- Content Section - Longer to make card rectangular -->
    <div class="p-3 sm:p-4 flex flex-col">
        <!-- Product Name with Link -->
        <a href="{{ route('products.show', $product->slug) }}" class="">
            <h3 class="text-xs sm:text-sm font-medium text-gray-900 hover:text-blue-600 transition-colors line-clamp-2" itemprop="name">{{ $product->name }}</h3>
        </a>
        
        <!-- Rating -->
        <div class="flex items-center" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
            @for($i = 1; $i <= 5; $i++)
                @if($i <= $product->rating)
                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                @else
                    <i class="far fa-star text-gray-300 text-xs"></i>
                @endif
            @endfor
            <span class="text-xs text-gray-500 ml-1 text-xs">({{ $product->reviews_count }})</span>
            <meta itemprop="ratingValue" content="{{ $product->rating }}">
            <meta itemprop="reviewCount" content="{{ $product->reviews_count }}">
            <meta itemprop="bestRating" content="5">
            <meta itemprop="worstRating" content="1">
        </div>
        
        <!-- Price -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-2">
                @if($product->old_price)
                    <span class="text-xs sm:text-sm text-gray-500 line-through order-2 sm:order-1">{{ $product->formatted_old_price }}</span>
                @endif
                <span class="text-base sm:text-lg font-bold text-gray-900 order-1 sm:order-2" itemprop="price" content="{{ $product->price }}">
                    <span itemprop="priceCurrency" content="KES">{{ $product->formatted_price }}</span>
                </span>
            </div>
        </div>
    </div>
    
    <!-- Hidden structured data -->
    <meta itemprop="url" content="{{ route('products.show', $product->slug) }}">
    <meta itemprop="availability" content="{{ $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}">
    @if($product->category)
        <meta itemprop="category" content="{{ $product->category->name }}">
    @endif
</article>
EOF

# Create banner component
cat > "$COMPONENTS_DIR/banner.blade.php" << 'EOF'
@props(['title', 'subtitle', 'buttonText', 'buttonLink', 'image' => null, 'backgroundColor' => 'bg-gray-800'])

<div class="{{ $backgroundColor }} text-white py-16">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <!-- Content -->
            <div class="text-center lg:text-left">
                <h2 class="text-4xl lg:text-5xl font-bold mb-4">{{ $title }}</h2>
                <p class="text-lg mb-8 text-gray-300">{{ $subtitle }}</p>
                <a href="{{ $buttonLink }}" class="inline-block bg-white text-gray-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    {{ $buttonText }}
                </a>
            </div>
            
            <!-- Image -->
            @if($image)
                <div class="flex justify-center lg:justify-end">
                    <img src="{{ $image }}" alt="Banner" class="max-w-md">
                </div>
            @endif
        </div>
    </div>
</div>
EOF

echo "✅ All component files have been created successfully!"
echo "📁 Components created in: $COMPONENTS_DIR"
echo ""
echo "Now run: php artisan view:clear && php artisan view:cache"
