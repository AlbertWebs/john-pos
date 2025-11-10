@extends('layouts.app')

@section('title', 'Point of Sale')

@section('content')
<style>
    /* Zoom out POS page to fit everything on screen */
    @media screen {
        .pos-container {
            zoom: 0.95;
            transform-origin: top left;
        }
        
        /* For browsers that don't support zoom, use transform scale */
        @supports not (zoom: 1) {
            .pos-container {
                zoom: 1;
                transform: scale(0.95);
                transform-origin: top left;
                width: 117.65%; /* 100 / 0.85 */
                height: 117.65%; /* 100 / 0.85 */
            }
        }
        
        /* Adjust for smaller screens */
        @media (max-width: 1920px) {
            .pos-container {
                zoom: 0.8;
            }
            @supports not (zoom: 1) {
                .pos-container {
                    transform: scale(0.8);
                    width: 125%; /* 100 / 0.8 */
                    height: 125%; /* 100 / 0.8 */
                }
            }
        }
        
        @media (max-width: 1680px) {
            .pos-container {
                zoom: 0.75;
            }
            @supports not (zoom: 1) {
                .pos-container {
                    transform: scale(0.75);
                    width: 133.33%; /* 100 / 0.75 */
                    height: 133.33%; /* 100 / 0.75 */
                }
            }
        }
        
        @media (max-width: 1440px) {
            .pos-container {
                zoom: 0.8;
            }
            @supports not (zoom: 1) {
                .pos-container {
                    transform: scale(0.7);
                    width: 142.86%; /* 100 / 0.7 */
                    height: 142.86%; /* 100 / 0.7 */
                }
            }
        }
    }
