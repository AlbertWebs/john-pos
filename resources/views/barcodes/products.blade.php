@extends('layouts.app')

@section('title', 'Products With Barcodes')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Products With Barcodes</h1>
            <p class="text-gray-600 mt-1">View all products that have barcodes assigned</p>
        </div>
        <div class="flex gap-3">
            <a 
                href="{{ route('barcodes.index') }}" 
                class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-semibold transition flex items-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Generate Barcodes
            </a>
            <a 
                href="{{ route('barcodes.recentlyGenerated', ['hours' => 24]) }}" 
                class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-semibold transition flex items-center gap-2"
                title="Download barcodes generated in the last 24 hours"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Download Recently Generated (24h)
            </a>
            <button 
                onclick="undoLast24Hours()"
                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-semibold transition flex items-center gap-2"
                title="Remove barcodes created in the last 24 hours"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                </svg>
                Undo Last 24h
            </button>
            <a 
                href="{{ route('barcodes.downloadSummary') }}" 
                class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded-lg font-semibold transition flex items-center gap-2"
                title="Download printing instructions and summary"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download Instructions
            </a>
            <a 
                href="{{ route('barcodes.downloadPDF') }}" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition flex items-center gap-2"
                title="Download barcode stickers PDF"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download Barcodes PDF
            </a>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('barcodes.products') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input 
                        type="text" 
                        name="search" 
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="Search by name, part number, SKU, or barcode..."
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

                <!-- Recently Generated Filter -->
                <div>
                    <label for="recent" class="block text-sm font-medium text-gray-700 mb-1">Recently Generated</label>
                    <select 
                        name="recent" 
                        id="recent"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">All Time</option>
                        <option value="today" {{ request('recent') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="24h" {{ request('recent') == '24h' ? 'selected' : '' }}>Last 24 Hours</option>
                        <option value="7d" {{ request('recent') == '7d' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="30d" {{ request('recent') == '30d' ? 'selected' : '' }}>Last 30 Days</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                    Filter
                </button>
                <a href="{{ route('barcodes.products') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg font-semibold transition">
                    Clear
                </a>
                @if(request('recent'))
                <a 
                    href="{{ route('barcodes.recentlyGenerated', ['hours' => request('recent') == 'today' ? 24 : (request('recent') == '24h' ? 24 : (request('recent') == '7d' ? 168 : 720)))]) }}" 
                    class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-semibold transition flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Filtered PDF
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                Products With Barcodes
                <span class="text-gray-500 font-normal">({{ $items->total() }})</span>
            </h2>
        </div>

        @if($items->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 20%;">Product</th>
                        <th class="px-4 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 12%;">Part Number</th>
                        <th class="px-4 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 10%;">Category</th>
                        <th class="px-4 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 10%;">Brand</th>
                        <th class="px-4 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 8%;">Stock</th>
                        <th class="px-4 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 12%;">Barcode</th>
                        <th class="px-4 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 18%;">Barcode Image</th>
                        <th class="px-4 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 10%;">Price</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($items as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4" style="width: 20%;">
                            <div class="text-sm font-medium text-gray-900 break-words">{{ $item->name }}</div>
                            @if($item->sku)
                            <div class="text-xs text-gray-500 mt-1">SKU: {{ $item->sku }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap" style="width: 12%;">
                            <div class="text-sm text-gray-900">{{ $item->part_number }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap" style="width: 10%;">
                            <span class="text-sm text-gray-900">{{ $item->category->name ?? 'N/A' }}</span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap" style="width: 10%;">
                            <span class="text-sm text-gray-900">{{ $item->brand->brand_name ?? 'N/A' }}</span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center" style="width: 8%;">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->stock_quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $item->stock_quantity }}
                            </span>
                        </td>
                        <td class="px-4 py-4" style="width: 12%;">
                            <div class="text-sm font-mono text-gray-900 font-semibold break-all">{{ $item->barcode }}</div>
                        </td>
                        <td class="px-4 py-4" style="width: 18%;">
                            <div class="flex items-center justify-center bg-white p-2 border border-gray-200 rounded" style="min-height: 60px;">
                                <img 
                                    src="{{ route('barcodes.image', $item->barcode) }}" 
                                    alt="Barcode {{ $item->barcode }}"
                                    class="h-12 w-auto max-w-full object-contain"
                                    style="min-height: 40px; max-height: 50px;"
                                    onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'200\' height=\'50\'%3E%3Ctext x=\'10\' y=\'30\' font-family=\'Arial\' font-size=\'12\'%3EBarcode Error%3C/text%3E%3C/svg%3E'"
                                >
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap" style="width: 10%;">
                            <div class="text-sm font-medium text-gray-900">KSh {{ number_format($item->selling_price, 2) }}</div>
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
            <h3 class="mt-2 text-sm font-medium text-gray-900">No products with barcodes found</h3>
            <p class="mt-1 text-sm text-gray-500">Generate barcodes for products to see them here.</p>
            <div class="mt-6">
                <a href="{{ route('barcodes.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Go to Generate Barcodes
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
async function undoLast24Hours() {
    if (!confirm('Are you sure you want to remove all barcodes created in the last 24 hours? This action cannot be undone.')) {
        return;
    }

    try {
        const response = await fetch('/barcodes/undo-last-24h', {
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
        alert('An error occurred while undoing barcodes');
    }
}
</script>
@endsection
