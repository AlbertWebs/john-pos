@extends('layouts.app')

@section('title', 'Product buy & sell history')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Product buy & sell history</h1>
        <p class="text-gray-600 mt-1">See when a product was received into stock and when it was sold, in one timeline.</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('reports.product-history') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                @if($product)
                    <input type="hidden" name="part_id" value="{{ $product->id }}">
                    <div class="flex items-center gap-2 border border-gray-300 rounded-lg px-3 py-2 bg-gray-50">
                        <span class="text-sm text-gray-900 flex-1">{{ $product->name }} <span class="text-gray-500">({{ $product->part_number }})</span></span>
                        <a href="{{ route('reports.product-history', request()->except('part_id')) }}" class="text-sm text-blue-600 hover:text-blue-800 whitespace-nowrap">Change</a>
                    </div>
                @else
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, part #, or SKU" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div class="flex flex-wrap gap-2 lg:col-span-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">Apply</button>
                <a href="{{ route('reports.product-history') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold">Reset</a>
                @if($product)
                <a href="{{ route('reports.product-history', array_merge(request()->query(), ['export' => 'excel'])) }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export Excel
                </a>
                @endif
            </div>
        </form>
        <p class="text-sm text-gray-500 mt-3">
            <strong>Stock received</strong> is recorded when staff use Add Stock. <strong>Sold</strong> comes from POS and shop sales.
            Leave dates empty for all-time history.
        </p>
    </div>

    @if(!$product && $searchResults->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-3">Select a product</h2>
        <ul class="divide-y divide-gray-200">
            @foreach($searchResults as $item)
            <li>
                <a href="{{ route('reports.product-history', array_merge(request()->except('search'), ['part_id' => $item->id])) }}"
                   class="flex flex-wrap justify-between items-center py-3 hover:bg-gray-50 px-2 -mx-2 rounded-lg transition">
                    <span class="font-medium text-gray-900">{{ $item->name }}</span>
                    <span class="text-sm text-gray-500">{{ $item->part_number }} · {{ $item->sku }}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @elseif(!$product && request()->filled('search'))
    <div class="bg-amber-50 border border-amber-200 text-amber-900 px-4 py-3 rounded-lg">
        No products match your search. Try a different name, part number, or SKU.
    </div>
    @elseif(!$product)
    <div class="bg-blue-50 border border-blue-200 text-blue-900 px-4 py-3 rounded-lg">
        Search for a product above, or open an inventory item and use <strong>Buy & sell history</strong>.
    </div>
    @endif

    @if($product)
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-md p-5">
            <p class="text-sm text-gray-500">Current stock</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($product->stock_quantity) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5">
            <p class="text-sm text-gray-500">Received in range</p>
            <p class="text-2xl font-bold text-green-700 mt-1">+{{ number_format($summary['purchased_qty']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5">
            <p class="text-sm text-gray-500">Sold in range</p>
            <p class="text-2xl font-bold text-blue-700 mt-1">−{{ number_format($summary['sold_qty']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5">
            <p class="text-sm text-gray-500">Returns in range</p>
            <p class="text-2xl font-bold text-amber-700 mt-1">+{{ number_format($summary['returned_qty']) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-wrap justify-between items-center gap-2">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h2>
                <p class="text-sm text-gray-500">{{ $product->part_number }} · {{ $product->category?->name ?? 'No category' }}</p>
            </div>
            <a href="{{ route('inventory.show', $product) }}" class="text-sm text-blue-600 hover:text-blue-800">View inventory item →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Date & time</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Type</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Qty</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Unit price</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Line total</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Reference</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Customer</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">User</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($events as $event)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap text-gray-900">
                            {{ $event['date']->format('M d, Y') }}<br>
                            <span class="text-xs text-gray-500">{{ $event['date']->format('h:i A') }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if($event['type'] === 'purchase')
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $event['type_label'] }}</span>
                            @elseif($event['type'] === 'sale')
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $event['type_label'] }}</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">{{ $event['type_label'] }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-semibold tabular-nums {{ str_starts_with($event['quantity_display'], '+') ? 'text-green-700' : 'text-blue-700' }}">
                            {{ $event['quantity_display'] }}
                        </td>
                        <td class="px-4 py-3 text-right tabular-nums text-gray-900">
                            @if($event['unit_price'] !== null)
                                KES {{ number_format($event['unit_price'], 2) }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right tabular-nums text-gray-900">
                            @if($event['line_total'] !== null)
                                KES {{ number_format($event['line_total'], 2) }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-900">
                            @if(!empty($event['reference_url']))
                                <a href="{{ $event['reference_url'] }}" class="text-blue-600 hover:text-blue-800">{{ $event['reference'] }}</a>
                            @else
                                {{ $event['reference'] }}
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $event['customer'] ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $event['user'] ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-500 max-w-xs truncate" title="{{ $event['notes'] }}">{{ $event['notes'] ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-10 text-center text-gray-500">
                            No stock receipts or sales found for this product in the selected period.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
