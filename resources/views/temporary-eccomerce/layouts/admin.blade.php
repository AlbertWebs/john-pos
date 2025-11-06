<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div x-data="{ sidebarOpen: true }" class="flex h-screen">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-black transform transition-transform duration-300 ease-in-out lg:translate-x-0"
             :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
             x-show="true">
            <div class="flex items-center justify-between h-16 px-6 bg-gray-900">
                <h1 class="text-white font-bold text-lg">Admin Panel</h1>
                <button @click="sidebarOpen = false" class="text-white lg:hidden">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <nav class="mt-6">
                <div class="px-4 space-y-2">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-tachometer-alt w-5"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                    
                    <div class="border-t border-white/20 my-4"></div>
                    
                    <!-- E-commerce -->
                    <div class="px-4 py-2">
                        <h3 class="text-xs font-semibold text-white/60 uppercase tracking-wider">E-commerce</h3>
                    </div>
                    
                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-box w-5"></i>
                        <span class="ml-3">Products</span>
                    </a>
                    
                    <a href="{{ route('admin.categories.index') }}" 
                       class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-tags w-5"></i>
                        <span class="ml-3">Categories</span>
                    </a>
                    
                    <a href="{{ route('admin.orders.index') }}" 
                       class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-shopping-cart w-5"></i>
                        <span class="ml-3">Orders</span>
                    </a>
                    
                                <a href="{{ route('admin.faqs.index') }}"
               class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('admin.faqs.*') ? 'bg-white/20' : '' }}">
                <i class="fas fa-question-circle w-5"></i>
                <span class="ml-3">FAQs</span>
            </a>

                                <a href="{{ route('admin.contact-messages.index') }}"
               class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('admin.contact-messages.*') ? 'bg-white/20' : '' }}">
                <i class="fas fa-envelope w-5"></i>
                <span class="ml-3">Messages</span>
            </a>

            <a href="{{ route('admin.carousel-slides.index') }}"
               class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('admin.carousel-slides.*') ? 'bg-white/20' : '' }}">
                <i class="fas fa-images w-5"></i>
                <span class="ml-3">Carousel Slides</span>
            </a>
                    
                    <div class="border-t border-white/20 my-4"></div>
                    
                    <!-- System -->
                    <div class="px-4 py-2">
                        <h3 class="text-xs font-semibold text-white/60 uppercase tracking-wider">System</h3>
                    </div>
                    
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-user-shield w-5"></i>
                        <span class="ml-3">Users</span>
                    </a>
                    
                    <a href="{{ route('admin.settings') }}" 
                       class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('admin.settings') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-cog w-5"></i>
                        <span class="ml-3">Settings</span>
                    </a>
                </div>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col ml-0 lg:ml-64">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between h-16 px-6">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = true" class="text-gray-600 lg:hidden">
                            <i class="fas fa-bars"></i>
                        </button>
                        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 hidden lg:block mr-4" title="Toggle Sidebar">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Quick Stats -->
                        <div class="hidden md:flex items-center space-x-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-box text-blue-500 mr-1"></i>
                                <span>{{ \App\Models\Product::count() }} products</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-shopping-cart text-green-500 mr-1"></i>
                                <span>{{ \App\Models\Order::count() }} orders</span>
                            </div>
                        </div>
                        
                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-gray-600 hover:text-gray-800">
                                <div class="w-8 h-8 bg-[#7b2c2cf1] rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <span class="hidden md:block text-sm">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" 
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <div class="px-4 py-2 text-xs text-gray-500 border-b">
                                    Signed in as<br>
                                    <strong>{{ auth()->user()->email }}</strong>
                                </div>
                                
                                <a href="{{ route('admin.users.edit', auth()->user()->id) }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-edit mr-2"></i>Profile
                                </a>
                                
                                <a href="{{ route('admin.settings') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i>Settings
                                </a>
                                
                                <div class="border-t border-gray-100"></div>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Breadcrumbs -->
                <nav class="flex mb-6" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-[#7b2c2cf1]">
                                <i class="fas fa-home mr-2"></i>
                                Dashboard
                            </a>
                        </li>
                        @if(request()->routeIs('admin.products.*'))
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                    <span class="text-sm font-medium text-gray-500">Products</span>
                                </div>
                            </li>
                        @elseif(request()->routeIs('admin.categories.*'))
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                    <span class="text-sm font-medium text-gray-500">Categories</span>
                                </div>
                            </li>
                        @elseif(request()->routeIs('admin.orders.*'))
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                    <span class="text-sm font-medium text-gray-500">Orders</span>
                                </div>
                            </li>
                        @elseif(request()->routeIs('admin.users.*'))
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                    <span class="text-sm font-medium text-gray-500">Users</span>
                                </div>
                            </li>
                        @elseif(request()->routeIs('admin.faqs.*'))
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                    <span class="text-sm font-medium text-gray-500">FAQs</span>
                                </div>
                            </li>
                        @elseif(request()->routeIs('admin.contact-messages.*'))
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                    <span class="text-sm font-medium text-gray-500">Messages</span>
                                </div>
                            </li>
                        @elseif(request()->routeIs('admin.settings'))
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                    <span class="text-sm font-medium text-gray-500">Settings</span>
                                </div>
                            </li>
                        @endif
                    </ol>
                </nav>

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html> 