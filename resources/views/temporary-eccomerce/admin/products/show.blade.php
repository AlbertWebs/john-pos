@extends('layouts.admin')

@section('title', 'Product Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
            <p class="text-gray-600">Product details and information</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.products.edit', $product) }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Edit Product
            </a>
            <a href="{{ route('admin.products.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                Back to Products
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Product Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Name</p>
                        <p class="font-medium">{{ $product->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Category</p>
                        <p class="font-medium">{{ $product->category->name ?? 'Uncategorized' }}</p>
                    </div>
                    @if($product->brand)
                        <div>
                            <p class="text-sm text-gray-600">Brand</p>
                            <p class="font-medium">{{ $product->brand }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600">Price</p>
                        <p class="font-medium text-lg text-green-600">{{ $product->formatted_price }}</p>
                    </div>
                    @if($product->old_price)
                        <div>
                            <p class="text-sm text-gray-600">Old Price</p>
                            <p class="font-medium text-gray-500 line-through">{{ $product->formatted_old_price }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600">Stock Quantity</p>
                        <p class="font-medium {{ $product->stock_quantity < 10 ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $product->stock_quantity }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Description</h2>
                <div class="prose max-w-none">
                    <p class="text-gray-700">{{ $product->description }}</p>
                </div>
            </div>

            <!-- Specifications -->
            @if($product->specifications && count($product->specifications) > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Specifications</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($product->specifications as $key => $value)
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-700">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                <span class="text-gray-600">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Images -->
            @if($product->image || $product->images)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Images</h2>
                    <div class="space-y-4">
                        @if($product->image)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Main Image</label>
                                <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" 
                                     class="w-48 h-48 object-cover rounded-lg border">
                            </div>
                        @endif

                        @if($product->getValidImages() && count($product->getValidImages()) > 0)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Images</label>
                                <div class="flex space-x-2">
                                    @foreach($product->getValidImages() as $image)
                                        <img src="{{ $product->getImageUrlAttribute($image) }}" alt="{{ $product->name }}" 
                                             class="w-24 h-24 object-cover rounded-lg border">
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Additional Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Additional Details</h2>
                <div class="space-y-3">
                    @if($product->badge)
                        <div>
                            <p class="text-sm text-gray-600">Badge</p>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $product->badge }}
                            </span>
                        </div>
                    @endif

                    @if($product->rating)
                        <div>
                            <p class="text-sm text-gray-600">Rating</p>
                            <div class="flex items-center">
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $product->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="ml-2 text-sm text-gray-600">({{ $product->rating }}/5)</span>
                            </div>
                        </div>
                    @endif

                    @if($product->reviews_count)
                        <div>
                            <p class="text-sm text-gray-600">Reviews Count</p>
                            <p class="font-medium">{{ $product->reviews_count }}</p>
                        </div>
                    @endif

                    <div>
                        <p class="text-sm text-gray-600">Featured</p>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $product->is_featured ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $product->is_featured ? 'Yes' : 'No' }}
                        </span>
                    </div>

                    @if($product->discount_percentage > 0)
                        <div>
                            <p class="text-sm text-gray-600">Discount</p>
                            <p class="font-medium text-red-600">{{ $product->discount_percentage }}% OFF</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Stats -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Stats</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Created</p>
                        <p class="font-medium">{{ $product->created_at->format('M j, Y g:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Last Updated</p>
                        <p class="font-medium">{{ $product->updated_at->format('M j, Y g:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Slug</p>
                        <p class="font-medium text-sm text-gray-500">{{ $product->slug }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                <div class="space-y-2">
                    <a href="{{ route('admin.products.edit', $product) }}" 
                       class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Edit Product
                    </a>
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this product?')"
                                class="block w-full text-center bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                            Delete Product
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 