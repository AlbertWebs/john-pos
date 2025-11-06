@extends('layouts.ecommerce')

@section('title', 'Shopping Cart')

@php
    $settings = $settings ?? \Illuminate\Support\Facades\DB::table('settings')->pluck('value', 'key')->toArray();
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

    @if(count($cartItems) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2 space-y-4">
            @foreach($cartItems as $item)
            <div class="bg-white rounded-lg shadow-md p-6" id="cart-item-{{ $item['id'] }}">
                <div class="flex gap-4">
                    <!-- Product Image -->
                    <div class="w-24 h-24 flex-shrink-0">
                        @if($item['image'])
                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover rounded">
                        @else
                        <div class="w-full h-full bg-gray-200 rounded flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @endif
                    </div>

                    <!-- Product Details -->
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 mb-1">{{ $item['name'] }}</h3>
                        <p class="text-sm text-gray-500 mb-2">Part #: {{ $item['part_number'] }}</p>
                        <p class="text-lg font-bold text-blue-600 mb-4">KES {{ number_format($item['price'], 2) }}</p>
                        
                        <div class="flex items-center gap-4">
                            <label class="text-sm text-gray-700">Quantity:</label>
                            <input 
                                type="number" 
                                value="{{ $item['quantity'] }}" 
                                min="1" 
                                max="{{ $item['stock_available'] }}"
                                onchange="updateCart({{ $item['id'] }}, this.value)"
                                class="w-20 px-3 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                            <span class="text-sm text-gray-500">Stock: {{ $item['stock_available'] }}</span>
                        </div>
                    </div>

                    <!-- Subtotal and Remove -->
                    <div class="text-right">
                        <p class="text-lg font-bold text-gray-900 mb-4" data-subtotal>KES {{ number_format($item['subtotal'], 2) }}</p>
                        <button 
                            onclick="removeFromCart({{ $item['id'] }})"
                            class="text-red-600 hover:text-red-800 text-sm font-medium"
                        >
                            Remove
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Order Summary</h2>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-gray-700">
                        <span>Subtotal:</span>
                        <span class="font-semibold" data-cart-subtotal>KES {{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Tax:</span>
                        <span class="font-semibold">KES 0.00</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Shipping:</span>
                        <span class="font-semibold">Free</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between text-lg font-bold text-gray-900">
                        <span>Total:</span>
                        <span data-cart-total>KES {{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <a href="{{ route('ecommerce.checkout') }}" class="block w-full bg-blue-600 text-white text-center px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold mb-4">
                    Proceed to Checkout
                </a>
                
                <a href="{{ route('ecommerce.products') }}" class="block w-full text-center text-blue-600 hover:text-blue-800 font-medium">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <h3 class="text-xl font-medium text-gray-900 mb-2">Your cart is empty</h3>
        <p class="text-gray-500 mb-6">Start shopping to add items to your cart</p>
        <a href="{{ route('ecommerce.products') }}" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
            Browse Products
        </a>
    </div>
    @endif
</div>

<script>
// Override updateCart for cart page to update DOM
function updateCartItemOnPage(productId, quantity) {
    if (quantity < 1) {
        removeFromCart(productId);
        return;
    }

    fetch('{{ route("ecommerce.cart.update") }}', {
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
            // Update item subtotal
            const cartItem = document.getElementById('cart-item-' + productId);
            if (cartItem && data.item) {
                const subtotalEl = cartItem.querySelector('.text-lg.font-bold.text-gray-900');
                if (subtotalEl) {
                    subtotalEl.textContent = 'KES ' + parseFloat(data.item.subtotal).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                }
            }
            // Update order summary
            updateOrderSummaryOnPage(data.cart_total);
            // Update cart count and total in header (using global function)
            if (window.updateCartCount) {
                updateCartCount(data.cart_count);
            }
            if (window.updateCartTotal) {
                updateCartTotal(data.cart_total);
            }
            if (window.updateCartDropdown) {
                updateCartDropdown();
            }
            if (window.showNotification) {
                showNotification('Cart updated!', 'success');
            }
        } else {
            if (window.showNotification) {
                showNotification(data.message || 'Failed to update cart', 'error');
            } else {
                alert(data.message || 'Failed to update cart');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (window.showNotification) {
            showNotification('An error occurred. Please try again.', 'error');
        } else {
            alert('An error occurred. Please try again.');
        }
    });
}

// Override removeFromCart for cart page
function removeFromCartOnPage(productId) {
    if (!confirm('Are you sure you want to remove this item from your cart?')) {
        return;
    }

    fetch('{{ route("ecommerce.cart.remove") }}', {
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
            // Remove item from DOM
            const cartItem = document.getElementById('cart-item-' + productId);
            if (cartItem) {
                cartItem.style.transition = 'opacity 0.3s';
                cartItem.style.opacity = '0';
                setTimeout(() => {
                    cartItem.remove();
                    // Check if cart is empty
                    if (document.querySelectorAll('[id^="cart-item-"]').length === 0) {
                        location.reload();
                    } else {
                        // Update order summary
                        updateOrderSummaryOnPage(data.cart_total);
                    }
                }, 300);
            }
            // Update cart count and total in header
            if (window.updateCartCount) {
                updateCartCount(data.cart_count);
            }
            if (window.updateCartTotal) {
                updateCartTotal(data.cart_total);
            }
            if (window.updateCartDropdown) {
                updateCartDropdown();
            }
            if (window.showNotification) {
                showNotification('Item removed from cart', 'success');
            }
        } else {
            if (window.showNotification) {
                showNotification(data.message || 'Failed to remove item', 'error');
            } else {
                alert(data.message || 'Failed to remove item');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (window.showNotification) {
            showNotification('An error occurred. Please try again.', 'error');
        } else {
            alert('An error occurred. Please try again.');
        }
    });
}

// Update order summary on cart page
function updateOrderSummaryOnPage(total) {
    const totalEl = document.querySelector('[data-cart-total]');
    if (totalEl) {
        totalEl.textContent = 'KES ' + parseFloat(total).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
    // Also update the subtotal if it exists
    const subtotalEl = document.querySelector('[data-cart-subtotal]');
    if (subtotalEl) {
        subtotalEl.textContent = 'KES ' + parseFloat(total).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
}

// Override the global functions for cart page
window.updateCart = function(productId, quantity) {
    updateCartItemOnPage(productId, quantity);
};

window.removeFromCart = function(productId) {
    removeFromCartOnPage(productId);
};
</script>
@endsection

