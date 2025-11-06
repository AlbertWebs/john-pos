<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        use App\Services\SeoService;
        // Ensure settings are available
        if (!isset($settings)) {
            $settings = \Illuminate\Support\Facades\DB::table('settings')->pluck('value', 'key')->toArray();
        }
        $seoData = $seoData ?? [];
        $pageType = $pageType ?? 'homepage';
        $pageTitle = $pageTitle ?? 'Home';
        try {
            $seoSettings = SeoService::getSeoSettings($pageType, $seoData);
        } catch (\Exception $e) {
            // Fallback if SeoService fails
            $seoSettings = [
                'meta_title' => $pageTitle . ' - ' . (isset($settings['company_name']) ? $settings['company_name'] : config('app.name', 'Auto Spares')),
                'meta_description' => 'Top-selling auto spares shop in Wangige. Quality car parts, vehicle spares, and auto accessories.',
                'meta_keywords' => 'auto spares, car parts, vehicle spares, Wangige',
                'og_title' => null,
                'og_description' => null,
                'og_image' => null,
                'structured_data' => null,
                'custom_meta_tags' => null,
            ];
        }
    @endphp
    
    <!-- SEO Meta Tags -->
    <title>{{ $seoSettings['meta_title'] ?? ($pageTitle . ' - ' . (isset($settings) && isset($settings['company_name']) ? $settings['company_name'] : config('app.name', 'Auto Spares'))) }}</title>
    <meta name="description" content="{{ $seoSettings['meta_description'] ?? 'Top-selling auto spares shop in Wangige. Quality car parts, vehicle spares, and auto accessories.' }}">
    <meta name="keywords" content="{{ $seoSettings['meta_keywords'] ?? 'auto spares, car parts, vehicle spares, Wangige, auto parts shop, car accessories' }}">
    <meta name="author" content="{{ isset($settings) && isset($settings['company_name']) ? $settings['company_name'] : 'Johlly Auto Spares' }}">
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">
    <meta name="geo.region" content="KE-20">
    <meta name="geo.placename" content="Wangige">
    <meta name="geo.position" content="-1.2333;36.7833">
    <meta name="ICBM" content="-1.2333, 36.7833">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $seoSettings['og_title'] ?? $seoSettings['meta_title'] ?? 'Top Auto Spares Shop in Wangige' }}">
    <meta property="og:description" content="{{ $seoSettings['og_description'] ?? $seoSettings['meta_description'] ?? 'Top-selling auto spares shop in Wangige' }}">
    <meta property="og:image" content="{{ $seoSettings['og_image'] ?? asset('images/default-og-image.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ isset($settings) && isset($settings['company_name']) ? $settings['company_name'] : 'Johlly Auto Spares' }}">
    <meta property="og:locale" content="en_KE">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoSettings['og_title'] ?? $seoSettings['meta_title'] ?? 'Top Auto Spares Shop in Wangige' }}">
    <meta name="twitter:description" content="{{ $seoSettings['og_description'] ?? $seoSettings['meta_description'] ?? 'Top-selling auto spares shop in Wangige' }}">
    <meta name="twitter:image" content="{{ $seoSettings['og_image'] ?? asset('images/default-og-image.jpg') }}">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Sitemap -->
    <link rel="sitemap" type="application/xml" href="{{ url('/sitemap.xml') }}">
    
    <!-- Custom Meta Tags -->
    {!! $seoSettings['custom_meta_tags'] ?? '' !!}
    
    <!-- Structured Data (JSON-LD) -->
    @if(isset($structuredData))
        <script type="application/ld+json">
        {!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @elseif($seoSettings['structured_data'])
        <script type="application/ld+json">
        {!! is_array($seoSettings['structured_data']) ? json_encode($seoSettings['structured_data'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) : $seoSettings['structured_data'] !!}
        </script>
    @elseif($pageType === 'homepage')
        @php
            try {
                $localBusinessData = SeoService::generateLocalBusinessStructuredData($settings ?? []);
            } catch (\Exception $e) {
                $localBusinessData = null;
            }
        @endphp
        @if($localBusinessData)
        <script type="application/ld+json">
        {!! json_encode($localBusinessData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
        @endif
    @endif
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- E-commerce Styles -->
    <link rel="stylesheet" href="{{ asset('e-commerce-styles/assets/app-D7ajvNhS.css') }}">
    <!-- <script src="{{ asset('e-commerce-styles/assets/app-DXKQOVqA.js') }}" defer></script> -->
</head>
<body class="bg-gray-50">
    @php
        $settings = $settings ?? \Illuminate\Support\Facades\DB::table('settings')->pluck('value', 'key')->toArray();
    @endphp

    <!-- Top Header Bar -->
    <div class="bg-gray-100 py-2 hidden md:block">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center text-sm">
                <div class="text-gray-600">
                    Free shipping on orders over KES 50,000
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-4">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('ecommerce.index') }}" class="flex items-center gap-3">
                        @if(isset($settings['logo']) && $settings['logo'])
                        <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo" class="h-16">
                        @else
                        <div class="bg-blue-600 rounded-lg p-2">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M5 16L3 5l5.5 1L12 4l3.5 2L21 5l-2 11H5zm14.3-4.3c-.4 1.7-1.1 3.1-2.1 4.1s-2.4 1.5-4.1 1.5c-1.7 0-3.1-.5-4.1-1.5s-1.7-2.4-2.1-4.1c-.4-1.7-.4-3.5 0-5.3.4-1.7 1.1-3.1 2.1-4.1s2.4-1.5 4.1-1.5c1.7 0 3.1.5 4.1 1.5s1.7 2.4 2.1 4.1c.4 1.8.4 3.6 0 5.3zm-2.8-1.2c.3-1.3.3-2.7 0-4-.3-1.3-.8-2.3-1.5-3s-1.7-1.2-3-1.5c-1.3-.3-2.7-.3-4 0-1.3.3-2.3.8-3 1.5s-1.2 1.7-1.5 3c-.3 1.3-.3 2.7 0 4 .3 1.3.8 2.3 1.5 3s1.7 1.2 3 1.5c1.3.3 2.7.3 4 0 1.3-.3 2.3-.8 3-1.5s1.2-1.7 1.5-3z"/>
                            </svg>
                        </div>
                        @endif
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">{{ isset($settings) && isset($settings['company_name']) ? $settings['company_name'] : 'Johlly Auto Spares' }}</h1>
                        </div>
                    </a>
                </div>

                <!-- Center Search -->
                <div class="flex-1 max-w-2xl mx-8 hidden md:block">
                    <form action="{{ route('ecommerce.products') }}" method="GET" class="flex">
                        <div class="relative flex-1">
                            <input type="text" name="search" value="{{ request('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Search for products...">
                            <button type="submit" class="absolute right-2.5 top-2.5">
                                <i class="fas fa-search text-gray-400"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Side -->
                <div class="flex items-center space-x-6">
                    <!-- Desktop Elements -->
                    <div class="hidden md:flex items-center space-x-2">
                        <i class="fas fa-phone text-gray-600"></i>
                        <span class="text-sm text-gray-600">{{ isset($settings['phone']) ? $settings['phone'] : '+254 700 123 456' }}</span>
                    </div>
                    <!-- Basket with Dropdown -->
                    <div class="relative group hidden md:block">
                        <a href="{{ route('ecommerce.cart') }}" class="flex items-center space-x-3 relative">
                            <div class="relative">
                                <i class="fas fa-shopping-basket text-xl text-gray-600"></i>
                                <span class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center cart-count">{{ session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0 }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-500">Total</span>
                                <span class="text-sm font-medium text-gray-900 cart-total">KES {{ number_format(session('cart') ? array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, session('cart'))) : 0, 2) }}</span>
                            </div>
                        </a>

                        <!-- Basket Dropdown -->
                        <div class="absolute right-0 top-full mt-2 w-96 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Shopping Basket</h3>
                            </div>
                            <div class="cart-dropdown max-h-80 overflow-y-auto">
                                @if(session('cart') && count(session('cart')) > 0)
                                    @foreach(session('cart') as $item)
                                    <div class="p-4 border-b border-gray-200 flex items-center space-x-3">
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $item['name'] }}</h4>
                                            <p class="text-sm text-gray-500">KES {{ number_format($item['price'], 2) }} x {{ $item['quantity'] }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="p-4 text-center text-gray-500">Your basket is empty</div>
                                @endif
                            </div>
                            <div class="p-4 border-t border-gray-200">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-sm font-medium text-gray-700">Total:</span>
                                    <span class="text-lg font-bold text-gray-900 cart-dropdown-total">KES {{ number_format(session('cart') ? array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, session('cart'))) : 0, 2) }}</span>
                                </div>
                                <a href="{{ route('ecommerce.cart') }}" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg text-center block hover:bg-blue-700 transition-colors">
                                    View Basket
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-button" class="md:hidden flex items-center space-x-2 text-gray-600 hover:text-gray-800">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <!-- Mobile Search Button -->
                    <button id="mobile-search-button" class="md:hidden flex items-center space-x-2 text-gray-600 hover:text-gray-800">
                        <i class="fas fa-search text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="bg-blue-900 hidden md:block w-full">
            <div class="w-full px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-center space-x-8 py-3">
                    @php
                        $categories = \App\Models\Category::orderBy('name')->take(12)->get();
                    @endphp
                    @foreach ($categories as $category)
                        <a href="{{ route('ecommerce.products', ['category' => $category->id]) }}" class="text-white hover:text-gray-300 text-sm font-medium">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer Top Bar -->
    <div class="bg-gray-800 py-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-shipping-fast text-white text-xl"></i>
                    <div>
                        <h4 class="text-white font-semibold">FREE DELIVERY</h4>
                        <p class="text-gray-300 text-sm">On orders over KES 50,000</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <i class="fas fa-credit-card text-white text-xl"></i>
                    <div>
                        <h4 class="text-white font-semibold">SECURE CHECKOUT</h4>
                        <p class="text-gray-300 text-sm">Up to 6 months installments</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <i class="fas fa-sync-alt text-white text-xl"></i>
                    <div>
                        <h4 class="text-white font-semibold">EASY RETURNS</h4>
                        <p class="text-gray-300 text-sm">15-day return window</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <i class="fas fa-headset text-white text-xl"></i>
                    <div>
                        <h4 class="text-white font-semibold">CUSTOMER CARE</h4>
                        <p class="text-gray-300 text-sm">We're here 24/7</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Top Selling Products -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">TOP SELLING PRODUCTS</h4>
                    <ul class="space-y-2">
                        @php
                            $topSellingProducts = \App\Models\SaleItem::select('part_id', \Illuminate\Support\Facades\DB::raw('SUM(quantity) as total_quantity'))
                                ->groupBy('part_id')
                                ->orderBy('total_quantity', 'desc')
                                ->limit(8)
                                ->get()
                                ->map(function($item) {
                                    return \App\Models\Inventory::where('id', $item->part_id)
                                        ->where('status', 'active')
                                        ->first();
                                })
                                ->filter()
                                ->take(8);
                        @endphp
                        @foreach($topSellingProducts as $product)
                            <li>
                                <a href="{{ route('ecommerce.product', $product->id) }}" class="text-gray-400 hover:text-white">
                                    {{ $product->name }}
                                </a>
                            </li>
                        @endforeach
                        @if($topSellingProducts->count() == 0)
                            @php
                                $fallbackProducts = \App\Models\Inventory::where('status', 'active')
                                    ->where('stock_quantity', '>', 0)
                                    ->orderBy('stock_quantity', 'desc')
                                    ->take(8)
                                    ->get();
                            @endphp
                            @foreach($fallbackProducts as $product)
                                <li>
                                    <a href="{{ route('ecommerce.product', $product->id) }}" class="text-gray-400 hover:text-white">
                                        {{ $product->name }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <!-- Car Models -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">CAR MODELS</h4>
                    <ul class="space-y-2">
                        @php
                            $footerVehicleModels = \App\Models\VehicleModel::with('vehicleMake')
                                ->orderBy('model_name')
                                ->take(8)
                                ->get();
                        @endphp
                        @foreach($footerVehicleModels as $vehicleModel)
                            <li>
                                <a href="{{ route('ecommerce.products', ['model' => $vehicleModel->id]) }}" class="text-gray-400 hover:text-white">
                                    @if($vehicleModel->vehicleMake)
                                        {{ $vehicleModel->vehicleMake->make_name }} {{ $vehicleModel->model_name }}
                                    @else
                                        {{ $vehicleModel->model_name }}
                                    @endif
                                </a>
                            </li>
                        @endforeach
                        @if(\App\Models\VehicleModel::count() > 8)
                            <li><a href="{{ route('ecommerce.products') }}" class="text-gray-400 hover:text-white">View All Models →</a></li>
                        @endif
                    </ul>
                </div>

                <!-- Categories -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">CATEGORIES</h4>
                    <ul class="space-y-2">
                        @php
                            $footerCategories = \App\Models\Category::orderBy('name')->take(8)->get();
                        @endphp
                        @foreach($footerCategories as $category)
                            <li><a href="{{ route('ecommerce.products', ['category' => $category->id]) }}" class="text-gray-400 hover:text-white">{{ $category->name }}</a></li>
                        @endforeach
                        @if(\App\Models\Category::count() > 8)
                            <li><a href="{{ route('ecommerce.products') }}" class="text-gray-400 hover:text-white">View All Categories →</a></li>
                        @endif
                    </ul>
                </div>

                <!-- Brands -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">BRANDS</h4>
                    <ul class="space-y-2">
                        @php
                            $footerBrands = \App\Models\Brand::orderBy('brand_name')->take(8)->get();
                        @endphp
                        @foreach($footerBrands as $brand)
                            <li>
                                <a href="{{ route('ecommerce.products', ['brand' => $brand->id]) }}" class="text-gray-400 hover:text-white">
                                    {{ $brand->brand_name }}
                                </a>
                            </li>
                        @endforeach
                        @if(\App\Models\Brand::count() > 8)
                            <li><a href="{{ route('ecommerce.products') }}" class="text-gray-400 hover:text-white">View All Brands →</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="bg-gray-800 py-4">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-center text-gray-400 text-sm">
                    © {{ date('Y') }} {{ isset($settings) && isset($settings['company_name']) ? $settings['company_name'] : 'Johlly Auto Spares' }}. Design & Develop By Designekta Studios.
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Global cart functions
        window.addToCart = function(productId, quantity = 1, buttonElement = null) {
            console.log('addToCart called with productId:', productId, 'quantity:', quantity);
            
            // Find button if not provided
            if (!buttonElement) {
                // Try to find button by product ID
                const productCard = document.querySelector(`[data-product-id="${productId}"]`);
                if (productCard) {
                    buttonElement = productCard.querySelector('button[onclick*="addToCart"]');
                }
            }
            
            // Show loading state
            let originalButtonHTML = null;
            let originalButtonDisabled = null;
            if (buttonElement) {
                originalButtonHTML = buttonElement.innerHTML;
                originalButtonDisabled = buttonElement.disabled;
                buttonElement.disabled = true;
                buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin text-blue-600"></i>';
                buttonElement.style.cursor = 'not-allowed';
                buttonElement.style.opacity = '0.6';
            }
            
            // Check if CSRF token exists
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found');
                // Restore button state
                if (buttonElement && originalButtonHTML) {
                    buttonElement.innerHTML = originalButtonHTML;
                    buttonElement.disabled = originalButtonDisabled;
                    buttonElement.style.cursor = '';
                    buttonElement.style.opacity = '';
                }
                alert('Error: CSRF token not found. Please refresh the page.');
                return;
            }
            
            const baseUrl = window.location.origin;
            fetch(baseUrl + '/shop/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Add to cart response:', data);
                
                // Restore button state
                if (buttonElement && originalButtonHTML) {
                    buttonElement.innerHTML = originalButtonHTML;
                    buttonElement.disabled = originalButtonDisabled;
                    buttonElement.style.cursor = '';
                    buttonElement.style.opacity = '';
                }
                
                if (data.success) {
                    // Update cart count
                    if (typeof updateCartCount === 'function') {
                        updateCartCount(data.cart_count);
                    }
                    // Update cart total
                    if (typeof updateCartTotal === 'function') {
                        updateCartTotal(data.cart_total || 0);
                    }
                    // Update cart dropdown
                    if (typeof updateCartDropdown === 'function') {
                        updateCartDropdown();
                    }
                    // Show success message
                    if (typeof showNotification === 'function') {
                        showNotification('Product added to cart!', 'success');
                    } else {
                        alert('Product added to cart!');
                    }
                } else {
                    if (typeof showNotification === 'function') {
                        showNotification(data.message || 'Failed to add product to cart', 'error');
                    } else {
                        alert(data.message || 'Failed to add product to cart');
                    }
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                
                // Restore button state
                if (buttonElement && originalButtonHTML) {
                    buttonElement.innerHTML = originalButtonHTML;
                    buttonElement.disabled = originalButtonDisabled;
                    buttonElement.style.cursor = '';
                    buttonElement.style.opacity = '';
                }
                
                if (typeof showNotification === 'function') {
                    showNotification('An error occurred. Please try again.', 'error');
                } else {
                    alert('An error occurred. Please try again.');
                }
            });
        };

        window.updateCart = function(productId, quantity) {
            if (quantity < 1) {
                window.removeFromCart(productId);
                return;
            }

            fetch('/shop/cart/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: parseInt(quantity)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count
                    updateCartCount(data.cart_count);
                    // Update cart total
                    updateCartTotal(data.cart_total || 0);
                    // Update cart dropdown
                    updateCartDropdown();
                    // If on cart page, update the item
                    if (document.getElementById('cart-item-' + productId)) {
                        updateCartItem(productId, data.item);
                    }
                    showNotification('Cart updated!', 'success');
                } else {
                    showNotification(data.message || 'Failed to update cart', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred. Please try again.', 'error');
            });
        };

        window.removeFromCart = function(productId) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                return;
            }

            fetch('/shop/cart/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count
                    updateCartCount(data.cart_count);
                    // Update cart total
                    updateCartTotal(data.cart_total || 0);
                    // Update cart dropdown
                    updateCartDropdown();
                    // If on cart page, remove the item from DOM
                    const cartItem = document.getElementById('cart-item-' + productId);
                    if (cartItem) {
                        cartItem.remove();
                        // Check if cart is empty
                        if (document.querySelectorAll('[id^="cart-item-"]').length === 0) {
                            location.reload();
                        }
                    }
                    showNotification('Item removed from cart', 'success');
                } else {
                    showNotification(data.message || 'Failed to remove item', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred. Please try again.', 'error');
            });
        };

        // Update cart count in header
        function updateCartCount(count) {
            const cartCountElements = document.querySelectorAll('.cart-count');
            cartCountElements.forEach(el => {
                el.textContent = count;
                if (count > 0) {
                    el.style.display = 'flex';
                } else {
                    el.style.display = 'none';
                }
            });
        }

        // Update cart total in header
        function updateCartTotal(total) {
            const cartTotalElements = document.querySelectorAll('.cart-total');
            cartTotalElements.forEach(el => {
                el.textContent = 'KES ' + parseFloat(total).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            });
        }

        // Update cart dropdown
        function updateCartDropdown() {
            fetch('/shop/cart', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.items) {
                    const dropdown = document.querySelector('.cart-dropdown');
                    if (dropdown) {
                        if (data.items.length > 0) {
                            let html = '';
                            data.items.forEach(item => {
                                html += `
                                    <div class="p-4 border-b border-gray-200 flex items-center space-x-3">
                                        <img src="${item.image ? '{{ asset("storage/") }}/' + item.image : '{{ asset("images/placeholder.png") }}'}" alt="${item.name}" class="w-16 h-16 object-cover rounded">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">${item.name}</h4>
                                            <p class="text-sm text-gray-500">KES ${parseFloat(item.price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})} x ${item.quantity}</p>
                                        </div>
                                    </div>
                                `;
                            });
                            dropdown.innerHTML = html;
                        } else {
                            dropdown.innerHTML = '<div class="p-4 text-center text-gray-500">Your basket is empty</div>';
                        }
                    }
                    // Update dropdown total
                    const dropdownTotal = document.querySelector('.cart-dropdown-total');
                    if (dropdownTotal && data.total !== undefined) {
                        dropdownTotal.textContent = 'KES ' + parseFloat(data.total).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    }
                }
            })
            .catch(error => {
                console.error('Error updating cart dropdown:', error);
            });
        }

        // Update cart item on cart page
        function updateCartItem(productId, item) {
            if (!item) return;
            const cartItem = document.getElementById('cart-item-' + productId);
            if (cartItem) {
                // Update subtotal
                const subtotalEl = cartItem.querySelector('[data-subtotal]');
                if (subtotalEl) {
                    subtotalEl.textContent = 'KES ' + parseFloat(item.subtotal).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                }
            }
            // Update order summary
            updateOrderSummary();
        }

        // Update order summary on cart page
        function updateOrderSummary() {
            fetch('/shop/cart', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.total !== undefined) {
                    const totalEl = document.querySelector('[data-cart-total]');
                    if (totalEl) {
                        totalEl.textContent = 'KES ' + parseFloat(data.total).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    }
                }
            })
            .catch(error => {
                console.error('Error updating order summary:', error);
            });
        }

        // Show notification
        function showNotification(message, type = 'success') {
            // Remove existing notifications
            const existing = document.querySelector('.cart-notification');
            if (existing) {
                existing.remove();
            }

            // Create notification
            const notification = document.createElement('div');
            notification.className = 'cart-notification fixed top-20 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transition-all duration-300';
            notification.style.backgroundColor = type === 'success' ? '#10b981' : '#ef4444';
            notification.style.color = 'white';
            notification.textContent = message;
            document.body.appendChild(notification);

            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html>
