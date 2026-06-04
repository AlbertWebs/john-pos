@extends('layouts.app')

@section('title', 'Add Stock')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('inventory.show', $inventory) }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Item
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Add Stock</h1>
        <p class="text-gray-600 mt-1">Increase stock for an existing product. Each receipt is recorded with a date for reporting.</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-3">Product</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-gray-500">Name</dt>
                <dd class="font-medium text-gray-900">{{ $inventory->name }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Part number</dt>
                <dd class="font-medium text-gray-900">{{ $inventory->part_number }}</dd>
            </div>
            @if($inventory->sku)
            <div>
                <dt class="text-gray-500">SKU</dt>
                <dd class="font-medium text-gray-900">{{ $inventory->sku }}</dd>
            </div>
            @endif
            <div>
                <dt class="text-gray-500">Current stock</dt>
                <dd class="font-semibold text-lg {{ $inventory->isLowStock() ? 'text-red-600' : 'text-gray-900' }}">
                    {{ $inventory->stock_quantity }}
                </dd>
            </div>
        </dl>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form method="POST" action="{{ route('inventory.add-stock.store', $inventory) }}">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                        Quantity to add <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        name="quantity"
                        id="quantity"
                        value="{{ old('quantity', 1) }}"
                        min="1"
                        step="1"
                        required
                        autofocus
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('quantity') border-red-500 @enderror"
                    >
                    @error('quantity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    @include('partials.supply-select', ['supplies' => $supplies, 'selected' => old('supply_id', $inventory->supply_id)])
                </div>

                <div>
                    <label for="received_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Date received
                    </label>
                    <input
                        type="date"
                        name="received_date"
                        id="received_date"
                        value="{{ old('received_date', now()->toDateString()) }}"
                        max="{{ now()->toDateString() }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('received_date') border-red-500 @enderror"
                    >
                    @error('received_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Defaults to today. Used in stock received reports and stock audit.</p>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes <span class="text-gray-400 font-normal">(optional)</span>
                    </label>
                    <textarea
                        name="notes"
                        id="notes"
                        rows="3"
                        maxlength="500"
                        placeholder="Supplier, invoice number, delivery reference..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                    >{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-wrap gap-3 mt-8 pt-6 border-t border-gray-200">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Stock
                </button>
                <a href="{{ route('inventory.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg font-semibold transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
