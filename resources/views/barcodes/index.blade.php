@extends('layouts.app')

@section('title', 'Items Without Barcodes')

@section('content')
<div class="space-y-6" x-data="barcodeManager()">
    <!-- Header with Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Items Without Barcodes</h1>
            <p class="text-gray-600 mt-1">Generate barcodes for items and download printable stickers</p>
        </div>
        <div class="flex gap-3">
            <button 
                @click="generateAllBarcodes()"
                :disabled="processing || items.length === 0"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold transition flex items-center gap-2 disabled:bg-gray-300 disabled:cursor-not-allowed"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span x-show="!processing">Generate All</span>
                <span x-show="processing">Processing...</span>
            </button>
            <a 
                href="{{ route('barcodes.downloadPDF') }}" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition flex items-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download All Barcodes PDF
            </a>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('barcodes.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input 
                        type="text" 
                        name="search" 
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="Search by name, part number, SKU..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select 
                        name="category_id" 
                        id="category_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                    Filter
                </button>
                <a href="{{ route('barcodes.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg font-semibold transition">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Items Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">
                    Items Without Barcodes
                    <span class="text-gray-500 font-normal">({{ $items->total() }})</span>
                </h2>
            </div>
            <div class="flex gap-3">
                <button 
                    @click="selectAll()"
                    class="text-sm text-blue-600 hover:text-blue-800 font-medium"
                >
                    Select All
                </button>
                <button 
                    @click="deselectAll()"
                    class="text-sm text-gray-600 hover:text-gray-800 font-medium"
                >
                    Deselect All
                </button>
                <button 
                    @click="generateSelectedBarcodes()"
                    :disabled="processing || selectedItems.length === 0"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition disabled:bg-gray-300 disabled:cursor-not-allowed"
                >
                    Generate Selected (<span x-text="selectedItems.length"></span>)
                </button>
                <button 
                    @click="downloadSelectedPDF()"
                    :disabled="selectedItems.length === 0"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition disabled:bg-gray-300 disabled:cursor-not-allowed"
                >
                    Download PDF
                </button>
            </div>
        </div>

        @if($items->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input 
                                type="checkbox" 
                                @change="toggleAll($event)"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            >
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($items as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input 
                                type="checkbox" 
                                :value="{{ $item->id }}"
                                x-model="selectedItems"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            >
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                            @if($item->brand)
                            <div class="text-sm text-gray-500">{{ $item->brand->name }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->part_number }}</div>
                            @if($item->sku)
                            <div class="text-sm text-gray-500">SKU: {{ $item->sku }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ $item->category->name ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->stock_quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $item->stock_quantity }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button 
                                @click="generateBarcode({{ $item->id }})"
                                :disabled="processing"
                                class="text-green-600 hover:text-green-900 disabled:text-gray-400"
                            >
                                Generate
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $items->links() }}
        </div>
        @else
        <div class="p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No items without barcodes</h3>
            <p class="mt-1 text-sm text-gray-500">All items have barcodes assigned.</p>
        </div>
        @endif
    </div>
</div>

<script>
function barcodeManager() {
    return {
        selectedItems: [],
        processing: false,

        selectAll() {
            this.selectedItems = @json($items->pluck('id')->toArray());
        },

        deselectAll() {
            this.selectedItems = [];
        },

        toggleAll(event) {
            if (event.target.checked) {
                this.selectAll();
            } else {
                this.deselectAll();
            }
        },

        async generateBarcode(itemId) {
            this.processing = true;
            try {
                const response = await fetch(`/barcodes/generate/${itemId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                });

                const data = await response.json();

                if (data.success) {
                    alert('Barcode generated successfully: ' + data.barcode);
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while generating barcode');
            } finally {
                this.processing = false;
            }
        },

        async generateSelectedBarcodes() {
            if (this.selectedItems.length === 0) {
                alert('Please select at least one item');
                return;
            }

            if (!confirm(`Generate barcodes for ${this.selectedItems.length} item(s)?`)) {
                return;
            }

            this.processing = true;
            try {
                const response = await fetch('/barcodes/generate-bulk', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        item_ids: this.selectedItems,
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while generating barcodes');
            } finally {
                this.processing = false;
            }
        },

        async generateAllBarcodes() {
            if (!confirm('Generate barcodes for all items without barcodes? This may take a while.')) {
                return;
            }

            this.processing = true;
            try {
                const response = await fetch('/barcodes/generate-all', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while generating barcodes');
            } finally {
                this.processing = false;
            }
        },

        downloadSelectedPDF() {
            if (this.selectedItems.length === 0) {
                alert('Please select at least one item');
                return;
            }

            const params = new URLSearchParams();
            this.selectedItems.forEach(id => {
                params.append('item_ids[]', id);
            });

            window.location.href = `/barcodes/download-pdf?${params.toString()}`;
        },
    }
}
</script>
@endsection
