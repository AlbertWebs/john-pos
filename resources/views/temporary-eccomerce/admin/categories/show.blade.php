@extends('layouts.admin')

@section('title', 'Category Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
            <p class="text-gray-600">Category details and products</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.categories.edit', $category) }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Edit Category
            </a>
            <a href="{{ route('admin.categories.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                Back to Categories
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Category Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Category Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Name</p>
                        <p class="font-medium">{{ $category->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Slug</p>
                        <p class="font-medium text-sm text-gray-500">{{ $category->slug }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Sort Order</p>
                        <p class="font-medium">{{ $category->sort_order ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($category->description)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Description</h2>
                    <div class="prose max-w-none">
                        <p class="text-gray-700">{{ $category->description }}</p>
                    </div>
                </div>
            @endif

            <!-- Products in this Category -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Products in this Category ({{ $category->products->count() }})</h2>
                
                @if($category->products->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($category->products as $product)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center space-x-3">
                                    @if($product->image)
                                        <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" 
                                             class="w-12 h-12 object-cover rounded">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h3 class="font-medium text-gray-900">{{ $product->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $product->formatted_price }}</p>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-box-open text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">No products in this category yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Category Stats -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Category Stats</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Total Products</p>
                        <p class="font-medium text-lg">{{ $category->products->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Active Products</p>
                        <p class="font-medium">{{ $category->products->where('is_active', true)->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Featured Products</p>
                        <p class="font-medium">{{ $category->products->where('is_featured', true)->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Low Stock Products</p>
                        <p class="font-medium text-red-600">{{ $category->products->where('stock_quantity', '<', 10)->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Category Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Category Details</h2>
                <div class="space-y-3">
                    @if($category->icon)
                        <div>
                            <p class="text-sm text-gray-600">Icon</p>
                            <div class="flex items-center">
                                <i class="{{ $category->icon }} text-gray-400 mr-2"></i>
                                <span class="text-sm text-gray-500">{{ $category->icon }}</span>
                            </div>
                        </div>
                    @endif

                    <div>
                        <p class="text-sm text-gray-600">Created</p>
                        <p class="font-medium">{{ $category->created_at->format('M j, Y g:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Last Updated</p>
                        <p class="font-medium">{{ $category->updated_at->format('M j, Y g:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                <div class="space-y-2">
                    <a href="{{ route('admin.categories.edit', $category) }}" 
                       class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Edit Category
                    </a>
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this category? This will also affect all products in this category.')"
                                class="block w-full text-center bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                            Delete Category
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 