</style>
<div class="pos-container h-screen flex flex-col bg-gradient-to-br from-blue-50 via-white to-purple-50" x-data="posInterface()">
    <!-- Notification Toasts -->
    <div class="fixed top-4 right-4 z-50 space-y-2" style="max-width: 400px;">
        <template x-for="notification in notifications" :key="notification.id">
            <div 
                x-show="notifications.find(n => n.id === notification.id)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-x-full"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform translate-x-full"
                :class="{
                    'bg-red-500': notification.type === 'error',
                    'bg-green-500': notification.type === 'success',
                    'bg-yellow-500': notification.type === 'warning'
                }"
                class="text-white px-6 py-4 rounded-lg shadow-lg flex items-center justify-between gap-4"
            >
                <p class="flex-1 font-medium" x-text="notification.message"></p>
                <button 
                    @click="removeNotification(notification.id)"
                    class="text-white hover:text-gray-200 transition"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </template>
    </div>

    <div class="flex-1 flex overflow-hidden">
        <!-- Left Panel - Product Search & Selection -->
        <div class="w-2/3 bg-white border-r border-gray-200 flex flex-col">
            <!-- Barcode Scanner & Search Bar - Side by Side -->
            <div class="bg-gradient-to-r from-green-50 via-blue-50 to-indigo-50 border-b border-gray-200 p-4 shadow-sm">
                <div class="flex gap-4">
                    <!-- Barcode Scanner -->
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-800 mb-2">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                            </svg>
                            Scan Barcode
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                x-ref="barcodeInput"
                                x-model="barcodeQuery"
                                @keydown="handleBarcodeInput($event)"
                                @input="handleBarcodeChange()"
                                placeholder="Scan barcode..."
                                class="w-full px-4 py-2.5 pl-10 border-2 border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white shadow-sm transition font-mono text-sm"
                                autocomplete="off"
                                autofocus
                            >
                          
                        </div>
                    </div>
                    
                    <!-- Search Bar -->
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-800 mb-2">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search by Name
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                x-model="searchQuery"
                                @input.debounce.300ms="searchProducts()"
                                placeholder="Search by name, part number, SKU..."
                                class="w-full px-4 py-2.5 pl-10 border-2 border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm transition text-sm"
                            >
                           
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Results -->
            <div class="flex-1 overflow-y-auto p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-show="!loading && products.length > 0">
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
                            
                            <!-- Vehicle Compatibility Tooltip -->
                            <div class="mt-2 relative group" x-show="product.vehicle_make || product.vehicle_model || (product.vehicle_models && product.vehicle_models.length > 0)">
                                <button 
                                    type="button"
                                    @click.stop="toggleCompatibility(product)"
                                    class="flex items-center gap-1 text-xs text-gray-600 hover:text-blue-600 transition"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>Vehicle Compatibility</span>
                                </button>
                                
                                <!-- Compatibility Popup -->
                                <div 
                                    x-show="showCompatibility && compatibilityProduct && compatibilityProduct.id === product.id"
                                    @click.away="showCompatibility = false"
                                    x-cloak
                                    class="absolute z-50 bottom-full left-0 mb-2 w-64 bg-white rounded-lg shadow-xl border-2 border-blue-200 p-4"
                                >
                                    <h4 class="font-bold text-sm text-gray-900 mb-2">Vehicle Compatibility</h4>
                                    <div class="space-y-2 text-xs">
                                        <div x-show="compatibilityProduct.vehicle_make">
                                            <span class="font-semibold text-gray-700">Make:</span>
                                            <span class="text-gray-600" x-text="compatibilityProduct.vehicle_make"></span>
                                        </div>
                                        <div x-show="compatibilityProduct.vehicle_model">
                                            <span class="font-semibold text-gray-700">Model:</span>
                                            <span class="text-gray-600" x-text="compatibilityProduct.vehicle_model"></span>
                                        </div>
                                        <div x-show="compatibilityProduct.year_range">
                                            <span class="font-semibold text-gray-700">Year Range:</span>
                                            <span class="text-gray-600" x-text="compatibilityProduct.year_range"></span>
                                        </div>
                                        <div x-show="compatibilityProduct.vehicle_models && compatibilityProduct.vehicle_models.length > 0">
                                            <span class="font-semibold text-gray-700 block mb-1">Compatible Models:</span>
                                            <ul class="list-disc list-inside space-y-1 text-gray-600">
                                                <template x-for="model in compatibilityProduct.vehicle_models" :key="model.id">
                                                    <li x-text="model.name + (model.year_start && model.year_end ? ' (' + model.year_start + '-' + model.year_end + ')' : '')"></li>
                                                </template>
                                            </ul>
                                        </div>
                                        <div x-show="!compatibilityProduct.vehicle_make && !compatibilityProduct.vehicle_model && (!compatibilityProduct.vehicle_models || compatibilityProduct.vehicle_models.length === 0)">
                                            <span class="text-gray-500 italic">No specific vehicle compatibility information</span>
                                        </div>
                                    </div>
                                </div>
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
                <div x-show="!loading && products.length === 0 && searchQuery" class="flex flex-col items-center justify-center h-64 space-y-4">
                    <div class="bg-gradient-to-br from-orange-100 to-red-100 rounded-2xl p-8 shadow-lg">
                        <svg class="w-20 h-20 text-orange-500 mb-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <p class="text-orange-700 font-bold text-lg text-center">No products found</p>
                        <p class="text-orange-600 text-sm text-center mt-2">Try a different search term</p>
                    </div>
                    <button 
                        type="button"
                        @click="openNextOrderFromSearch()"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition flex items-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Request to Next Orders
                    </button>
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

            <!-- C2B Payment Allocation Section -->
            <div class="border-t-2 border-blue-200 bg-gradient-to-r from-blue-50 to-indigo-50 p-4 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-bold text-blue-900">Direct Paybill Payments</label>
                    <button 
                        type="button"
                        @click="loadPendingPayments()"
                        class="text-xs text-blue-600 hover:text-blue-800 font-medium"
                    >
                        🔄 Refresh
                    </button>
                </div>
                
                <!-- Search/Filter Pending Payments -->
                <input 
                    type="text" 
                    x-model="pendingPaymentSearch"
                    @input.debounce.300ms="searchPendingPayments()"
                    placeholder="Search by phone, reference, or amount..."
                    class="w-full px-3 py-2 border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-medium bg-white mb-2"
                >
                
                <!-- Pending Payments List -->
                <div class="max-h-48 overflow-y-auto space-y-2" x-show="pendingPayments.length > 0">
                    <template x-for="payment in filteredPendingPayments" :key="payment.id">
                        <div 
                            @click="selectPendingPayment(payment)"
                            class="p-2 bg-white rounded-lg border-2 cursor-pointer transition"
                            :class="selectedPendingPayment && selectedPendingPayment.id === payment.id ? 'border-blue-500 bg-blue-50' : 'border-blue-200 hover:border-blue-400'"
                        >
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-gray-900" x-text="'KES ' + formatPrice(payment.amount)"></p>
                                    <p class="text-xs text-gray-600" x-text="payment.phone_number"></p>
                                    <p class="text-xs text-gray-500" x-text="payment.transaction_reference"></p>
                                </div>
                                <div class="flex-shrink-0 ml-2">
                                    <span 
                                        class="px-2 py-1 text-xs rounded font-semibold"
                                        :class="Math.abs(payment.amount - cartTotal.total) <= 0.01 ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'"
                                        x-text="Math.abs(payment.amount - cartTotal.total) <= 0.01 ? 'Match' : 'Mismatch'"
                                    ></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                
                <!-- No Pending Payments -->
                <div x-show="pendingPayments.length === 0 && !loadingPendingPayments" class="text-center py-2">
                    <p class="text-xs text-gray-500">No pending C2B payments</p>
                </div>
                
                <!-- Loading State -->
                <div x-show="loadingPendingPayments" class="text-center py-2">
                    <svg class="animate-spin h-4 w-4 text-blue-600 mx-auto" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                
                <!-- Selected Payment Info -->
                <div x-show="selectedPendingPayment" class="bg-green-50 border-2 border-green-300 rounded-lg p-2 mt-2">
                    <p class="text-xs font-bold text-green-900 mb-1">Selected Payment:</p>
                    <p class="text-xs text-green-700" x-text="'KES ' + formatPrice(selectedPendingPayment.amount) + ' - ' + selectedPendingPayment.transaction_reference"></p>
                    <button 
                        type="button"
                        @click="selectedPendingPayment = null; transactionReference = ''"
                        class="mt-1 text-xs text-red-600 hover:text-red-800 font-medium"
                    >
                        Clear Selection
                    </button>
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
                                        :class="Number(item.price) < Number(item.min_price) ? 'border-red-500 bg-red-50 text-red-700' : 'border-indigo-300 focus:ring-2 focus:ring-indigo-500'"
                                        placeholder="Price"
                                    >
                                    <span class="text-xs font-semibold text-gray-600 whitespace-nowrap">KES</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span 
                                        class="text-xs font-medium"
                                        :class="Number(item.price) < Number(item.min_price) ? 'text-red-600' : 'text-gray-600'"
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
                    
                    <div x-show="paymentMethod === 'M-Pesa'" class="space-y-3">
                        <!-- STK Push Section -->
                        <div class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-3 space-y-2">
                            <p class="text-xs font-bold text-yellow-900 mb-2">Or use STK Push:</p>
                            <input 
                                type="tel" 
                                x-model="mpesaPhoneNumber"
                                :disabled="!!selectedPendingPayment"
                                :placeholder="selectedCustomer ? selectedCustomer.phone || 'Customer phone number (2547XXXXXXXX)' : 'Customer phone number (2547XXXXXXXX)'"
                                class="w-full px-3 py-2.5 border-2 border-yellow-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm font-medium bg-white"
                                :class="selectedPendingPayment ? 'opacity-50 cursor-not-allowed' : ''"
                            >
                            <p class="text-xs text-yellow-700 font-medium">Use customer phone number for M-Pesa payment</p>
                            <button 
                                type="button"
                                @click="initiateSTKPush()"
                                :disabled="!mpesaPhoneNumber || cartTotal.total <= 0 || processingSTK || !!selectedPendingPayment"
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
                                placeholder="Transaction reference (auto-filled after STK or C2B)"
                                class="w-full px-3 py-2 border-2 border-yellow-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm bg-white font-medium"
                                :readonly="!!selectedPendingPayment"
                            >
                            <p class="text-xs text-yellow-600 font-medium" x-show="!selectedPendingPayment">Enter phone number and click "Initiate STK Push" to prompt customer</p>
                        </div>
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

    <!-- Success Modal -->
    <div 
        x-show="showSuccessModal"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-20 backdrop-blur-sm z-50 flex items-center justify-center p-4"
        @click.self="closeSuccessModal()"
        @keydown.escape.window="closeSuccessModal()"
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
            <div class="text-center">
                <!-- Success Icon -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Sale Completed!</h2>
                <p class="text-gray-600 mb-1" x-show="completedSaleInvoice">
                    Invoice: <span class="font-semibold" x-text="completedSaleInvoice"></span>
                </p>
                <p class="text-sm text-gray-500 mb-6">The receipt print dialog will open automatically. You can continue working below.</p>
                
                <!-- Buttons -->
                <div class="flex gap-3">
                    <button 
                        @click="printReceipt()"
                        class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition transform hover:scale-105 flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Print Receipt
                    </button>
                    <button 
                        @click="closeSuccessModal()"
                        class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-semibold transition"
                    >
                        Continue
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function posInterface() {
    return {
        searchQuery: '',
        barcodeQuery: '',
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
        barcodeInputTimeout: null,
        lastBarcodeInputTime: 0,
        scanningBarcode: false,
        audioContext: null,
        pendingPayments: [],
        filteredPendingPayments: [],
        pendingPaymentSearch: '',
        selectedPendingPayment: null,
        loadingPendingPayments: false,
        showCompatibility: false,
        compatibilityProduct: null,
        notifications: [],
        showSuccessModal: false,
        completedSaleId: null,
        completedSaleInvoice: null,

        cartTotal: {
            subtotal: 0,
            tax: 0,
            discount: 0,
            total: 0,
        },

        init() {
            this.calculateTotal();
            
            // Auto-focus barcode input on page load
            // Use multiple methods to ensure focus works reliably
            this.$nextTick(() => {
                setTimeout(() => {
                    if (this.$refs.barcodeInput) {
                        this.$refs.barcodeInput.focus();
                    }
                }, 100);
            });
            
            // Also focus after a short delay to ensure DOM is ready
            setTimeout(() => {
                if (this.$refs.barcodeInput) {
                    this.$refs.barcodeInput.focus();
                }
            }, 300);
            
            // Auto-populate M-Pesa phone from selected customer
            this.$watch('selectedCustomer', (customer) => {
                if (customer && customer.phone && !this.mpesaPhoneNumber) {
                    this.mpesaPhoneNumber = customer.phone;
                }
            });
            
            // Load pending payments on page load
            this.loadPendingPayments();
            
            // Watch payment method changes
            this.$watch('paymentMethod', (method) => {
                if (method !== 'M-Pesa' && this.selectedPendingPayment) {
                    this.selectedPendingPayment = null;
                    this.transactionReference = '';
                }
            });
            
            // Initialize audio context on first user interaction
            this.initializeAudio();

            window.addEventListener('next-order-saved', (event) => {
                const message = event.detail && event.detail.message ? event.detail.message : 'Next order recorded successfully.';
                this.showNotification(message, 'success');
            });
        },

        initializeAudio() {
            // Initialize audio context on first user interaction (required by browsers)
            const initAudio = () => {
                try {
                    if (!this.audioContext) {
                        this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
                    }
                    // Resume audio context if suspended (required by some browsers)
                    if (this.audioContext.state === 'suspended') {
                        this.audioContext.resume();
                    }
                } catch (error) {
                    console.log('Audio initialization failed:', error);
                }
            };
            
            // Initialize on any user interaction
            document.addEventListener('click', initAudio, { once: true });
            document.addEventListener('keydown', initAudio, { once: true });
            this.$refs.barcodeInput?.addEventListener('focus', initAudio, { once: true });
        },

        playBeepSound() {
            try {
                // Create or reuse audio context
                if (!this.audioContext) {
                    this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
                }
                
                // Resume if suspended
                if (this.audioContext.state === 'suspended') {
                    this.audioContext.resume();
                }
                
                const oscillator = this.audioContext.createOscillator();
                const gainNode = this.audioContext.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(this.audioContext.destination);

                // Configure beep sound (short, pleasant beep)
                oscillator.frequency.value = 800; // Frequency in Hz (higher = more beep-like)
                oscillator.type = 'sine'; // Sine wave for a smooth beep
                
                // Volume control (30% volume)
                gainNode.gain.setValueAtTime(0.3, this.audioContext.currentTime); // Start volume
                gainNode.gain.exponentialRampToValueAtTime(0.01, this.audioContext.currentTime + 0.1); // Fade out

                // Play beep (100ms duration)
                oscillator.start(this.audioContext.currentTime);
                oscillator.stop(this.audioContext.currentTime + 0.1);
            } catch (error) {
                // Fallback: If Web Audio API is not available, silently fail
                console.log('Audio playback failed:', error);
            }
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

        handleBarcodeInput(event) {
            const currentTime = Date.now();
            
            // Detect if this is a barcode scanner input
            // Barcode scanners typically send characters very quickly (< 50ms between chars)
            // and end with Enter, Tab, or other special keys
            if (event.key === 'Enter' || event.key === 'Tab') {
                // Clear any pending timeout
                if (this.barcodeInputTimeout) {
                    clearTimeout(this.barcodeInputTimeout);
                    this.barcodeInputTimeout = null;
                }
                
                // If Enter was pressed and input was rapid, it's likely from a scanner
                if (event.key === 'Enter' && (currentTime - this.lastBarcodeInputTime) < 100) {
                    event.preventDefault();
                    this.searchByBarcode();
                    return false;
                }
            } else {
                // Regular character input
                this.lastBarcodeInputTime = currentTime;
                
                // Clear previous timeout
                if (this.barcodeInputTimeout) {
                    clearTimeout(this.barcodeInputTimeout);
                }
                
                // Set timeout to detect end of input (for scanners that don't send Enter)
                this.barcodeInputTimeout = setTimeout(() => {
                    // Input has stopped, could be end of barcode scan
                    if (this.barcodeQuery && this.barcodeQuery.length >= 3) {
                        this.searchByBarcode();
                    }
                }, 200);
            }
        },

        handleBarcodeChange() {
            // Clear timeout when user manually types
            if (this.barcodeInputTimeout) {
                clearTimeout(this.barcodeInputTimeout);
                this.barcodeInputTimeout = null;
            }
        },

        async searchByBarcode() {
            if (!this.barcodeQuery || this.barcodeQuery.trim().length === 0) {
                return;
            }

            const barcode = this.barcodeQuery.trim();
            this.scanningBarcode = true;

            try {
                // Search for product by barcode
                const response = await fetch(`{{ route('pos.search') }}?search=${encodeURIComponent(barcode)}`);
                const data = await response.json();
                
                // Find exact barcode match
                const product = data.find(p => p.barcode === barcode || p.part_number === barcode || p.sku === barcode);
                
                if (product) {
                    // Check if product is in stock
                    if (product.stock_quantity <= 0) {
                        this.showNotification(`${product.name} is out of stock`, 'error');
                        this.openNextOrderModal({
                            item_name: product.name,
                            part_number: product.part_number,
                        });
                        this.barcodeQuery = '';
                        this.$refs.barcodeInput?.focus();
                        return;
                    }
                    
                    // Add to cart
                    this.addToCart(product);
                    
                    // Play beep sound for successful scan
                    this.playBeepSound();
                    
                    // Clear barcode input and refocus
                    this.barcodeQuery = '';
                    this.$refs.barcodeInput?.focus();
                } else {
                    // No exact match found, show search results
                    if (data.length > 0) {
                        this.products = data;
                        this.searchQuery = barcode;
                        this.showNotification(`Found ${data.length} product(s) matching "${barcode}". Please select from the list.`, 'warning');
                    } else {
                        this.showNotification(`No product found with barcode "${barcode}"`, 'error');
                        this.openNextOrderModal({
                            item_name: barcode,
                            notes: `Requested via barcode search "${barcode}"`,
                        });
                        this.barcodeQuery = '';
                        this.$refs.barcodeInput?.focus();
                    }
                }
            } catch (error) {
                console.error('Barcode search error:', error);
                this.showNotification('Error searching for barcode. Please try again.', 'error');
            } finally {
                this.scanningBarcode = false;
            }
        },

        addToCart(product) {
            if (product.stock_quantity <= 0) {
                this.showNotification('This item is out of stock', 'error');
                this.openNextOrderModal({
                    item_name: product.name,
                    part_number: product.part_number,
                });
                return;
            }

            const existingIndex = this.cart.findIndex(item => item.id === product.id);
            
            if (existingIndex >= 0) {
                if (this.cart[existingIndex].quantity < product.stock_quantity) {
                    this.cart[existingIndex].quantity++;
                } else {
                    this.showNotification('Cannot add more. Stock limit reached.', 'error');
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
                this.showNotification('Cannot exceed available stock', 'error');
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
                this.showNotification('Quantity adjusted to available stock', 'warning');
            }
            this.calculateTotal();
        },

        updateItemPrice(index) {
            const item = this.cart[index];
            const price = Number(item.price);
            const minPrice = Number(item.min_price);
            
            if (isNaN(price) || price < minPrice) {
                this.showNotification(`Price cannot be below minimum price of KES ${this.formatPrice(item.min_price)}. Price will be set to minimum.`, 'warning');
                item.price = minPrice;
            } else {
                // Ensure price is stored as a number
                item.price = price;
            }
            this.calculateTotal();
        },

        calculateTotal() {
            this.cartTotal.subtotal = this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            this.cartTotal.tax = this.tax || 0;
            this.cartTotal.discount = this.discount || 0;
            this.cartTotal.total = this.cartTotal.subtotal + this.cartTotal.tax - this.cartTotal.discount;
        },

        openNextOrderModal(detail = {}) {
            const payload = {
                item_name: detail.item_name || '',
                part_number: detail.part_number || '',
                requested_quantity: detail.requested_quantity || 1,
                customer_name: detail.customer_name || (this.selectedCustomer ? this.selectedCustomer.name : ''),
                customer_contact: detail.customer_contact || (this.selectedCustomer ? (this.selectedCustomer.phone || '') : ''),
                notes: detail.notes || '',
            };

            if (!payload.item_name && this.searchQuery) {
                payload.item_name = this.searchQuery;
            }

            if (!payload.notes && this.searchQuery) {
                payload.notes = `Requested via search "${this.searchQuery}"`;
            }

            window.dispatchEvent(new CustomEvent('open-next-order-modal', { detail: payload }));
        },

        openNextOrderFromSearch() {
            if (!this.searchQuery || this.searchQuery.trim().length === 0) {
                this.showNotification('Enter a product name first.', 'warning');
                return;
            }

            this.openNextOrderModal({
                item_name: this.searchQuery,
            });
        },

        showNotification(message, type = 'error') {
            const notification = {
                id: Date.now(),
                message: message,
                type: type, // 'error', 'success', 'warning'
            };
            this.notifications.push(notification);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                this.removeNotification(notification.id);
            }, 5000);
        },
        
        removeNotification(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        },
        
        printReceipt() {
            if (this.completedSaleId) {
                const printUrl = `/sales/${this.completedSaleId}/print`;
                // Create a hidden iframe for printing
                const iframe = document.createElement('iframe');
                iframe.style.position = 'fixed';
                iframe.style.right = '0';
                iframe.style.bottom = '0';
                iframe.style.width = '0';
                iframe.style.height = '0';
                iframe.style.border = '0';
                iframe.src = printUrl;
                
                document.body.appendChild(iframe);
                
                // Wait for iframe to load, then trigger print
                iframe.onload = function() {
                    setTimeout(() => {
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                        // Remove iframe after printing
                        setTimeout(() => {
                            document.body.removeChild(iframe);
                        }, 1000);
                    }, 500);
                };
            }
        },
        
        closeSuccessModal() {
            this.showSuccessModal = false;
            this.completedSaleId = null;
            this.completedSaleInvoice = null;
        },
        
        resetForm() {
            this.cart = [];
            this.selectedCustomer = null;
            this.customerSearch = '';
            this.paymentMethod = 'Cash';
            this.mpesaPhoneNumber = '';
            this.transactionReference = '';
            this.selectedPendingPayment = null;
            this.pendingPaymentSearch = '';
            this.tax = 0;
            this.discount = 0;
            this.calculateTotal();
            
            // Reload pending payments to refresh the list
            this.loadPendingPayments();
            
            // Refocus barcode input
            this.$nextTick(() => {
                setTimeout(() => {
                    if (this.$refs.barcodeInput) {
                        this.$refs.barcodeInput.focus();
                    }
                }, 100);
            });
        },

        async checkout() {
            if (this.cart.length === 0) {
                this.showNotification('Cart is empty', 'error');
                return;
            }

            // Validate cart items
            for (let item of this.cart) {
                const price = Number(item.price);
                const minPrice = Number(item.min_price);
                
                if (isNaN(price) || price < minPrice) {
                    this.showNotification(`${item.name}: Price below minimum (KES ${this.formatPrice(item.min_price)})`, 'error');
                    return;
                }
                if (item.quantity > item.stock_quantity) {
                    this.showNotification(`${item.name}: Insufficient stock`, 'error');
                    return;
                }
            }

            if (!this.paymentMethod) {
                this.showNotification('Please select a payment method', 'error');
                return;
            }

            if (this.paymentMethod === 'M-Pesa') {
                // If C2B payment is selected, validate it
                if (this.selectedPendingPayment) {
                    // Validate amount match
                    const difference = Math.abs(this.selectedPendingPayment.amount - this.cartTotal.total);
                    if (difference > 0.01) {
                        if (!confirm(`Payment amount (KES ${this.formatPrice(this.selectedPendingPayment.amount)}) does not match cart total (KES ${this.formatPrice(this.cartTotal.total)}). Continue anyway?`)) {
                            return;
                        }
                    }
                } else if (!this.transactionReference) {
                    // If no C2B payment selected and no STK reference, require one
                    this.showNotification('Please select a C2B payment or initiate STK Push', 'error');
                    return;
                }
            }

            this.processing = true;

            try {
                // First create the sale
                const saleResponse = await fetch('{{ route("sales.store") }}', {
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
                        transaction_reference: this.selectedPendingPayment ? this.selectedPendingPayment.transaction_reference : (this.transactionReference || null),
                        pending_payment_id: this.selectedPendingPayment ? this.selectedPendingPayment.id : null,
                        subtotal: this.cartTotal.subtotal,
                        tax: this.cartTotal.tax,
                        discount: this.cartTotal.discount,
                        total_amount: this.cartTotal.total,
                    }),
                });

                const saleData = await saleResponse.json();

                if (saleData.success) {
                    // If C2B payment was selected, allocate it to the sale
                    if (this.selectedPendingPayment && this.paymentMethod === 'M-Pesa') {
                        try {
                            const allocateResponse = await fetch(`/pending-payments/${this.selectedPendingPayment.id}/allocate`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                },
                                body: JSON.stringify({
                                    sale_id: saleData.sale_id,
                                }),
                            });

                            const allocateData = await allocateResponse.json();
                            
                            if (!allocateData.success) {
                                console.warn('C2B allocation failed:', allocateData.message);
                                // Sale was created, but allocation failed - show warning
                                this.showNotification(`Sale created successfully, but payment allocation failed: ${allocateData.message}. Please allocate manually from pending payments.`, 'warning');
                            }
                        } catch (error) {
                            console.error('Allocation error:', error);
                            // Sale was created, but allocation failed - show warning
                            this.showNotification('Sale created successfully, but payment allocation failed. Please allocate manually from pending payments.', 'warning');
                        }
                    }
                    
                    // Store sale info for modal
                    this.completedSaleId = saleData.sale_id;
                    this.completedSaleInvoice = saleData.invoice_number;
                    
                    // Reset form
                    this.resetForm();
                    
                    // Show success modal
                    this.showSuccessModal = true;
                    
                    // Auto-open print dialog after a short delay (using iframe - no new page)
                    setTimeout(() => {
                        this.printReceipt();
                    }, 800);
                } else {
                    this.showNotification(saleData.message || 'Checkout failed', 'error');
                }
            } catch (error) {
                console.error('Checkout error:', error);
                this.showNotification('An error occurred during checkout', 'error');
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
                this.showNotification('Please enter a valid phone number', 'error');
                return;
            }

            if (this.cart.length === 0) {
                this.showNotification('Cart is empty', 'error');
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
                    this.showNotification(data.customer_message || 'STK Push initiated. Please check your phone.', 'success');
                    
                    // Poll for payment status
                    this.checkPaymentStatus(data.checkout_request_id);
                } else {
                    // Show detailed error message
                    const errorMsg = data.error || data.message || 'Failed to initiate STK Push';
                    this.showNotification('Error: ' + errorMsg + '. Please check your M-Pesa configuration.', 'error');
                }
            } catch (error) {
                console.error('STK Push error:', error);
                this.showNotification('Failed to initiate STK Push. Please check your internet connection and M-Pesa configuration.', 'error');
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
                        this.showNotification('Payment confirmed! Transaction: ' + (data.MpesaReceiptNumber || checkoutRequestId), 'success');
                    } else if (data.ResultCode && data.ResultCode != 1032) {
                        // Payment failed (1032 is still processing)
                        clearInterval(pollInterval);
                        this.showNotification('Payment failed: ' + (data.ResultDesc || 'Unknown error'), 'error');
                    }

                    if (attempts >= maxAttempts) {
                        clearInterval(pollInterval);
                        this.showNotification('Payment confirmation timeout. Please verify payment manually.', 'warning');
                    }
                } catch (error) {
                    console.error('Status check error:', error);
                }
            }, 3000);
        },

        formatPrice(price) {
            return parseFloat(price).toFixed(2);
        },

        async loadPendingPayments() {
            this.loadingPendingPayments = true;
            try {
                const response = await fetch('{{ route("pending-payments.getPending") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.pendingPayments = Array.isArray(data) ? data : (data.data || []);
                    this.filteredPendingPayments = this.pendingPayments;
                } else {
                    console.error('Failed to load pending payments');
                }
            } catch (error) {
                console.error('Error loading pending payments:', error);
            } finally {
                this.loadingPendingPayments = false;
            }
        },

        searchPendingPayments() {
            if (!this.pendingPaymentSearch || this.pendingPaymentSearch.trim().length === 0) {
                this.filteredPendingPayments = this.pendingPayments;
                return;
            }

            const search = this.pendingPaymentSearch.toLowerCase().trim();
            this.filteredPendingPayments = this.pendingPayments.filter(payment => {
                return (
                    (payment.phone_number && payment.phone_number.toLowerCase().includes(search)) ||
                    (payment.transaction_reference && payment.transaction_reference.toLowerCase().includes(search)) ||
                    (payment.account_reference && payment.account_reference.toLowerCase().includes(search)) ||
                    (payment.amount && payment.amount.toString().includes(search)) ||
                    (payment.first_name && payment.first_name.toLowerCase().includes(search)) ||
                    (payment.last_name && payment.last_name.toLowerCase().includes(search))
                );
            });
        },

        selectPendingPayment(payment) {
            this.selectedPendingPayment = payment;
            this.transactionReference = payment.transaction_reference;
            
            // Automatically set payment method to M-Pesa if C2B payment is selected
            if (this.paymentMethod !== 'M-Pesa') {
                this.paymentMethod = 'M-Pesa';
            }
            
            // Clear STK push fields when C2B payment is selected
            this.mpesaPhoneNumber = '';
        },

        toggleCompatibility(product) {
            if (this.showCompatibility && this.compatibilityProduct && this.compatibilityProduct.id === product.id) {
                this.showCompatibility = false;
                this.compatibilityProduct = null;
            } else {
                this.compatibilityProduct = product;
                this.showCompatibility = true;
            }
        }
    }
}
</script>
@endsection
