@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
            <p class="text-gray-600">Order details and management</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" 
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
            Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Order Number</p>
                        <p class="font-medium">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Order Date</p>
                        <p class="font-medium" data-utc-time="{{ $order->created_at->toISOString() }}">
                            {{ $order->created_at->format('F j, Y \a\t g:i A') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Amount</p>
                        <p class="font-medium text-lg text-green-600">KES {{ number_format($order->total_amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Payment Method</p>
                        <p class="font-medium">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</p>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h2>
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $item->product->main_image_url }}" alt="{{ $item->product->name }}" 
                                     class="w-16 h-16 object-cover rounded-lg">
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                                    <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                                    <p class="text-sm text-gray-600">Price: KES {{ number_format($item->price, 2) }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">KES {{ number_format($item->total, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Name</p>
                        <p class="font-medium">{{ $order->user->name ?? 'Guest' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium">{{ $order->user->email ?? 'guest@example.com' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Phone</p>
                        <p class="font-medium">{{ $order->phone }}</p>
                    </div>
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Delivery Information</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Shipping Address</p>
                        <p class="font-medium">{{ $order->shipping_address }}</p>
                    </div>
                    @if($order->notes)
                        <div>
                            <p class="text-sm text-gray-600">Delivery Notes</p>
                            <p class="font-medium">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status Management -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Management</h2>
                
                <!-- Order Status -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="flex space-x-2">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Update
                        </button>
                    </form>
                </div>

                <!-- Payment Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                    <form method="POST" action="{{ route('admin.orders.update-payment-status', $order) }}" class="flex space-x-2">
                        @csrf
                        @method('PATCH')
                        <select name="payment_status" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            Update
                        </button>
                    </form>
                </div>
            </div>

            <!-- Current Status -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Current Status</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Order Status</p>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $order->status_color }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Payment Status</p>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $order->payment_status_color }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Convert UTC times to local timezone
    const timeElements = document.querySelectorAll('[data-utc-time]');
    
    timeElements.forEach(function(element) {
        const utcTime = element.getAttribute('data-utc-time');
        if (utcTime) {
            const localTime = new Date(utcTime);
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            };
            element.textContent = localTime.toLocaleDateString('en-US', options);
        }
    });
});
</script>
@endpush
@endsection 