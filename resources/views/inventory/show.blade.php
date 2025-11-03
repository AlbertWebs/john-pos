@extends('layouts.app')

@section('title', 'Inventory Details')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('inventory.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Inventory
        </a>
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $inventory->name }}</h1>
                <p class="text-gray-600 mt-1">Part Number: {{ $inventory->part_number }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('inventory.edit', $inventory) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Part Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $inventory->part_number }}</dd>
                    </div>
                    @if($inventory->sku)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">SKU</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $inventory->sku }}</dd>
                    </div>
                    @endif
                    @if($inventory->category)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Category</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ $inventory->category->name }}
                            </span>
                        </dd>
                    </div>
                    @endif
                    @if($inventory->brand)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Brand</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $inventory->brand->brand_name }}</dd>
                    </div>
                    @endif
                    @if($inventory->vehicleMake)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Vehicle Make</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $inventory->vehicleMake->make_name }}</dd>
                    </div>
                    @endif
                    @if($inventory->vehicleModel)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Vehicle Model</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $inventory->vehicleModel->model_name }}</dd>
                    </div>
                    @endif
                    @if($inventory->year_range)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Year Range</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $inventory->year_range }}</dd>
                    </div>
                    @endif
                    @if($inventory->location)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Location</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $inventory->location }}</dd>
                    </div>
                    @endif
                </dl>
                @if($inventory->description)
                <div class="mt-4">
                    <dt class="text-sm font-medium text-gray-500 mb-1">Description</dt>
                    <dd class="text-sm text-gray-900">{{ $inventory->description }}</dd>
                </div>
                @endif
            </div>

            <!-- Pricing Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Pricing & Stock</h2>
                <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Cost Price</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">KES {{ number_format($inventory->cost_price, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Min Price</dt>
                        <dd class="mt-1 text-lg font-semibold text-red-600">KES {{ number_format($inventory->min_price, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Selling Price</dt>
                        <dd class="mt-1 text-lg font-semibold text-green-600">KES {{ number_format($inventory->selling_price, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Stock Quantity</dt>
                        <dd class="mt-1 text-lg font-semibold {{ $inventory->isLowStock() ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $inventory->stock_quantity }}
                            @if($inventory->isLowStock())
                            <svg class="inline-block w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Reorder Level</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $inventory->reorder_level }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $inventory->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($inventory->status) }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Price History -->
            @if($inventory->priceHistories->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Price History</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Old Price</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">New Price</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Changed By</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($inventory->priceHistories as $history)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">KES {{ number_format($history->old_price, 2) }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-green-600">KES {{ number_format($history->new_price, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $history->changedBy->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $history->changed_at->format('M d, Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('inventory.edit', $inventory) }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-center font-medium transition">
                        Edit Item
                    </a>
                    @if($inventory->saleItems->count() == 0)
                    <form method="POST" action="{{ route('inventory.destroy', $inventory) }}" onsubmit="return confirm('Are you sure you want to delete this item?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="block w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg text-center font-medium transition">
                            Delete Item
                        </button>
                    </form>
                    @else
                    <button disabled class="block w-full bg-gray-300 text-gray-500 py-2 px-4 rounded-lg text-center font-medium cursor-not-allowed">
                        Cannot Delete (Used in Sales)
                    </button>
                    @endif
                </div>
            </div>

            <!-- Stock Alert -->
            @if($inventory->isLowStock())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center gap-2">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-red-900">Low Stock Alert</h4>
                        <p class="text-sm text-red-700">Stock is below reorder level. Consider restocking.</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

