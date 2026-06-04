@extends('layouts.app')

@section('title', 'Stock Value Report')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Stock Value Report</h1>
        <p class="text-gray-600 mt-1">Value of inventory on hand at cost and at list selling price.</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('reports.stock-value') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, part #, SKU" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">All</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>
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
            <div class="flex flex-col gap-2 justify-end">
                <input type="hidden" name="in_stock_only" value="0">
                <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" name="in_stock_only" value="1" @checked(request()->input('in_stock_only', '1') !== '0') class="rounded border-gray-300 text-blue-600">
                    In stock only
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" name="low_stock" value="1" @checked(request('low_stock') == '1') class="rounded border-gray-300 text-blue-600">
                    Low stock only
                </label>
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">Apply</button>
                <a href="{{ route('reports.stock-value') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold">Reset</a>
                <a href="{{ route('reports.stock-value', array_merge(request()->query(), ['export' => 'excel'])) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold">Excel</a>
                <a href="{{ route('reports.stock-value', array_merge(request()->query(), ['export' => 'pdf'])) }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold">PDF</a>
            </div>
        </form>
        <p class="text-sm text-gray-500 mt-3">
            <strong>Value at cost</strong> = qty × cost price. <strong>Value at retail</strong> = qty × list selling price.
            <strong>Potential profit</strong> = retail value − cost value (if everything sold at list price).
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg shadow-md p-5">
            <p class="text-sm text-gray-500">Line items</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totals['total_items']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5">
            <p class="text-sm text-gray-500">Units on hand</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totals['total_units']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5">
            <p class="text-sm text-gray-500">Value at cost</p>
            <p class="text-2xl font-bold text-blue-700 mt-1">KES {{ number_format($totals['cost_value'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5">
            <p class="text-sm text-gray-500">Value at retail</p>
            <p class="text-2xl font-bold text-green-700 mt-1">KES {{ number_format($totals['retail_value'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5">
            <p class="text-sm text-gray-500">Potential profit</p>
            <p class="text-2xl font-bold {{ $totals['potential_profit'] >= 0 ? 'text-emerald-700' : 'text-red-600' }} mt-1">
                KES {{ number_format($totals['potential_profit'], 2) }}
            </p>
        </div>
    </div>

    @if($categoryBreakdown->count() > 0)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">By category</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Category</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Items</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Units</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">At cost</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">At retail</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Potential profit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($categoryBreakdown as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $row->category_name }}</td>
                        <td class="px-4 py-3 text-right tabular-nums">{{ number_format($row->item_count) }}</td>
                        <td class="px-4 py-3 text-right tabular-nums">{{ number_format($row->total_units) }}</td>
                        <td class="px-4 py-3 text-right tabular-nums">KES {{ number_format($row->cost_value, 2) }}</td>
                        <td class="px-4 py-3 text-right tabular-nums">KES {{ number_format($row->retail_value, 2) }}</td>
                        <td class="px-4 py-3 text-right tabular-nums {{ $row->potential_profit >= 0 ? 'text-green-700' : 'text-red-600' }}">
                            KES {{ number_format($row->potential_profit, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Items by value (at cost)</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Part</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Category</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Qty</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Cost</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">List price</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">At cost</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">At retail</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Profit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($items as $item)
                    @php
                        $atCost = $item->stock_quantity * $item->cost_price;
                        $atRetail = $item->stock_quantity * $item->selling_price;
                        $profit = $atRetail - $atCost;
                    @endphp
                    <tr class="hover:bg-gray-50 {{ $item->isLowStock() ? 'bg-amber-50/50' : '' }}">
                        <td class="px-4 py-3">
                            <a href="{{ route('inventory.show', $item) }}" class="font-medium text-blue-600 hover:text-blue-800">{{ $item->name }}</a>
                            <div class="text-xs text-gray-500">{{ $item->part_number }}</div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $item->category?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right tabular-nums font-medium {{ $item->isLowStock() ? 'text-amber-700' : '' }}">{{ number_format($item->stock_quantity) }}</td>
                        <td class="px-4 py-3 text-right tabular-nums">{{ number_format($item->cost_price, 2) }}</td>
                        <td class="px-4 py-3 text-right tabular-nums">{{ number_format($item->selling_price, 2) }}</td>
                        <td class="px-4 py-3 text-right tabular-nums font-semibold text-blue-700">KES {{ number_format($atCost, 2) }}</td>
                        <td class="px-4 py-3 text-right tabular-nums text-green-700">KES {{ number_format($atRetail, 2) }}</td>
                        <td class="px-4 py-3 text-right tabular-nums {{ $profit >= 0 ? 'text-emerald-700' : 'text-red-600' }}">KES {{ number_format($profit, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-10 text-center text-gray-500">No items match your filters.</td>
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
