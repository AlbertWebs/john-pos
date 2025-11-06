@extends('layouts.ecommerce')

@section('title', 'Checkout')

@php
    $settings = $settings ?? \Illuminate\Support\Facades\DB::table('settings')->pluck('value', 'key')->toArray();
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

    <form id="checkout-form" onsubmit="placeOrder(event)">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Checkout Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Customer Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email (Optional)</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Delivery Address *</label>
                            <textarea 
                                id="address" 
                                name="address" 
                                rows="3" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Information (Optional) -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Vehicle Information (Optional)</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="vehicle_make_id" class="block text-sm font-medium text-gray-700 mb-2">Vehicle Make</label>
                            <select 
                                id="vehicle_make_id" 
                                name="vehicle_make_id"
                                onchange="loadVehicleModels(this.value)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="">Select Make</option>
                                @foreach($vehicleMakes as $make)
                                <option value="{{ $make->id }}">{{ $make->make_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="vehicle_model_id" class="block text-sm font-medium text-gray-700 mb-2">Vehicle Model</label>
                            <select 
                                id="vehicle_model_id" 
                                name="vehicle_model_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="">Select Model</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Payment Method</h2>
                    
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition">
                            <input type="radio" name="payment_method" value="M-Pesa" checked class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <div class="flex-1">
                                <span class="font-semibold text-gray-900">M-Pesa</span>
                                <p class="text-sm text-gray-500">Pay via M-Pesa mobile money</p>
                            </div>
                        </label>
                        
                        <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition">
                            <input type="radio" name="payment_method" value="Cash" class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <div class="flex-1">
                                <span class="font-semibold text-gray-900">Cash on Delivery</span>
                                <p class="text-sm text-gray-500">Pay when you receive your order</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Order Summary</h2>
                    
                    <div class="space-y-3 mb-6">
                        @foreach($cartItems as $item)
                        <div class="flex justify-between text-sm text-gray-700">
                            <span>{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                            <span>KES {{ number_format($item['subtotal'], 2) }}</span>
                        </div>
                        @endforeach
                        
                        <div class="border-t pt-3 space-y-2">
                            <div class="flex justify-between text-gray-700">
                                <span>Subtotal:</span>
                                <span class="font-semibold">KES {{ number_format($total, 2) }}</span>
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
                                <span>KES {{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <button 
                        type="submit"
                        class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold mb-4"
                    >
                        Place Order
                    </button>
                    
                    <a href="{{ route('ecommerce.cart') }}" class="block w-full text-center text-blue-600 hover:text-blue-800 font-medium">
                        Back to Cart
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function loadVehicleModels(makeId) {
    if (!makeId) {
        document.getElementById('vehicle_model_id').innerHTML = '<option value="">Select Model</option>';
        return;
    }

    fetch(`{{ url('/shop/api/vehicle-models') }}/${makeId}`)
        .then(response => response.json())
        .then(models => {
            const select = document.getElementById('vehicle_model_id');
            select.innerHTML = '<option value="">Select Model</option>';
            models.forEach(model => {
                const option = document.createElement('option');
                option.value = model.id;
                option.textContent = model.model_name;
                select.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading vehicle models:', error);
        });
}

function placeOrder(event) {
    event.preventDefault();
    
    const form = document.getElementById('checkout-form');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    
    // Show loading state
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    submitButton.disabled = true;
    submitButton.textContent = 'Processing...';
    
    fetch('{{ route("ecommerce.order") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // If M-Pesa payment, show message and redirect
            if (data.payment_method === 'M-Pesa' && data.customer_message) {
                alert(data.customer_message + '\n\nPlease check your phone to complete the payment.');
            }
            window.location.href = data.redirect_url;
        } else {
            alert(data.message || 'Failed to place order. Please try again.');
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    });
}
</script>
@endsection

