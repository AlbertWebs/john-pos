@extends('layouts.ecommerce')

@section('title', 'Products')

@php
    $settings = $settings ?? \Illuminate\Support\Facades\DB::table('settings')->pluck('value', 'key')->toArray();
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">All Products</h1>
        <p class="text-gray-600">Browse our complete catalog of auto parts</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Filters Sidebar -->
        <aside class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                <h2 class="text-lg font-semibold mb-4">Filters</h2>
                
                <form method="GET" action="{{ route('ecommerce.products') }}" class="space-y-6">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search products..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Brand Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                        <select name="brand" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Brands</option>
                            @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->brand_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- In Stock Filter -->
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="in_stock" 
                                value="1"
                                {{ request('in_stock') ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            >
                            <span class="text-sm text-gray-700">In Stock Only</span>
                        </label>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                            Apply Filters
                        </button>
                        <a href="{{ route('ecommerce.products') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            Clear
                        </a>
                    </div>
                </form>
            </div>
        </aside>

        <!-- Products Grid -->
        <div class="lg:col-span-3">
            @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    <a href="{{ route('ecommerce.product', $product->id) }}">
                        <div class="aspect-w-1 aspect-h-1 bg-gray-100">
                            @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                            @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $product->name }}</h3>
                            <p class="text-xs text-gray-500 mb-2">Part #: {{ $product->part_number }}</p>
                            @if($product->brand)
                            <p class="text-sm text-gray-500 mb-2">{{ $product->brand->brand_name }}</p>
                            @endif
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-blue-600">KES {{ number_format($product->selling_price, 2) }}</span>
                                @if($product->stock_quantity > 0)
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">In Stock</span>
                                @else
                                <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Out of Stock</span>
                                @endif
                            </div>
                        </div>
                    </a>
                    @if($product->stock_quantity > 0)
                    <div class="px-4 pb-4">
                        <button 
                            onclick="addToCart({{ $product->id }})"
                            class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-medium"
                        >
                            Add to Cart
                        </button>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
            @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
                <p class="text-gray-500">Try adjusting your filters or search terms.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function addToCart(productId) {
    fetch('{{ route("ecommerce.cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Product added to cart!');
            // Reload page to update cart count
            window.location.reload();
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
@endsection

