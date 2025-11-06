@extends('layouts.ecommerce')

@section('title', $product->name)

@php
    $settings = $settings ?? \Illuminate\Support\Facades\DB::table('settings')->pluck('value', 'key')->toArray();
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Product Image -->
        <div class="bg-white rounded-lg shadow-md p-6">
            @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-auto rounded-lg">
            @else
            <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            @endif
        </div>

        <!-- Product Details -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
            
            <div class="space-y-4 mb-6">
                <div>
                    <span class="text-sm text-gray-500">Part Number:</span>
                    <span class="text-sm font-medium text-gray-900 ml-2">{{ $product->part_number }}</span>
                </div>
                
                @if($product->brand)
                <div>
                    <span class="text-sm text-gray-500">Brand:</span>
                    <span class="text-sm font-medium text-gray-900 ml-2">{{ $product->brand->brand_name }}</span>
                </div>
                @endif
                
                @if($product->category)
                <div>
                    <span class="text-sm text-gray-500">Category:</span>
                    <span class="text-sm font-medium text-gray-900 ml-2">{{ $product->category->name }}</span>
                </div>
                @endif
                
                @if($product->vehicleMake || $product->vehicleModel)
                <div>
                    <span class="text-sm text-gray-500">Vehicle:</span>
                    <span class="text-sm font-medium text-gray-900 ml-2">
                        @if($product->vehicleMake){{ $product->vehicleMake->make_name }}@endif
                        @if($product->vehicleModel) - {{ $product->vehicleModel->model_name }}@endif
                    </span>
                </div>
                @endif
            </div>

            <div class="mb-6">
                <div class="flex items-center gap-4 mb-4">
                    <span class="text-4xl font-bold text-blue-600">KES {{ number_format($product->selling_price, 2) }}</span>
                    @if($product->stock_quantity > 0)
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                        In Stock ({{ $product->stock_quantity }} available)
                    </span>
                    @else
                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                        Out of Stock
                    </span>
                    @endif
                </div>
            </div>

            @if($product->description)
            <div class="mb-6 border-t pt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Description</h2>
                <div class="text-gray-700 prose max-w-none">
                    {!! $product->description !!}
                </div>
            </div>
            @endif

            @if($product->stock_quantity > 0)
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                <div class="flex items-center gap-4">
                    <input 
                        type="number" 
                        id="quantity" 
                        value="1" 
                        min="1" 
                        max="{{ $product->stock_quantity }}"
                        class="w-20 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    <button 
                        onclick="addToCart({{ $product->id }})"
                        class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold"
                    >
                        Add to Cart
                    </button>
                </div>
            </div>
            @else
            <div class="bg-gray-100 rounded-lg p-4 text-center">
                <p class="text-gray-600">This product is currently out of stock.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $relatedProduct)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                <a href="{{ route('ecommerce.product', $relatedProduct->id) }}">
                    <div class="aspect-w-1 aspect-h-1 bg-gray-100">
                        @if($relatedProduct->image)
                        <img src="{{ asset('storage/' . $relatedProduct->image) }}" alt="{{ $relatedProduct->name }}" class="w-full h-48 object-cover">
                        @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $relatedProduct->name }}</h3>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-blue-600">KES {{ number_format($relatedProduct->selling_price, 2) }}</span>
                            @if($relatedProduct->stock_quantity > 0)
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">In Stock</span>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
function addToCart(productId) {
    const quantity = document.getElementById('quantity') ? parseInt(document.getElementById('quantity').value) : 1;
    
    fetch('{{ route("ecommerce.cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Product added to cart!');
            // Update cart count if available
            if (window.ecommerceLayout) {
                window.ecommerceLayout.cartCount = data.cart_count;
            }
        } else {
            alert(data.message || 'Failed to add product to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}
</script>
@if(isset($structuredData))
<script type="application/ld+json">
{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif
@endsection

