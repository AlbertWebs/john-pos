@extends('layouts.app')

@section('title', 'Sold price vs cost')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Sold price vs cost price</h1>
        <p class="text-gray-600 mt-1">Compare cost, list selling price, and average actual sold price per item. Use the date range to limit which sales are included in the average sold price.</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('reports.sold-vs-cost') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sales from</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sales to</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="category_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">All</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, part #, SKU" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">Apply</button>
                <a href="{{ route('reports.sold-vs-cost') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold">Reset</a>
            </div>
        </form>
        <p class="text-sm text-gray-500 mt-3">Leave dates empty to use <strong>all-time</strong> sales when calculating average sold price. Margin uses average sold price minus cost (when both exist).</p>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Part</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Cost price</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">List selling price</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Qty sold <span class="text-gray-400 font-normal">(in range)</span></th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Avg sold price</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Margin</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Margin %</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($items as $item)
                    @php
                        $cost = (float) $item->cost_price;
                        $list = (float) $item->selling_price;
                        $avgSold = $item->avg_sold_price !== null ? (float) $item->avg_sold_price : null;
                        $margin = $avgSold !== null ? $avgSold - $cost : null;
                        $marginPct = ($avgSold !== null && $cost > 0) ? (($avgSold - $cost) / $cost) * 100 : null;
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $item->name }}</div>
                            <div class="text-xs text-gray-500">{{ $item->part_number }} @if($item->category) · {{ $item->category->name }} @endif</div>
                        </td>
                        <td class="px-4 py-3 text-right tabular-nums">{{ number_format($cost, 2) }}</td>
                        <td class="px-4 py-3 text-right tabular-nums">{{ number_format($list, 2) }}</td>
                        <td class="px-4 py-3 text-right tabular-nums">{{ $item->qty_sold !== null ? number_format((int) $item->qty_sold) : '—' }}</td>
                        <td class="px-4 py-3 text-right tabular-nums font-medium">{{ $avgSold !== null ? number_format($avgSold, 2) : '—' }}</td>
                        <td class="px-4 py-3 text-right tabular-nums @if($margin !== null && $margin < 0) text-red-600 font-semibold @elseif($margin !== null && $margin > 0) text-green-700 @endif">
                            @if($margin !== null)
                                {{ number_format($margin, 2) }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right tabular-nums">
                            @if($marginPct !== null)
                                {{ number_format($marginPct, 1) }}%
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">No items match your filters.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($items->hasPages())
        <div class="px-4 py-3 border-t border-gray-200">{{ $items->links() }}</div>
        @endif
    </div>
</div>
@endsection
