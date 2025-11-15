@extends('layouts.app')

@section('title', 'Stock Status')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Stock Status</h1>
            <p class="text-gray-600 mt-1">Monitor inventory levels and stock status</p>
        </div>
        <form action="{{ route('admin.stock-status.send-email') }}" method="POST" class="inline">
            @csrf
            <input type="hidden" name="low_stock_only" value="{{ request('low_stock_only', '0') }}">
            <input type="hidden" name="category_id" value="{{ request('category_id', '') }}">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Send Stock Status Email
            </button>
        </form>
    </div>

    <!-- Cron Job Controls -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col gap-2 mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Cron Job Controls</h2>
            <p class="text-sm text-gray-600">Manually dispatch scheduled jobs via the queue worker.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
            <form method="POST" action="{{ route('admin.stock-status.run-job') }}">
                @csrf
                <input type="hidden" name="job" value="all">
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg font-semibold text-white bg-slate-900 hover:bg-slate-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5h8v14H5z" />
                    </svg>
                    Run All Cron Jobs
                </button>
            </form>
            <form method="POST" action="{{ route('admin.stock-status.run-job') }}">
                @csrf
                <input type="hidden" name="job" value="daily_sales">
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg font-semibold text-white bg-blue-600 hover:bg-blue-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Daily Sales Report
                </button>
            </form>
            <form method="POST" action="{{ route('admin.stock-status.run-job') }}">
                @csrf
                <input type="hidden" name="job" value="hourly_stock">
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Hourly Stock Status
                </button>
            </form>
            <form method="POST" action="{{ route('admin.stock-status.run-job') }}">
                @csrf
                <input type="hidden" name="job" value="low_stock_alert">
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg font-semibold text-white bg-orange-600 hover:bg-orange-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Low Stock Alerts
                </button>
            </form>
            <form method="POST" action="{{ route('admin.stock-status.run-job') }}">
                @csrf
                <input type="hidden" name="job" value="next_order">
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg font-semibold text-white bg-purple-600 hover:bg-purple-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                    </svg>
                    Next Order Reminders
                </button>
            </form>
        </div>
        <p class="text-xs text-gray-500 mt-4">Jobs are queued; keep a worker running via <code>php artisan queue:work</code>.</p>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.stock-status.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="low_stock_only" class="flex items-center">
                    <input type="checkbox" name="low_stock_only" id="low_stock_only" value="1" {{ request('low_stock_only') == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm font-medium text-gray-700">Show Low Stock Only</span>
                </label>
            </div>
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select name="category_id" id="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Stock Status Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Threshold</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($inventory as $item)
                <tr class="hover:bg-gray-50 transition {{ $item->isLowStock() ? 'bg-red-50' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $item->sku ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $item->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $item->category->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                        {{ $item->stock_quantity }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                        {{ $item->reorder_level }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($item->isLowStock())
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            Low Stock
                        </span>
                        @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            OK
                        </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                        No items found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Summary -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Total Items</h3>
            <p class="text-2xl font-bold text-gray-900">{{ $inventory->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Low Stock Items</h3>
            <p class="text-2xl font-bold text-red-600">{{ $inventory->filter(fn($item) => $item->isLowStock())->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-sm font-medium text-gray-500 mb-2">In Stock Items</h3>
            <p class="text-2xl font-bold text-green-600">{{ $inventory->filter(fn($item) => !$item->isLowStock())->count() }}</p>
        </div>
    </div>
</div>
@endsection

