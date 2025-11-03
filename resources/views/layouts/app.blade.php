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
            class="bg-gradient-to-b from-blue-600 via-blue-700 to-indigo-800 shadow-2xl fixed h-full transition-all duration-300 z-50"
            x-data="sidebar()"
        >
            <!-- Logo & Toggle -->
            <div class="p-4 border-b border-blue-500/30">
                <div class="flex items-center justify-between">
                    <div x-show="!sidebarCollapsed" class="flex items-center space-x-2">
                        <div class="bg-white rounded-lg p-2">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                            </svg>
                        </div>
                        <h1 class="text-lg font-bold text-white">POS System</h1>
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
            <nav class="flex-1 overflow-y-auto py-4 px-2" style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.2) transparent;">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white shadow-lg' : 'text-blue-100 hover:bg-white/10' }} px-4 py-3 rounded-xl mb-2 transition-all group"
                   title="Dashboard"
                >
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="font-medium">Dashboard</span>
                </a>

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
                        </div>
                    </div>
                    @endcan
                </div>

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
                </div>
            </nav>

            <!-- Logout -->
            <div class="p-4 border-t border-blue-500/30">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center text-blue-100 hover:bg-red-500/20 hover:text-white px-4 py-3 rounded-xl transition">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span x-show="!sidebarCollapsed" class="font-medium">{{ Auth::user()->name }}</span>
                    </button>
                </form>
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
                    <div class="flex items-center gap-2">
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
                
                toggleFullscreen() {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen().catch(err => {
                            console.log(`Error attempting to enable fullscreen: ${err.message}`);
                        });
                    } else {
                        document.exitFullscreen();
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
