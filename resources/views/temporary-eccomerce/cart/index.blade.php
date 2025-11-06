@extends('layouts.app')

@php
use App\Models\Setting;
@endphp

@section('content')
    <!-- Page Header -->
    <section class="bg-gray-50 py-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Shopping Basket</h1>
                    <p class="text-gray-600 mt-2">Review your items and proceed to checkout</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if(count($cartItems) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Basket Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Basket Items ({{ count($cartItems) }})</h2>
                        </div>
                        
                        <div class="divide-y divide-gray-200">
                            @foreach($cartItems as $item)
                                <div class="p-6 flex items-center space-x-4">
                                    <img src="{{ $item['product']->image }}" alt="{{ $item['product']->name }}" class="w-20 h-20 object-cover rounded">
                                    
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $item['product']->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $item['product']->category->name }}</p>
                                        <div class="flex items-center mt-2">
                                            <span class="text-lg font-bold text-gray-900">{{ $item['product']->formatted_price }}</span>
                                            @if($item['product']->old_price && $item['product']->old_price > $item['product']->price)
                                                <span class="ml-2 text-sm text-gray-500 line-through">{{ $item['product']->formatted_old_price }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center border border-gray-300 rounded-lg">
                                            <button onclick="updateCartQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})" 
                                                    class="px-3 py-2 text-gray-600 hover:text-gray-800 transition-colors" 
                                                    {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                                <i class="fas fa-minus text-xs"></i>
                                            </button>
                                            <span class="px-3 py-2 text-gray-900 font-medium">{{ $item['quantity'] }}</span>
                                            <button onclick="updateCartQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})" 
                                                    class="px-3 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                                                <i class="fas fa-plus text-xs"></i>
                                            </button>
                                        </div>
                                        
                                        <div class="text-right">
                                            <div class="text-lg font-bold text-gray-900">KES {{ number_format($item['subtotal'], 2) }}</div>
                                            <button onclick="removeFromCart({{ $item['id'] }})" 
                                                    class="text-red-500 hover:text-red-700 text-sm transition-colors">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="p-6 border-t border-gray-200">
                            <button onclick="clearCart()" class="text-red-600 hover:text-red-700 font-medium transition-colors">
                                Clear Basket
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Checkout Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Checkout</h2>
                        
                        <!-- Order Summary -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="text-gray-900">KES {{ number_format($total, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Delivery</span>
                                    <span class="text-gray-500 text-sm">Shipping fee may apply</span>
                                </div>
                                <div class="border-t border-gray-200 pt-2">
                                    <div class="flex justify-between font-semibold">
                                        <span class="text-gray-900">Total</span>
                                        <span class="text-gray-900">KES {{ number_format($total, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Details Form -->
                        <form action="{{ route('cart.checkout') }}" method="POST" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                <input type="text" id="name" name="name" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       value="{{ old('name') }}">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                                <input type="email" id="email" name="email" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       value="{{ old('email') }}">
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                                <input type="tel" id="phone" name="phone" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       value="{{ old('phone') }}" placeholder="{{ Setting::get('contact_phone', '+254 700 123 456') }}">
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Delivery Address *</label>
                                <textarea id="address" name="address" rows="3" required 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Enter your full delivery address">{{ old('address') }}</textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                                    <input type="text" id="city" name="city" required 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           value="{{ old('city') }}">
                                </div>

                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal Code *</label>
                                    <input type="text" id="postal_code" name="postal_code" required 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           value="{{ old('postal_code') }}">
                                </div>
                            </div>

                            <div>
                                <label for="delivery_notes" class="block text-sm font-medium text-gray-700 mb-1">Delivery Notes</label>
                                <textarea id="delivery_notes" name="delivery_notes" rows="2" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Any special delivery instructions...">{{ old('delivery_notes') }}</textarea>
                            </div>

                            <button type="submit" 
                                    class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                Place Order - KES {{ number_format($total, 2) }}
                            </button>
                        </form>

                        <!-- Payment Info -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Payment Information</h4>
                            <p class="text-sm text-gray-600 mb-2">
                                Payment will be collected upon delivery. We accept cash and mobile money payments.
                            </p>
                            <p class="text-sm text-gray-600">
                                <strong>Note:</strong> Shipping fees may apply depending on your location and order value. Final delivery cost will be confirmed during order processing.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Basket -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m6 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Your basket is empty</h3>
                <p class="mt-1 text-sm text-gray-500">Start shopping to add items to your basket.</p>
                <div class="mt-6">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Continue Shopping
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection 