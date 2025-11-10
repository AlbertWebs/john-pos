@extends('layouts.app')

@section('title', $pageTitle ?? 'Top Selling Parts')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $pageTitle ?? 'Top Selling Parts' }}</h1>
            <p class="text-gray-600 mt-1">{{ $pageDescription ?? 'View best-selling inventory items' }}</p>
        </div>
        <div class="flex gap-3">
            <form method="GET" action="{{ route('reports.top-selling') }}" class="flex gap-3">
                <!-- Period Filter -->
                <select name="period" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="this.form.submit()">
                    <option value="">All Time</option>
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>This Month</option>
                    <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>This Year</option>
                </select>

                <!-- Date Range -->
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Start Date">
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="End Date">

                <!-- Limit -->
                <select name="limit" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="this.form.submit()">
                    <option value="10" {{ request('limit', 10) == 10 ? 'selected' : '' }}>Top 10</option>
                    <option value="20" {{ request('limit') == 20 ? 'selected' : '' }}>Top 20</option>
                    <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>Top 50</option>
                    <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>Top 100</option>
                </select>

                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">Filter</button>
            </form>

            <!-- Export Buttons -->
            <div class="flex gap-2">
                <a href="{{ route('reports.top-selling', array_merge(request()->all(), ['export' => 'pdf'])) }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    PDF
                </a>
                <a href="{{ route('reports.top-selling', array_merge(request()->all(), ['export' => 'excel'])) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Excel
                </a>
            </div>
        </div>
    </div>

    <!-- Top Selling Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brand</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity Sold</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Revenue</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Transactions</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Price</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topSelling as $index => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($index == 0)
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold">🥇</span>
                                @elseif($index == 1)
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-bold">🥈</span>
                                @elseif($index == 2)
                                    <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-bold">🥉</span>
                                @else
                                    <span class="text-sm font-semibold text-gray-900">#{{ $index + 1 }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $item['part']->part_number }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $item['part']->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $item['part']->category ? $item['part']->category->name : '—' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $item['part']->brand ? $item['part']->brand->brand_name : '—' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-blue-600">
                            {{ number_format($item['total_quantity']) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-green-600">
                            KES {{ number_format($item['total_revenue'], 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                            {{ number_format($item['transaction_count']) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                            @php
                                $avgPrice = $item['total_quantity'] > 0 ? $item['total_revenue'] / $item['total_quantity'] : 0;
                            @endphp
                            KES {{ number_format($avgPrice, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <p class="text-gray-500">No sales data found for the selected period</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

