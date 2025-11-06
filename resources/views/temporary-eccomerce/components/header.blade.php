@php
use App\Models\Setting;
@endphp

<!-- Top Header Bar -->
<div class="bg-gray-100 py-2 hidden md:block">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center text-sm">
            <div class="text-gray-600">
                Free shipping on orders over KES 50,000
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('wishlist.index') }}" class="text-gray-600 hover:text-gray-800">Wishlist</a>
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
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.svg') }}" alt="Speed and Style Hub" class="h-16">
                </a>
            </div>

            <!-- Center Search -->
            <div class="flex-1 max-w-2xl mx-8 hidden md:block">
                <form action="{{ route('products.index') }}" method="GET" class="flex">
                    <div class="relative flex-1">
                        <select name="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">All Categories</option>
                            @foreach(\App\Models\Category::active()->get() as $category)
                                <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="relative flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Search for products...">
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
                    <span class="text-sm text-gray-600">{{ Setting::get('contact_phone', '+254 700 123 456') }}</span>
                </div>
                <a href="{{ route('wishlist.index') }}" class="hidden md:block text-gray-600 hover:text-red-600 relative">
                    <i class="fas fa-heart text-xl"></i>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center wishlist-count">0</span>
                </a>

                <!-- Basket with Dropdown -->
                <div class="relative group hidden md:block">
                    <a href="{{ route('cart.index') }}" class="flex items-center space-x-3 relative">
                        <div class="relative">
                            <i class="fas fa-shopping-basket text-xl text-gray-600"></i>
                            <span class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center cart-count">0</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500">Total</span>
                            <span class="text-sm font-medium text-gray-900 cart-total">KES 0</span>
                        </div>
                    </a>

                    <!-- Basket Dropdown -->
                    <div class="absolute right-0 top-full mt-2 w-96 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Shopping Basket</h3>
                        </div>
                        <div class="cart-dropdown max-h-80 overflow-y-auto">
                            <div class="p-4 text-center text-gray-500">Your basket is empty</div>
                        </div>
                        <div class="p-4 border-t border-gray-200">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm font-medium text-gray-700">Total:</span>
                                <span class="text-lg font-bold text-gray-900 cart-dropdown-total">KES 0.00</span>
                            </div>
                            <a href="{{ route('cart.index') }}" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg text-center block hover:bg-blue-700 transition-colors">
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
    <nav class="bg-blue-900 hidden md:block">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-8 py-3">
                <a href="{{ route('home') }}" class="text-white hover:text-gray-300 text-sm font-medium {{ request()->routeIs('home') ? 'text-gray-300' : '' }}">Home</a>
                <a href="{{ route('products.index') }}" class="text-white hover:text-gray-300 text-sm font-medium {{ request()->routeIs('products.*') ? 'text-gray-300' : '' }}">Products</a>
                <?php
                  $Category = \App\Models\Category::take(6)->inRandomOrder()->get();
                ?>
                @foreach ($Category as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                    class="text-white hover:text-gray-300 text-sm font-medium {{ request('category') == $category->slug ? 'text-gray-300' : '' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
                <a href="{{ route('pages.about') }}" class="text-white hover:text-gray-300 text-sm font-medium {{ request()->routeIs('pages.about') ? 'text-gray-300' : '' }}">About</a>
                <a href="{{ route('pages.contact') }}" class="text-white hover:text-gray-300 text-sm font-medium {{ request()->routeIs('pages.contact') ? 'text-gray-300' : '' }}">Contact</a>
                <a href="{{ route('pages.faq') }}" class="text-white hover:text-gray-300 text-sm font-medium {{ request()->routeIs('pages.faq') ? 'text-gray-300' : '' }}">FAQ</a>
            </div>
        </div>
    </nav>
</header>

<!-- Mobile Search Form -->
<div id="mobile-search" class="fixed inset-0 z-40 hidden bg-black bg-opacity-50">
    <div class="absolute top-0 left-0 right-0 bg-white p-4 shadow-lg">
        <div class="flex items-center space-x-3">
            <button id="mobile-search-close" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
            <form action="{{ route('products.index') }}" method="GET" class="flex-1 flex">
                <input type="text" name="search" value="{{ request('search') }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Search for products...">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Mobile Drawer Menu -->
<div id="mobile-drawer" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div id="mobile-drawer-backdrop" class="absolute inset-0 bg-black bg-opacity-50"></div>

    <!-- Drawer Content -->
    <div class="absolute right-0 top-0 h-full w-80 bg-white shadow-xl transform translate-x-full transition-transform duration-300">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Menu</h2>
            <button id="mobile-drawer-close" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Navigation -->
        <div class="p-4 h-full overflow-y-auto">
            <div class="space-y-4">
                <!-- Main Navigation -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Navigation</h3>
                    <div class="space-y-2">
                        <a href="{{ route('home') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
                            <i class="fas fa-home text-lg"></i>
                            <span>Home</span>
                        </a>
                        <a href="{{ route('products.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 {{ request()->routeIs('products.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
                            <i class="fas fa-shopping-bag text-lg"></i>
                            <span>Products</span>
                        </a>
                        <a href="{{ route('pages.about') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700">
                            <i class="fas fa-info-circle text-lg"></i>
                            <span>About</span>
                        </a>
                        <a href="{{ route('pages.contact') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700">
                            <i class="fas fa-envelope text-lg"></i>
                            <span>Contact</span>
                        </a>
                        <a href="{{ route('pages.faq') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700">
                            <i class="fas fa-question-circle text-lg"></i>
                            <span>FAQ</span>
                        </a>
                    </div>
                </div>

                <!-- Categories -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Categories</h3>
                    <div class="space-y-2">
                        @foreach(\App\Models\Category::active()->take(6)->get() as $category)
                            <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700">
                                <i class="{{ $category->icon }} text-lg"></i>
                                <span>{{ $category->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Account & Cart -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Account</h3>
                    <div class="space-y-2">
                        @auth
                            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700">
                                <i class="fas fa-user text-lg"></i>
                                <span>My Account</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700">
                                <i class="fas fa-sign-in-alt text-lg"></i>
                                <span>Login</span>
                            </a>
                        @endauth
                        <a href="{{ route('cart.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700">
                            <i class="fas fa-shopping-cart text-lg"></i>
                            <span>Cart</span>
                            <span class="ml-auto bg-blue-500 text-white text-xs rounded-full px-2 py-1 cart-count">0</span>
                        </a>
                        <a href="{{ route('wishlist.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700">
                            <i class="fas fa-heart text-lg"></i>
                            <span>Wishlist</span>
                            <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1 wishlist-count">0</span>
                        </a>
                    </div>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Contact</h3>
                    <div class="space-y-2">
                        <div class="flex items-center space-x-3 p-3 text-gray-700">
                            <i class="fas fa-phone text-lg"></i>
                            <span>{{ Setting::get('contact_phone', '+254 700 123 456') }}</span>
                        </div>
                        <div class="flex items-center space-x-3 p-3 text-gray-700">
                            <i class="fas fa-envelope text-lg"></i>
                            <span>{{ Setting::get('contact_email', 'hello@gurudigital.co.ke') }}</span>
                        </div>
                        <div class="flex items-center space-x-3 p-3 text-gray-700">
                            <i class="fas fa-map-marker-alt text-lg"></i>
                            <span>{{ Setting::get('contact_address', 'Westlands, Nairobi') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
