<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Spare Parts POS') }} - @yield('title', 'Dashboard')</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 antialiased" x-data="appLayout()">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        @auth
        <aside 
            :class="sidebarCollapsed ? 'w-20' : 'w-64'" 
            class="bg-blue-900 shadow-2xl fixed h-screen transition-all duration-300 z-50 flex flex-col overflow-hidden"
            x-data="sidebar()"
        >
            <!-- Logo & Toggle -->
            <div class="p-4 border-b border-blue-500/30">
                <div class="flex items-center justify-between">
                    <div x-show="!sidebarCollapsed" class="flex items-center space-x-2">
                        <div class="bg-white rounded-lg p-2">
                            <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M5 16L3 5l5.5 1L12 4l3.5 2L21 5l-2 11H5zm14.3-4.3c-.4 1.7-1.1 3.1-2.1 4.1s-2.4 1.5-4.1 1.5c-1.7 0-3.1-.5-4.1-1.5s-1.7-2.4-2.1-4.1c-.4-1.7-.4-3.5 0-5.3.4-1.7 1.1-3.1 2.1-4.1s2.4-1.5 4.1-1.5c1.7 0 3.1.5 4.1 1.5s1.7 2.4 2.1 4.1c.4 1.8.4 3.6 0 5.3zm-2.8-1.2c.3-1.3.3-2.7 0-4-.3-1.3-.8-2.3-1.5-3s-1.7-1.2-3-1.5c-1.3-.3-2.7-.3-4 0-1.3.3-2.3.8-3 1.5s-1.2 1.7-1.5 3c-.3 1.3-.3 2.7 0 4 .3 1.3.8 2.3 1.5 3s1.7 1.2 3 1.5c1.3.3 2.7.3 4 0 1.3-.3 2.3-.8 3-1.5s1.2-1.7 1.5-3z"/>
                                <circle cx="12" cy="9" r="1.5" fill="currentColor"/>
                                <circle cx="12" cy="13" r="1.5" fill="currentColor"/>
                            </svg>
                        </div>
                        <h1 class="text-lg font-bold text-white">Johlly-POS </h1>
                    </div>
                    <button 
                        @click="sidebarCollapsed = !sidebarCollapsed"
                        class="text-white hover:bg-white/10 rounded-lg p-2 transition"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto overflow-x-hidden py-4 px-2" style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.2) transparent; max-height: calc(100vh - 80px);">
                <!-- Dashboard - Hidden for cashiers -->
                @unless(Auth::user()->isCashier())
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10' }} px-4 py-3 rounded-xl mb-2 transition-all group"
                   title="Dashboard"
                >
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="font-medium">Dashboard</span>
                </a>
                @endunless

                <!-- Management Section -->
                @can('manage inventory')
                <div x-data="{ open: {{ request()->routeIs(['categories.*', 'brands.*', 'vehicle-makes.*', 'vehicle-models.*', 'customers.*']) ? 'true' : 'false' }} }">
                    <button 
                        @click="open = !open"
                        class="flex items-center justify-between w-full text-blue-100 hover:bg-white/10 px-4 py-3 rounded-xl mb-2 transition-all group"
                        :title="sidebarCollapsed ? 'Management' : ''"
                    >
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                            </svg>
                            <span x-show="!sidebarCollapsed" class="font-medium">Management</span>
                        </div>
                        <svg x-show="!sidebarCollapsed" class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div x-show="open || sidebarCollapsed" class="ml-4 space-y-1 border-l-2 border-blue-400/30 pl-3">
                        <a href="{{ route('inventory.index') }}" 
                           class="flex items-center {{ request()->routeIs('inventory.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }} px-3 py-2 rounded-lg text-sm transition group"
                           title="Inventory"
                        >
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span x-show="!sidebarCollapsed">Inventory</span>
                        </a>
                        <a href="{{ route('categories.index') }}" 
                           class="flex items-center {{ request()->routeIs('categories.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }} px-3 py-2 rounded-lg text-sm transition"
                           title="Categories"
                        >
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span x-show="!sidebarCollapsed">Categories</span>
                        </a>
                        <a href="{{ route('brands.index') }}" 
                           class="flex items-center {{ request()->routeIs('brands.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }} px-3 py-2 rounded-lg text-sm transition"
                           title="Brands"
                        >
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <span x-show="!sidebarCollapsed">Brands</span>
                        </a>
                        <a href="{{ route('vehicle-makes.index') }}" 
                           class="flex items-center {{ request()->routeIs('vehicle-makes.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }} px-3 py-2 rounded-lg text-sm transition"
                           title="Vehicle Makes"
                        >
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span x-show="!sidebarCollapsed">Vehicle Makes</span>
                        </a>
                        <a href="{{ route('vehicle-models.index') }}" 
                           class="flex items-center {{ request()->routeIs('vehicle-models.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }} px-3 py-2 rounded-lg text-sm transition"
                           title="Vehicle Models"
                        >
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span x-show="!sidebarCollapsed">Vehicle Models</span>
                        </a>
                        <a href="{{ route('customers.index') }}" 
                           class="flex items-center {{ request()->routeIs('customers.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }} px-3 py-2 rounded-lg text-sm transition"
                           title="Customers"
                        >
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span x-show="!sidebarCollapsed">Customers</span>
                        </a>
                    </div>
                </div>
                @endcan

                <!-- Sales Section -->
                <div class="mt-4 pt-4 border-t border-blue-400/30">
                    <a href="{{ route('pos.index') }}" 
                       class="flex items-center {{ request()->routeIs('pos.*') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10' }} px-4 py-3 rounded-xl mb-2 transition-all group"
                       title="POS"
                    >
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span x-show="!sidebarCollapsed" class="font-medium">POS</span>
                    </a>
                    
                    @can('view sales')
                    <div x-data="{ open: {{ request()->routeIs(['sales.*', 'returns.*', 'loyalty-points.*']) ? 'true' : 'false' }} }">
                        <button 
                            @click="open = !open"
                            class="flex items-center justify-between w-full text-blue-100 hover:bg-white/10 px-4 py-3 rounded-xl mb-2 transition-all group"
                            :title="sidebarCollapsed ? 'Sales' : ''"
                        >
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed" class="font-medium">Sales</span>
                            </div>
                            <svg x-show="!sidebarCollapsed" class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open || sidebarCollapsed" class="ml-4 space-y-1 border-l-2 border-blue-400/30 pl-3">
                            <a href="{{ route('sales.index') }}" 
                               class="flex items-center {{ request()->routeIs('sales.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }} px-3 py-2 rounded-lg text-sm transition"
                               title="Sales History"
                            >
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed">Sales History</span>
                            </a>
                            <a href="{{ route('returns.index') }}" 
                               class="flex items-center {{ request()->routeIs('returns.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }} px-3 py-2 rounded-lg text-sm transition"
                               title="Returns"
                            >
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed">Returns</span>
                            </a>
                            <a href="{{ route('loyalty-points.index') }}" 
                               class="flex items-center {{ request()->routeIs('loyalty-points.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }} px-3 py-2 rounded-lg text-sm transition"
                               title="Loyalty Points"
                            >
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed">Loyalty Points</span>
                            </a>
                            <a href="{{ route('pending-payments.index') }}" 
                               class="flex items-center {{ request()->routeIs('pending-payments.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }} px-3 py-2 rounded-lg text-sm transition"
                               title="Pending Payments"
                            >
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed">Pending Payments</span>
                            </a>
                        </div>
                    </div>
                    @endcan
                </div>

                <!-- Website Section -->
                @unless(Auth::user()->isCashier())
                <div class="mt-4 pt-4 border-t border-blue-400/30">
                    <div x-data="{ open: {{ request()->routeIs('website.*') ? 'true' : 'false' }} }">
                        <button 
                            @click="open = !open"
                            class="flex items-center justify-between w-full text-blue-100 hover:bg-white/10 px-4 py-3 rounded-xl mb-2 transition-all group"
                            :title="sidebarCollapsed ? 'Website' : ''"
                        >
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed" class="font-medium">Website</span>
                            </div>
                            <svg x-show="!sidebarCollapsed" class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open || sidebarCollapsed" class="ml-4 space-y-1 border-l-2 border-blue-400/30 pl-3">
                            <a href="{{ route('website.products.index') }}" 
                               class="flex items-center {{ request()->routeIs('website.products.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }} px-3 py-2 rounded-lg text-sm transition"
                               title="Products"
                            >
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed">Products</span>
                            </a>
                            <a href="{{ route('website.categories.index') }}" 
                               class="flex items-center {{ request()->routeIs('website.categories.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }} px-3 py-2 rounded-lg text-sm transition"
                               title="Categories"
                            >
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed">Categories</span>
                            </a>
                            <a href="{{ route('website.brands.index') }}" 
                               class="flex items-center {{ request()->routeIs('website.brands.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }} px-3 py-2 rounded-lg text-sm transition"
                               title="Brands"
                            >
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed">Brands</span>
                            </a>
                            <a href="{{ route('website.vehicle-models.index') }}" 
                               class="flex items-center {{ request()->routeIs('website.vehicle-models.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }} px-3 py-2 rounded-lg text-sm transition"
                               title="Vehicle Models"
                            >
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed">Models</span>
                            </a>
                            <a href="{{ route('admin.seo-settings.index') }}" 
                               class="flex items-center {{ request()->routeIs('admin.seo-settings.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }} px-3 py-2 rounded-lg text-sm transition"
                               title="SEO Settings"
                            >
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed">SEO Settings</span>
                            </a>
                            <a href="{{ route('admin.users.index') }}" 
                               class="flex items-center {{ request()->routeIs('admin.users.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }} px-3 py-2 rounded-lg text-sm transition"
                               title="Users Management"
                            >
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed">Users Management</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endunless

                <!-- Other Sections -->
                <div class="mt-4 pt-4 border-t border-blue-400/30">
                    <a href="{{ route('work-orders.index') }}" 
                       class="flex items-center {{ request()->routeIs('work-orders.*') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10' }} px-4 py-3 rounded-xl mb-2 transition-all group"
                       title="Work Orders"
                    >
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        <span x-show="!sidebarCollapsed" class="font-medium">Work Orders</span>
                    </a>
                    
                    @can('view reports')
                    <div x-data="{ open: {{ request()->routeIs('reports.*') ? 'true' : 'false' }} }">
                        <button 
                            @click="open = !open"
                            class="flex items-center justify-between w-full text-blue-100 hover:bg-white/10 px-4 py-3 rounded-xl mb-2 transition-all group"
                            :title="sidebarCollapsed ? 'Reports' : ''"
                        >
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed" class="font-medium">Reports</span>
                            </div>
                            <svg x-show="!sidebarCollapsed" class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open || sidebarCollapsed" class="ml-4 space-y-1 border-l-2 border-blue-400/30 pl-3">
                            <a href="{{ route('reports.index') }}" 
                               class="flex items-center {{ request()->routeIs('reports.index') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }} px-3 py-2 rounded-lg text-sm transition"
                               title="Reports Dashboard"
                            >
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed">All Reports</span>
                            </a>
                        </div>
                    </div>
                    @endcan

                    <!-- Admin Pages - Only for super_admin -->
                    @unless(Auth::user()->isCashier())
                    <div class="mt-4 pt-4 border-t border-blue-400/30">
                        <a href="{{ route('admin.sales-reports.index') }}" 
                           class="flex items-center {{ request()->routeIs('admin.sales-reports.*') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10' }} px-4 py-3 rounded-xl mb-2 transition-all group"
                           title="Sales Reports"
                        >
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span x-show="!sidebarCollapsed" class="font-medium">Sales Reports</span>
                        </a>
                        <a href="{{ route('admin.stock-status.index') }}" 
                           class="flex items-center {{ request()->routeIs('admin.stock-status.*') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10' }} px-4 py-3 rounded-xl mb-2 transition-all group"
                           title="Stock Status"
                        >
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span x-show="!sidebarCollapsed" class="font-medium">Stock Status</span>
                        </a>
                    </div>
                    @endunless

                    <!-- Settings - Hidden for cashiers -->
                    @unless(Auth::user()->isCashier())
                    <a href="{{ route('settings.index') }}" 
                       class="flex items-center {{ request()->routeIs('settings.*') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10' }} px-4 py-3 rounded-xl mb-2 transition-all group"
                       title="Settings"
                    >
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span x-show="!sidebarCollapsed" class="font-medium">Settings</span>
                    </a>
                    @endunless
                </div>
            </nav>

            <!-- Logout -->
            <div class="p-4 border-t border-blue-500/30 space-y-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center text-blue-100 hover:bg-red-500/20 hover:text-white px-4 py-3 rounded-xl transition">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span x-show="!sidebarCollapsed" class="font-medium">{{ Auth::user()->name }}</span>
                    </button>
                </form>
                
                <!-- Temporary C2B Simulator Button -->
                <!-- <button 
                    type="button"
                    @click="simulateC2B()"
                    :disabled="simulatingC2B"
                    class="w-full flex items-center justify-center text-yellow-100 hover:bg-yellow-500/20 hover:text-white px-4 py-2 rounded-xl transition text-sm"
                    :class="simulatingC2B ? 'opacity-50 cursor-not-allowed' : ''"
                    x-show="!sidebarCollapsed"
                >
                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="font-medium" x-text="simulatingC2B ? 'Simulating...' : 'Simulate C2B'"></span>
                </button> -->
            </div>
        </aside>
        @endauth

        <!-- Main Content -->
        <main 
            :class="sidebarCollapsed ? 'ml-20' : 'ml-64'"
            class="flex-1 transition-all duration-300"
        >
            @auth
            <header class="bg-white shadow-sm border-b sticky top-0 z-40">
                <div class="px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">@yield('title', 'Dashboard')</h2>
                    <div class="flex items-center gap-3">
                        <!-- Fullscreen Toggle -->
                        <button 
                            @click="toggleFullscreen()"
                            class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition"
                            title="Toggle Fullscreen"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                            </svg>
                        </button>
                        
                        <!-- User Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button 
                                @click="open = !open"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition text-gray-700"
                            >
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium hidden md:block">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div 
                                x-show="open"
                                x-cloak
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50"
                            >
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->username }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">
                                            {{ ucfirst(str_replace('_', ' ', Auth::user()->role ?? 'user')) }}
                                        </span>
                                    </p>
                                </div>
                                
                                <div class="py-1">
                                    <a href="{{ route('pos.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                            </svg>
                                            POS
                                        </div>
                                    </a>
                                    @unless(Auth::user()->isCashier())
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                            </svg>
                                            Dashboard
                                        </div>
                                    </a>
                                    @endunless
                                    <a href="{{ route('sales.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Sales History
                                        </div>
                                    </a>
                                    @unless(Auth::user()->isCashier())
                                    <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Settings
                                        </div>
                                    </a>
                                    @endunless
                                </div>
                                
                                <div class="border-t border-gray-200 py-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            @endauth
            
            <div class="p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if(isset($errors) && $errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function appLayout() {
            return {
                sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' || false,
                simulatingC2B: false,
                
                init() {
                    // No automatic fullscreen - user controls via toggle button
                },
                
                isFullscreen() {
                    return !!(document.fullscreenElement || 
                             document.webkitFullscreenElement || 
                             document.mozFullScreenElement || 
                             document.msFullscreenElement);
                },
                
                enterFullscreen() {
                    if (this.isFullscreen()) {
                        return; // Already in fullscreen
                    }
                    
                    const element = document.documentElement;
                    
                    const promise = element.requestFullscreen?.() ||
                                   element.webkitRequestFullscreen?.() ||
                                   element.mozRequestFullScreen?.() ||
                                   element.msRequestFullscreen?.();
                    
                    if (promise && promise.catch) {
                        promise.catch(err => {
                            console.log(`Fullscreen request failed: ${err.message}`);
                        });
                    }
                },
                
                exitFullscreen() {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if (document.msExitFullscreen) {
                        document.msExitFullscreen();
                    }
                },
                
                toggleFullscreen() {
                    if (this.isFullscreen()) {
                        this.exitFullscreen();
                    } else {
                        this.enterFullscreen();
                    }
                },
                
                async simulateC2B() {
                    this.simulatingC2B = true;
                    
                    try {
                        const response = await fetch('{{ route("mpesa.simulateC2B") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            credentials: 'same-origin',
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            // Show success message
                            alert(`C2B Transaction Simulated!\n\nTransaction ID: ${data.simulated_transaction.transaction_id}\nAmount: KES ${data.simulated_transaction.amount}\nPhone: ${data.simulated_transaction.phone_number}\nAccount Ref: ${data.simulated_transaction.account_reference}\nCustomer: ${data.simulated_transaction.customer_name}\n\nCheck Pending Payments to allocate this payment.`);
                            
                            // Optionally redirect to pending payments
                            if (confirm('View pending payments?')) {
                                window.location.href = '{{ route("pending-payments.index") }}';
                            }
                        } else {
                            alert('Failed to simulate C2B transaction: ' + (data.message || 'Unknown error'));
                        }
                    } catch (error) {
                        console.error('C2B Simulation Error:', error);
                        alert('Error simulating C2B transaction. Please check console for details.');
                    } finally {
                        this.simulatingC2B = false;
                    }
                }
            }
        }

        function sidebar() {
            return {
                sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' || false,
                
                init() {
                    this.$watch('sidebarCollapsed', value => {
                        localStorage.setItem('sidebarCollapsed', value);
                        window.dispatchEvent(new Event('sidebar-toggle'));
                    });
                }
            }
        }

        // Listen for sidebar toggle events
        window.addEventListener('sidebar-toggle', () => {
            const layout = Alpine.$data(document.querySelector('[x-data="appLayout()"]'));
            if (layout) {
                layout.sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            }
        });
    </script>
</body>
</html>
