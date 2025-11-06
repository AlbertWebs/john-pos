@extends('layouts.ecommerce')

@section('title', 'Order Confirmation')

@php
    $settings = $settings ?? \Illuminate\Support\Facades\DB::table('settings')->pluck('value', 'key')->toArray();
@endphp

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <!-- Success Icon -->
        <div class="mb-6">
            <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-4">Order Placed Successfully!</h1>
        <p class="text-gray-600 mb-8">Thank you for your order. We'll process it shortly.</p>

        <!-- Order Details -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Order Information</h3>
                    <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Invoice Number:</span> {{ $sale->invoice_number }}</p>
                    <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Order Date:</span> {{ $sale->date->format('F d, Y') }}</p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Payment Status:</span> 
                        <span class="px-2 py-1 rounded text-xs font-medium 
                            {{ $sale->payment_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($sale->payment_status) }}
                        </span>
                    </p>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Customer Information</h3>
                    <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Name:</span> {{ $sale->customer->name }}</p>
                    <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Phone:</span> {{ $sale->customer->phone }}</p>
                    @if($sale->customer->email)
                    <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Email:</span> {{ $sale->customer->email }}</p>
                    @endif
                    <p class="text-sm text-gray-600"><span class="font-medium">Address:</span> {{ $sale->customer->address }}</p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Order Items</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($sale->saleItems as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item->part->name }}</div>
                                <div class="text-sm text-gray-500">Part #: {{ $item->part->part_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">KES {{ number_format($item->price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">KES {{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Total:</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">KES {{ number_format($sale->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Payment Information -->
        @php
            $mpesaPayment = $sale->payments->where('payment_method', 'M-Pesa')->first();
        @endphp
        @if($sale->payment_status === 'pending' && $mpesaPayment)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8" id="payment-status-section">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-yellow-900 mb-2">M-Pesa Payment Pending</h3>
                    <p class="text-sm text-yellow-800 mb-4">Please check your phone and complete the M-Pesa payment. Your order will be processed once payment is confirmed.</p>
                    <div class="flex gap-3">
                        <button 
                            onclick="checkPaymentStatus()" 
                            id="check-payment-btn"
                            class="bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700 transition font-medium text-sm"
                        >
                            Check Payment Status
                        </button>
                        <div id="payment-status-message" class="hidden"></div>
                    </div>
                </div>
            </div>
        </div>
        @elseif($sale->payment_status === 'completed' && $mpesaPayment)
        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-green-900 mb-2">Payment Completed</h3>
                    <p class="text-sm text-green-800">Your M-Pesa payment has been confirmed. Your order is being processed.</p>
                    @if($mpesaPayment->transaction_reference)
                    <p class="text-sm text-green-700 mt-2">Transaction Reference: <span class="font-mono">{{ $mpesaPayment->transaction_reference }}</span></p>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex gap-4 justify-center">
            <a href="{{ route('ecommerce.products') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                Continue Shopping
            </a>
        </div>
    </div>
</div>

@if($sale->payment_status === 'pending' && $mpesaPayment)
<script>
let checkingPayment = false;

function checkPaymentStatus() {
    if (checkingPayment) return;
    
    checkingPayment = true;
    const btn = document.getElementById('check-payment-btn');
    const messageDiv = document.getElementById('payment-status-message');
    const originalText = btn.textContent;
    
    btn.disabled = true;
    btn.textContent = 'Checking...';
    messageDiv.classList.add('hidden');
    
    fetch('{{ route("ecommerce.payment-status", $sale->id) }}', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        checkingPayment = false;
        btn.disabled = false;
        btn.textContent = originalText;
        
        if (data.success) {
            if (data.payment_status === 'completed') {
                messageDiv.className = 'text-green-600 font-medium text-sm flex items-center gap-2';
                messageDiv.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Payment Completed!';
                messageDiv.classList.remove('hidden');
                
                // Reload page after 2 seconds to show updated status
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                messageDiv.className = 'text-yellow-600 font-medium text-sm';
                messageDiv.textContent = data.message || 'Payment is still pending';
                messageDiv.classList.remove('hidden');
            }
        } else {
            messageDiv.className = 'text-red-600 font-medium text-sm';
            messageDiv.textContent = data.message || 'Error checking payment status';
            messageDiv.classList.remove('hidden');
        }
    })
    .catch(error => {
        checkingPayment = false;
        btn.disabled = false;
        btn.textContent = originalText;
        messageDiv.className = 'text-red-600 font-medium text-sm';
        messageDiv.textContent = 'Error checking payment status. Please try again.';
        messageDiv.classList.remove('hidden');
        console.error('Error:', error);
    });
}

// Auto-check payment status every 10 seconds if payment is pending
let paymentCheckInterval = null;
@if($sale->payment_status === 'pending' && $mpesaPayment)
paymentCheckInterval = setInterval(() => {
    checkPaymentStatus();
}, 10000);

// Stop checking after 5 minutes
setTimeout(() => {
    if (paymentCheckInterval) {
        clearInterval(paymentCheckInterval);
        paymentCheckInterval = null;
    }
}, 300000);
@endif
</script>
@endif
@endsection

