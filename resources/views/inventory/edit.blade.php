@extends('layouts.app')

@section('title', 'Edit Inventory Item')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('inventory.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Inventory
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Edit Inventory Item</h1>
        <p class="text-gray-600 mt-1">{{ $inventory->name }}</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form method="POST" action="{{ route('inventory.update', $inventory) }}" x-data="inventoryForm()">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Part Number -->
                    <div>
                        <label for="part_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Part Number <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="part_number" 
                            id="part_number"
                            value="{{ old('part_number', $inventory->part_number) }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('part_number') border-red-500 @enderror"
                        >
                        @error('part_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SKU (Display Only - Auto-generated) -->
                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                        <input 
                            type="text" 
                            name="sku" 
                            id="sku"
                            value="{{ old('sku', $inventory->sku) }}"
                            readonly
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed"
                            placeholder="Auto-generated"
                        >
                        <p class="mt-1 text-xs text-gray-500">SKU is auto-generated and cannot be edited</p>
                    </div>

                    <!-- Barcode -->
                    <div>
                        <label for="barcode" class="block text-sm font-medium text-gray-700 mb-2">
                            Barcode
                            <span class="text-xs text-gray-500 font-normal">(Scan or enter manually)</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="barcode" 
                                id="barcode"
                                value="{{ old('barcode', $inventory->barcode) }}"
                                x-ref="barcodeInput"
                                @keydown="handleBarcodeInput($event)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('barcode') border-red-500 @enderror"
                                placeholder="Scan barcode or enter manually"
                                autocomplete="off"
                            >
                            <button 
                                type="button"
                                @click="toggleCameraScanner"
                                class="absolute right-2 top-1/2 -translate-y-1/2 p-2 text-gray-500 hover:text-blue-600 transition"
                                title="Toggle camera scanner"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('barcode')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Item Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name"
                            value="{{ old('name', $inventory->name) }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea 
                            name="description" 
                            id="description"
                            rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                        >{{ old('description', $inventory->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Categorization -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Categorization</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select 
                            name="category_id" 
                            id="category_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_id') border-red-500 @enderror"
                        >
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $inventory->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Brand -->
                    <div>
                        <label for="brand_id" class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                        <select 
                            name="brand_id" 
                            id="brand_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('brand_id') border-red-500 @enderror"
                        >
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $inventory->brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->brand_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vehicle Models (Multiple Selection) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Vehicle Models <span class="text-gray-500 text-xs">(Select multiple if item fits multiple cars)</span>
                        </label>
                        <div class="border border-gray-300 rounded-lg p-4 max-h-96 overflow-y-auto bg-gray-50 @error('vehicle_model_ids') border-red-500 @enderror" x-data="{ selectedCount: {{ count(old('vehicle_model_ids', $selectedVehicleModelIds)) }} }">
                            <div class="flex justify-between items-center mb-3 pb-2 border-b">
                                <span class="text-sm font-medium text-gray-700">
                                    <span x-text="selectedCount"></span> model(s) selected
                                </span>
                                <button 
                                    type="button"
                                    @click="Array.from($el.closest('[x-data]').querySelectorAll('input[type=checkbox]')).forEach(cb => cb.checked = false); selectedCount = 0;"
                                    class="text-xs text-blue-600 hover:text-blue-800 font-medium"
                                >
                                    Clear All
                                </button>
                            </div>
                            @foreach($allVehicleModels->groupBy('vehicle_make_id') as $makeId => $models)
                                @php
                                    $make = $models->first()->vehicleMake;
                                @endphp
                                <div class="mb-4">
                                    <h4 class="font-semibold text-gray-800 mb-2 text-sm">{{ $make->make_name }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 ml-4">
                                        @foreach($models as $model)
                                            <label class="flex items-center space-x-2 p-2 hover:bg-white rounded cursor-pointer transition">
                                                <input 
                                                    type="checkbox" 
                                                    name="vehicle_model_ids[]" 
                                                    value="{{ $model->id }}"
                                                    {{ in_array($model->id, old('vehicle_model_ids', $selectedVehicleModelIds)) ? 'checked' : '' }}
                                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-2"
                                                    @change="selectedCount = Array.from($el.closest('[x-data]').querySelectorAll('input[type=checkbox]')).filter(cb => cb.checked).length"
                                                >
                                                <span class="text-sm text-gray-700">
                                                    {{ $model->model_name }}@if($model->year_start && $model->year_end) <span class="text-gray-500">({{ $model->year_start }}-{{ $model->year_end }})</span>@endif
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                            @if($allVehicleModels->isEmpty())
                                <p class="text-sm text-gray-500 text-center py-4">No vehicle models available. Please add vehicle models first.</p>
                            @endif
                        </div>
                        @error('vehicle_model_ids')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Year Range -->
                    <div>
                        <label for="year_range" class="block text-sm font-medium text-gray-700 mb-2">Year Range</label>
                        <input 
                            type="text" 
                            name="year_range" 
                            id="year_range"
                            value="{{ old('year_range', $inventory->year_range) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('year_range') border-red-500 @enderror"
                            placeholder="e.g., 2015-2021"
                        >
                        @error('year_range')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Pricing & Stock -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Pricing & Stock</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Cost Price -->
                    <div>
                        <label for="cost_price" class="block text-sm font-medium text-gray-700 mb-2">
                            Cost Price (KES) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01"
                            min="0"
                            name="cost_price" 
                            id="cost_price"
                            value="{{ old('cost_price', $inventory->cost_price) }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cost_price') border-red-500 @enderror"
                        >
                        @error('cost_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Min Price -->
                    <div>
                        <label for="min_price" class="block text-sm font-medium text-gray-700 mb-2">
                            Min Price (KES) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01"
                            min="0"
                            name="min_price" 
                            id="min_price"
                            x-model="minPrice"
                            value="{{ old('min_price', $inventory->min_price) }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('min_price') border-red-500 @enderror"
                        >
                        @error('min_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Cannot sell below this price</p>
                    </div>

                    <!-- Selling Price -->
                    <div>
                        <label for="selling_price" class="block text-sm font-medium text-gray-700 mb-2">
                            Selling Price (KES) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01"
                            min="0"
                            name="selling_price" 
                            id="selling_price"
                            x-model="sellingPrice"
                            value="{{ old('selling_price', $inventory->selling_price) }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('selling_price') border-red-500 @enderror"
                        >
                        @error('selling_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock Quantity -->
                    <div>
                        <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                            Stock Quantity <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            min="0"
                            name="stock_quantity" 
                            id="stock_quantity"
                            value="{{ old('stock_quantity', $inventory->stock_quantity) }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stock_quantity') border-red-500 @enderror"
                        >
                        @error('stock_quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reorder Level -->
                    <div>
                        <label for="reorder_level" class="block text-sm font-medium text-gray-700 mb-2">
                            Reorder Level <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            min="0"
                            name="reorder_level" 
                            id="reorder_level"
                            value="{{ old('reorder_level', $inventory->reorder_level) }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('reorder_level') border-red-500 @enderror"
                        >
                        @error('reorder_level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Alert when stock reaches this level</p>
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location (Shelf/Bin)</label>
                        <input 
                            type="text" 
                            name="location" 
                            id="location"
                            value="{{ old('location', $inventory->location) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('location') border-red-500 @enderror"
                            placeholder="e.g., A-12"
                        >
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="status" 
                            id="status"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror"
                        >
                            <option value="active" {{ old('status', $inventory->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $inventory->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-4 pt-6 border-t">
                <a href="{{ route('inventory.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                    Update Inventory Item
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function inventoryForm() {
    return {
        minPrice: {{ old('min_price', $inventory->min_price) }},
        sellingPrice: {{ old('selling_price', $inventory->selling_price) }},
        barcodeInputTimeout: null,
        lastBarcodeInputTime: 0,
        cameraScannerActive: false,
        
        init() {
            // Auto-focus barcode field on page load
            this.$nextTick(() => {
                this.$refs.barcodeInput?.focus();
            });
            
            // Listen for keyboard shortcuts (Ctrl+B or Cmd+B to focus barcode)
            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                    e.preventDefault();
                    this.$refs.barcodeInput?.focus();
                }
            });
        },
        
        handleBarcodeInput(event) {
            const input = event.target;
            const currentTime = Date.now();
            
            // Detect if this is a barcode scanner input
            // Barcode scanners typically send characters very quickly (< 50ms between chars)
            // and end with Enter, Tab, or other special keys
            if (event.key === 'Enter' || event.key === 'Tab') {
                // Clear any pending timeout
                if (this.barcodeInputTimeout) {
                    clearTimeout(this.barcodeInputTimeout);
                    this.barcodeInputTimeout = null;
                }
                
                // If Enter was pressed and input was rapid, it's likely from a scanner
                if (event.key === 'Enter' && (currentTime - this.lastBarcodeInputTime) < 100) {
                    event.preventDefault();
                    // Process the barcode (you can add lookup logic here if needed)
                    // For now, just prevent form submission
                    return false;
                }
            } else {
                // Regular character input
                this.lastBarcodeInputTime = currentTime;
                
                // Clear previous timeout
                if (this.barcodeInputTimeout) {
                    clearTimeout(this.barcodeInputTimeout);
                }
                
                // Set timeout to detect end of input (for scanners that don't send Enter)
                this.barcodeInputTimeout = setTimeout(() => {
                    // Input has stopped, could be end of barcode scan
                    // You can add auto-lookup or validation here if needed
                }, 200);
            }
        },
        
        toggleCameraScanner() {
            // Placeholder for camera scanner functionality
            // You can integrate libraries like QuaggaJS or Html5Qrcode here
            alert('Camera scanner functionality can be added here. Would you like to integrate a barcode scanning library?');
        }
    }
}
</script>
@endsection

