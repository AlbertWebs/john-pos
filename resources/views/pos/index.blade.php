@extends('layouts.app')

@section('title', 'Point of Sale')

@section('content')
<div class="h-screen flex flex-col bg-gradient-to-br from-blue-50 via-white to-purple-50" x-data="posInterface()">
    <!-- Header -->
    <div class="bg-blue-900 shadow-lg px-6 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Point of Sale</h1>
                    <p class="text-sm text-blue-100">{{ Auth::user()->name }} • {{ now()->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-1 flex overflow-hidden">
        <!-- Left Panel - Product Search & Selection -->
        <div class="w-2/3 bg-white border-r border-gray-200 flex flex-col">
            <!-- Search Bar -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200 p-4 shadow-sm">
                <div class="relative">
                    <input 
                        type="text" 
                        x-model="searchQuery"
                        @input.debounce.300ms="searchProducts()"
                        placeholder="Search by name, part number, SKU..."
                        class="w-full px-4 py-3 pl-12 border-2 border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm transition"
                    >
                    <svg class="absolute left-4 top-4 w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Search Results -->
            <div class="flex-1 overflow-y-auto p-4">
                <div class="grid grid-cols-2 gap-4" x-show="!loading && products.length > 0">
                    <template x-for="product in products" :key="product.id">
                        <div 
                            @click="addToCart(product)"
                            class="bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-md hover:shadow-xl p-4 cursor-pointer transition-all duration-200 border-2 hover:border-blue-400 transform hover:scale-[1.02]"
                            :class="product.stock_quantity <= 0 ? 'border-red-300 opacity-75' : 'border-transparent hover:border-blue-400'"
                        >
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1 min-w-0 pr-2">
                                    <h3 class="font-bold text-gray-900 text-sm leading-tight mb-1" x-text="product.name"></h3>
                                    <p class="text-xs text-gray-500 truncate" x-text="product.part_number"></p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="text-lg font-bold text-green-600 whitespace-nowrap" x-text="'KES ' + formatPrice(product.selling_price)"></span>
                                </div>
                            </div>
                            
                            <div class="mt-3 flex items-center justify-between flex-wrap gap-2">
                                <div class="flex gap-2 flex-wrap">
                                    <span x-show="product.category" class="px-2 py-1 bg-blue-100 text-blue-700 rounded-md text-xs font-medium" x-text="product.category"></span>
                                    <span x-show="product.brand" class="px-2 py-1 bg-purple-100 text-purple-700 rounded-md text-xs font-medium" x-text="product.brand"></span>
                                </div>
                                <span 
                                    class="px-2.5 py-1 rounded-md text-xs font-semibold whitespace-nowrap"
                                    :class="product.stock_quantity > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                    x-text="product.stock_quantity > 0 ? '✓ ' + product.stock_quantity + ' in stock' : 'Out of stock'"
                                ></span>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Loading State -->
                <div x-show="loading" class="flex flex-col items-center justify-center h-64">
                    <div class="bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl p-8 shadow-lg">
                        <svg class="animate-spin h-12 w-12 text-blue-600 mb-4 mx-auto" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-blue-700 font-semibold text-center">Searching products...</p>
                    </div>
                </div>

                <!-- Empty State -->
                <div x-show="!loading && products.length === 0 && searchQuery" class="flex flex-col items-center justify-center h-64">
                    <div class="bg-gradient-to-br from-orange-100 to-red-100 rounded-2xl p-8 shadow-lg">
                        <svg class="w-20 h-20 text-orange-500 mb-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <p class="text-orange-700 font-bold text-lg text-center">No products found</p>
                        <p class="text-orange-600 text-sm text-center mt-2">Try a different search term</p>
                    </div>
                </div>

                <!-- Initial State -->
                <div x-show="!loading && products.length === 0 && !searchQuery" class="flex flex-col items-center justify-center h-64">
                    <div class="bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl p-8 shadow-lg">
                        <svg class="w-20 h-20 text-blue-500 mb-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <p class="text-blue-700 font-bold text-lg text-center">Start typing to search</p>
                        <p class="text-blue-600 text-sm text-center mt-2">Search by name, part number, or SKU</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Cart & Checkout -->
        <div class="w-1/3 bg-gradient-to-b from-white to-gray-50 flex flex-col shadow-lg">
            <!-- Customer Selection -->
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200 p-4 shadow-sm">
                <label class="block text-sm font-semibold text-gray-800 mb-2">Customer (Optional)</label>
                <div class="flex gap-2">
                    <input 
                        type="text" 
                        x-model="customerSearch"
                        @input.debounce.300ms="searchCustomers()"
                        placeholder="Search customer..."
                        class="flex-1 px-3 py-2 border-2 border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white shadow-sm"
                    >
                    <button 
                        @click="showCustomerModal = true"
                        class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white rounded-lg text-sm font-semibold shadow-md transition transform hover:scale-105"
                    >
                        + New
                    </button>
                </div>
                <div x-show="selectedCustomer" class="mt-3 p-3 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg shadow-md">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-sm font-bold text-white block" x-text="selectedCustomer.name"></span>
                            <p class="text-xs text-blue-100 mt-1" x-text="'⭐ ' + (selectedCustomer.loyalty_points || 0) + ' points'"></p>
                        </div>
                        <button @click="selectedCustomer = null" class="text-white hover:text-red-200 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cart -->
            <div class="flex-1 overflow-y-auto p-4">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <h2 class="text-lg font-bold text-gray-900">Shopping Cart</h2>
                    <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold" x-text="cart.length" x-show="cart.length > 0"></span>
                </div>
                
                <div x-show="cart.length === 0" class="text-center py-12">
                    <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl p-8">
                        <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <p class="text-gray-500 font-medium">Your cart is empty</p>
                        <p class="text-xs text-gray-400 mt-1">Add products to get started</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <template x-for="(item, index) in cart" :key="index">
                        <div class="bg-gradient-to-br from-white to-blue-50 rounded-xl p-4 border-2 border-blue-200 shadow-md hover:shadow-lg transition">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1 min-w-0 pr-2">
                                    <h3 class="font-bold text-gray-900 text-sm mb-1" x-text="item.name"></h3>
                                    <p class="text-xs text-gray-500" x-text="item.part_number"></p>
                                </div>
                                <button @click="removeFromCart(index)" class="text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg p-1 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <button 
                                        @click="updateQuantity(index, -1)"
                                        class="w-8 h-8 rounded-lg border-2 border-gray-300 hover:bg-indigo-500 hover:text-white hover:border-indigo-500 flex items-center justify-center text-sm font-bold transition"
                                        :disabled="item.quantity <= 1"
                                        :class="item.quantity <= 1 ? 'opacity-50 cursor-not-allowed' : ''"
                                    >-</button>
                                    <input 
                                        type="number" 
                                        x-model="item.quantity"
                                        @change="updateCartItem(index)"
                                        min="1"
                                        :max="item.stock_quantity"
                                        class="w-16 text-center border-2 border-gray-300 rounded-lg text-sm font-semibold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    >
                                    <button 
                                        @click="updateQuantity(index, 1)"
                                        class="w-8 h-8 rounded-lg border-2 border-gray-300 hover:bg-indigo-500 hover:text-white hover:border-indigo-500 flex items-center justify-center text-sm font-bold transition"
                                        :disabled="item.quantity >= item.stock_quantity"
                                        :class="item.quantity >= item.stock_quantity ? 'opacity-50 cursor-not-allowed' : ''"
                                    >+</button>
                                </div>
                                <span class="font-bold text-lg text-green-600" x-text="'KES ' + formatPrice(item.quantity * item.price)"></span>
                            </div>
                            
                            <div class="space-y-2 pt-2 border-t border-gray-200">
                                <!-- Price Adjustment -->
                                <div class="flex items-center gap-2">
                                    <label class="text-xs font-semibold text-gray-700 whitespace-nowrap">Price:</label>
                                    <input 
                                        type="number" 
                                        x-model.number="item.price"
                                        @change="updateItemPrice(index)"
                                        :min="item.min_price"
                                        step="0.01"
                                        class="flex-1 px-2 py-1.5 border-2 rounded-lg text-sm font-medium"
                                        :class="item.price < item.min_price ? 'border-red-500 bg-red-50 text-red-700' : 'border-indigo-300 focus:ring-2 focus:ring-indigo-500'"
                                        placeholder="Price"
                                    >
                                    <span class="text-xs font-semibold text-gray-600 whitespace-nowrap">KES</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span 
                                        class="text-xs font-medium"
                                        :class="item.price < item.min_price ? 'text-red-600' : 'text-gray-600'"
                                        x-text="'Min: KES ' + formatPrice(item.min_price)"
                                    ></span>
                                    <span 
                                        class="px-2.5 py-1 rounded-md font-semibold text-xs"
                                        :class="item.quantity > item.stock_quantity ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'"
                                        x-text="'Stock: ' + item.stock_quantity"
                                    ></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Totals & Checkout -->
            <div class="border-t-2 border-indigo-200 bg-gradient-to-br from-indigo-50 via-white to-purple-50 p-5 shadow-lg">
                <div class="space-y-3 mb-5 bg-white rounded-xl p-4 shadow-md">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 font-medium">Subtotal</span>
                        <span class="font-semibold text-gray-900" x-text="'KES ' + formatPrice(cartTotal.subtotal)"></span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 font-medium">Tax</span>
                        <input 
                            type="number" 
                            x-model.number="tax"
                            @input="calculateTotal()"
                            min="0"
                            step="0.01"
                            class="w-28 text-right border-2 border-indigo-200 rounded-lg px-2 py-1.5 text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="0.00"
                        >
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 font-medium">Discount</span>
                        <input 
                            type="number" 
                            x-model.number="discount"
                            @input="calculateTotal()"
                            min="0"
                            step="0.01"
                            class="w-28 text-right border-2 border-indigo-200 rounded-lg px-2 py-1.5 text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="0.00"
                        >
                    </div>
                    <div class="flex justify-between items-center text-xl font-bold border-t-2 border-indigo-200 pt-3">
                        <span class="text-gray-800">Total</span>
                        <span class="text-green-600 text-2xl" x-text="'KES ' + formatPrice(cartTotal.total)"></span>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex gap-2">
                        <button 
                            @click="paymentMethod = 'Cash'"
                            class="flex-1 py-3 px-4 rounded-xl font-bold text-sm transition transform hover:scale-105 shadow-md"
                            :class="paymentMethod === 'Cash' ? 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                        >
                            💵 Cash
                        </button>
                        <button 
                            @click="paymentMethod = 'M-Pesa'"
                            class="flex-1 py-3 px-4 rounded-xl font-bold text-sm transition transform hover:scale-105 shadow-md"
                            :class="paymentMethod === 'M-Pesa' ? 'bg-gradient-to-r from-yellow-500 to-yellow-600 text-white shadow-lg' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                        >
                            📱 M-Pesa
                        </button>
                    </div>
                    
                    <div x-show="paymentMethod === 'M-Pesa'" class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-3 space-y-2">
                        <input 
                            type="tel" 
                            x-model="mpesaPhoneNumber"
                            :placeholder="selectedCustomer ? selectedCustomer.phone || 'Customer phone number (2547XXXXXXXX)' : 'Customer phone number (2547XXXXXXXX)'"
                            class="w-full px-3 py-2.5 border-2 border-yellow-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm font-medium bg-white"
                        >
                        <p class="text-xs text-yellow-700 font-medium">Use customer phone number for M-Pesa payment</p>
                        <button 
                            type="button"
                            @click="initiateSTKPush()"
                            :disabled="!mpesaPhoneNumber || cartTotal.total <= 0 || processingSTK"
                            class="w-full py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white rounded-lg font-bold transition transform hover:scale-105 disabled:bg-gray-300 disabled:cursor-not-allowed disabled:transform-none text-sm flex items-center justify-center gap-2 shadow-md"
                        >
                            <span x-show="!processingSTK">📱 Initiate STK Push</span>
                            <span x-show="processingSTK" class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                        <input 
                            type="text" 
                            x-model="transactionReference"
                            placeholder="Transaction reference (auto-filled after STK)"
                            class="w-full px-3 py-2 border-2 border-yellow-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm bg-white font-medium"
                            readonly
                        >
                        <p class="text-xs text-yellow-600 font-medium">Enter phone number and click "Initiate STK Push" to prompt customer</p>
                    </div>

                    <button 
                        @click="checkout()"
                        :disabled="cart.length === 0 || processing"
                        class="w-full py-4 bg-gradient-to-r from-green-500 via-green-600 to-green-700 hover:from-green-600 hover:via-green-700 hover:to-green-800 text-white rounded-xl font-bold text-lg transition transform hover:scale-105 disabled:bg-gray-300 disabled:cursor-not-allowed disabled:transform-none shadow-lg flex items-center justify-center gap-2"
                    >
                        <span x-show="!processing" class="flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Complete Sale
                        </span>
                        <span x-show="processing" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-6 w-6" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Modal -->
    <div 
        x-show="showCustomerModal"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
        @click.self="showCustomerModal = false"
        @keydown.escape.window="showCustomerModal = false"
    >
        <div 
            class="bg-white rounded-lg shadow-xl max-w-md w-full p-6" 
            @click.stop
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
        >
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-900">Add New Customer</h2>
                <button 
                    @click="showCustomerModal = false"
                    class="text-gray-400 hover:text-gray-600"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form @submit.prevent="createCustomer()">
                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Customer Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="customer_name"
                            x-model="newCustomer.name"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter customer name"
                        >
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number <span class="text-red-500">*</span>
                            <span class="text-xs text-gray-500">(For M-Pesa)</span>
                        </label>
                        <input 
                            type="tel" 
                            id="customer_phone"
                            x-model="newCustomer.phone"
                            required
                            pattern="[0-9]{10,12}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="2547XXXXXXXX"
                        >
                        <p class="mt-1 text-xs text-gray-500">Format: 2547XXXXXXXX (e.g., 254712345678)</p>
                    </div>

                    <!-- Email (Auto-generated) -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <p class="text-xs text-blue-800">
                            <strong>Note:</strong> A unique email will be automatically generated for this customer.
                        </p>
                    </div>

                    <!-- Error Message -->
                    <div x-show="customerError" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <p x-text="customerError"></p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 pt-4">
                        <button 
                            type="button"
                            @click="showCustomerModal = false"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            :disabled="creatingCustomer"
                            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium disabled:bg-gray-300 disabled:cursor-not-allowed"
                        >
                            <span x-show="!creatingCustomer">Add Customer</span>
                            <span x-show="creatingCustomer" class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Creating...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function posInterface() {
    return {
        searchQuery: '',
        products: [],
        loading: false,
        cart: [],
        customerSearch: '',
        selectedCustomer: null,
        paymentMethod: 'Cash',
        mpesaPhoneNumber: '',
        transactionReference: '',
        processingSTK: false,
        tax: 0,
        discount: 0,
        processing: false,
        showCustomerModal: false,
        newCustomer: {
            name: '',
            phone: '',
        },
        creatingCustomer: false,
        customerError: '',

        cartTotal: {
            subtotal: 0,
            tax: 0,
            discount: 0,
            total: 0,
        },

        init() {
            this.calculateTotal();
            
            // Auto-populate M-Pesa phone from selected customer
            this.$watch('selectedCustomer', (customer) => {
                if (customer && customer.phone && !this.mpesaPhoneNumber) {
                    this.mpesaPhoneNumber = customer.phone;
                }
            });
        },

        async searchProducts() {
            if (!this.searchQuery || this.searchQuery.length < 2) {
                this.products = [];
                return;
            }

            this.loading = true;
            try {
                const response = await fetch(`{{ route('pos.search') }}?search=${encodeURIComponent(this.searchQuery)}`);
                const data = await response.json();
                this.products = data;
            } catch (error) {
                console.error('Search error:', error);
            } finally {
                this.loading = false;
            }
        },

        addToCart(product) {
            if (product.stock_quantity <= 0) {
                alert('This item is out of stock');
                return;
            }

            const existingIndex = this.cart.findIndex(item => item.id === product.id);
            
            if (existingIndex >= 0) {
                if (this.cart[existingIndex].quantity < product.stock_quantity) {
                    this.cart[existingIndex].quantity++;
                } else {
                    alert('Cannot add more. Stock limit reached.');
                }
            } else {
                this.cart.push({
                    id: product.id,
                    name: product.name,
                    part_number: product.part_number,
                    price: product.selling_price,
                    quantity: 1,
                    stock_quantity: product.stock_quantity,
                    min_price: product.min_price,
                });
            }
            
            this.calculateTotal();
        },

        removeFromCart(index) {
            this.cart.splice(index, 1);
            this.calculateTotal();
        },

        updateQuantity(index, change) {
            const item = this.cart[index];
            const newQuantity = item.quantity + change;
            
            if (newQuantity < 1) return;
            if (newQuantity > item.stock_quantity) {
                alert('Cannot exceed available stock');
                return;
            }
            
            item.quantity = newQuantity;
            this.calculateTotal();
        },

        updateCartItem(index) {
            const item = this.cart[index];
            if (item.quantity < 1) {
                item.quantity = 1;
            }
            if (item.quantity > item.stock_quantity) {
                item.quantity = item.stock_quantity;
                alert('Quantity adjusted to available stock');
            }
            this.calculateTotal();
        },

        updateItemPrice(index) {
            const item = this.cart[index];
            if (item.price < item.min_price) {
                alert(`Price cannot be below minimum price of KES ${this.formatPrice(item.min_price)}. Price will be set to minimum.`);
                item.price = parseFloat(item.min_price);
            }
            this.calculateTotal();
        },

        calculateTotal() {
            this.cartTotal.subtotal = this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            this.cartTotal.tax = this.tax || 0;
            this.cartTotal.discount = this.discount || 0;
            this.cartTotal.total = this.cartTotal.subtotal + this.cartTotal.tax - this.cartTotal.discount;
        },

        async checkout() {
            if (this.cart.length === 0) {
                alert('Cart is empty');
                return;
            }

            // Validate cart items
            for (let item of this.cart) {
                if (item.price < item.min_price) {
                    alert(`${item.name}: Price below minimum (KES ${this.formatPrice(item.min_price)})`);
                    return;
                }
                if (item.quantity > item.stock_quantity) {
                    alert(`${item.name}: Insufficient stock`);
                    return;
                }
            }

            if (!this.paymentMethod) {
                alert('Please select a payment method');
                return;
            }

            if (this.paymentMethod === 'M-Pesa' && !this.transactionReference) {
                alert('Please initiate STK Push and wait for payment confirmation');
                return;
            }

            this.processing = true;

            try {
                const response = await fetch('{{ route("sales.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        customer_id: this.selectedCustomer ? this.selectedCustomer.id : null,
                        items: this.cart.map(item => ({
                            part_id: item.id,
                            quantity: item.quantity,
                            price: item.price,
                        })),
                        payment_method: this.paymentMethod,
                        transaction_reference: this.transactionReference || null,
                        subtotal: this.cartTotal.subtotal,
                        tax: this.cartTotal.tax,
                        discount: this.cartTotal.discount,
                        total_amount: this.cartTotal.total,
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    // Redirect to receipt
                    window.location.href = data.redirect_url;
                } else {
                    alert(data.message || 'Checkout failed');
                }
            } catch (error) {
                console.error('Checkout error:', error);
                alert('An error occurred during checkout');
            } finally {
                this.processing = false;
            }
        },

        async searchCustomers() {
            if (!this.customerSearch || this.customerSearch.length < 2) {
                this.selectedCustomer = null;
                return;
            }
            
            try {
                const response = await fetch(`/customers?search=${encodeURIComponent(this.customerSearch)}&ajax=1`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });
                
                if (response.ok) {
                    const customers = await response.json();
                    if (customers.length > 0) {
                        // Auto-select if exact match or first result
                        const exactMatch = customers.find(c => 
                            c.name.toLowerCase() === this.customerSearch.toLowerCase() ||
                            c.phone === this.customerSearch
                        );
                        this.selectedCustomer = exactMatch || customers[0];
                    } else {
                        this.selectedCustomer = null;
                    }
                }
            } catch (error) {
                console.error('Customer search error:', error);
            }
        },

        async createCustomer() {
            if (!this.newCustomer.name || !this.newCustomer.phone) {
                this.customerError = 'Please fill in all required fields';
                return;
            }

            // Normalize phone number (remove spaces, dashes, etc.)
            let phone = this.newCustomer.phone.replace(/[\s\-\(\)]/g, '');
            
            // Convert formats: 07XXXXXXXX -> 2547XXXXXXXX, +2547XXXXXXXX -> 2547XXXXXXXX
            if (phone.startsWith('0')) {
                phone = '254' + phone.substring(1);
            } else if (phone.startsWith('+254')) {
                phone = phone.substring(1);
            }

            // Validate phone number format (should start with 254 for Kenya)
            if (!/^254\d{9}$/.test(phone)) {
                this.customerError = 'Phone number must be in format 2547XXXXXXXX (12 digits starting with 254) or 07XXXXXXXX';
                return;
            }

            this.creatingCustomer = true;
            this.customerError = '';

            try {
                const response = await fetch('{{ route("customers.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        name: this.newCustomer.name,
                        phone: phone,
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    // Set the newly created customer
                    this.selectedCustomer = data.customer;
                    this.customerSearch = data.customer.name;
                    
                    // Reset form and close modal
                    this.newCustomer = { name: '', phone: '' };
                    this.showCustomerModal = false;
                    this.customerError = '';
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat();
                        this.customerError = errorMessages.join(', ');
                    } else {
                        this.customerError = data.message || 'Failed to create customer';
                    }
                }
            } catch (error) {
                console.error('Create customer error:', error);
                this.customerError = 'An error occurred while creating the customer';
            } finally {
                this.creatingCustomer = false;
            }
        },

        async initiateSTKPush() {
            if (!this.mpesaPhoneNumber || this.mpesaPhoneNumber.length < 10) {
                alert('Please enter a valid phone number');
                return;
            }

            if (this.cart.length === 0) {
                alert('Cart is empty');
                return;
            }

            this.processingSTK = true;

            try {
                const response = await fetch('{{ route("mpesa.stkPush") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        phone_number: this.mpesaPhoneNumber,
                        amount: this.cartTotal.total,
                        account_reference: 'POS-' + Date.now(),
                        transaction_desc: 'Payment for ' + this.cart.length + ' item(s)',
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    this.transactionReference = data.checkout_request_id;
                    alert(data.customer_message || 'STK Push initiated. Please check your phone.');
                    
                    // Poll for payment status
                    this.checkPaymentStatus(data.checkout_request_id);
                } else {
                    // Show detailed error message
                    const errorMsg = data.error || data.message || 'Failed to initiate STK Push';
                    alert('Error: ' + errorMsg + '\n\n' + (data.message || 'Please check your M-Pesa configuration.'));
                }
            } catch (error) {
                console.error('STK Push error:', error);
                alert('Failed to initiate STK Push. Please check your internet connection and M-Pesa configuration.');
            } finally {
                this.processingSTK = false;
            }
        },

        async checkPaymentStatus(checkoutRequestId) {
            // Poll for payment status every 3 seconds, max 20 times (1 minute)
            let attempts = 0;
            const maxAttempts = 20;

            const pollInterval = setInterval(async () => {
                attempts++;

                try {
                    const response = await fetch('{{ route("mpesa.checkStatus") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            checkout_request_id: checkoutRequestId,
                        }),
                    });

                    const data = await response.json();

                    if (data.ResultCode == 0) {
                        // Payment successful
                        clearInterval(pollInterval);
                        this.transactionReference = data.MpesaReceiptNumber || checkoutRequestId;
                        alert('Payment confirmed! Transaction: ' + (data.MpesaReceiptNumber || checkoutRequestId));
                    } else if (data.ResultCode && data.ResultCode != 1032) {
                        // Payment failed (1032 is still processing)
                        clearInterval(pollInterval);
                        alert('Payment failed: ' + (data.ResultDesc || 'Unknown error'));
                    }

                    if (attempts >= maxAttempts) {
                        clearInterval(pollInterval);
                        alert('Payment confirmation timeout. Please verify payment manually.');
                    }
                } catch (error) {
                    console.error('Status check error:', error);
                }
            }, 3000);
        },

        formatPrice(price) {
            return parseFloat(price).toFixed(2);
        }
    }
}
</script>
@endsection
