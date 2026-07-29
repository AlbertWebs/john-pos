@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        <!-- Today's Sales (all invoices) -->
        <div class="bg-white rounded-lg shadow-md p-5 border-l-4 border-blue-500 xl:col-span-1">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Total Invoiced</p>
                    <p class="text-lg md:text-xl font-extrabold text-gray-900 mt-1 leading-tight truncate">KES {{ number_format($stats['today_sales'] ?? 0, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $stats['today_transactions'] ?? 0 }} invoices today</p>
                </div>
                <div class="bg-blue-100 p-2.5 rounded-full flex-shrink-0 ml-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Closing Sales (paid/completed only) -->
        <div class="bg-white rounded-lg shadow-md p-5 border-l-4 border-teal-500 xl:col-span-1">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Closing Sales</p>
                    <p class="text-lg md:text-xl font-extrabold text-gray-900 mt-1 leading-tight truncate">KES {{ number_format($stats['today_closing_sales'] ?? 0, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $stats['today_closing_count'] ?? 0 }} paid today</p>
                </div>
                <div class="bg-teal-100 p-2.5 rounded-full flex-shrink-0 ml-2">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Daily Profit -->
        @php $profit = $stats['today_profit'] ?? 0; @endphp
        <div class="bg-white rounded-lg shadow-md p-5 border-l-4 {{ $profit >= 0 ? 'border-green-500' : 'border-amber-500' }} xl:col-span-1">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Today's Profit</p>
                    <p class="text-lg md:text-xl font-extrabold mt-1 leading-tight truncate {{ $profit >= 0 ? 'text-green-700' : 'text-amber-700' }}">
                        KES {{ number_format($profit, 2) }}
                    </p>
                    <p class="text-xs mt-0.5 {{ $profit >= 0 ? 'text-green-600' : 'text-amber-600' }}">
                        {{ $profit >= 0 ? 'Gross profit (paid sales)' : 'Negative — check pricing' }}
                    </p>
                </div>
                <div class="p-2.5 rounded-full flex-shrink-0 ml-2 {{ $profit >= 0 ? 'bg-green-100' : 'bg-amber-100' }}">
                    <svg class="w-5 h-5 {{ $profit >= 0 ? 'text-green-600' : 'text-amber-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Low Stock Items — clickable -->
        <a href="{{ route('admin.stock-status.index', ['low_stock_only' => 1]) }}"
           class="bg-white rounded-lg shadow-md p-5 border-l-4 border-red-500 hover:shadow-lg hover:bg-red-50 transition xl:col-span-1 block">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Low Stock Items</p>
                    <p class="text-lg md:text-xl font-extrabold text-gray-900 mt-1 leading-tight">{{ $stats['low_stock_items'] ?? 0 }}</p>
                    <p class="text-xs text-red-600 mt-0.5 font-medium">View all →</p>
                </div>
                <div class="bg-red-100 p-2.5 rounded-full flex-shrink-0 ml-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </a>

        @if(auth()->user()->isSuperAdmin())
        <!-- Inventory Value -->
        <div class="bg-white rounded-lg shadow-md p-5 border-l-4 border-purple-500 xl:col-span-1">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Inventory Value</p>
                    <p class="text-lg md:text-xl font-extrabold text-gray-900 mt-1 leading-tight truncate">KES {{ number_format($stats['total_inventory_value'] ?? 0, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">at cost price</p>
                </div>
                <div class="bg-purple-100 p-2.5 rounded-full flex-shrink-0 ml-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-lg shadow-md p-5 border-l-4 border-orange-400 xl:col-span-1">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Pending / Credit</p>
                    <p class="text-lg md:text-xl font-extrabold text-gray-900 mt-1 leading-tight">{{ $stats['pending_orders'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">unpaid invoices</p>
                </div>
                <div class="bg-orange-100 p-2.5 rounded-full flex-shrink-0 ml-2">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Daily Sales Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Daily Sales (Last 7 Days)</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="dailySalesChart"></canvas>
            </div>
        </div>

        <!-- Weekly Sales Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Weekly Sales (Last 8 Weeks)</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="weeklySalesChart"></canvas>
            </div>
        </div>

        <!-- Monthly Sales Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Sales (Last 6 Months)</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="monthlySalesChart"></canvas>
            </div>
        </div>

        <!-- Payment Methods Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Methods Distribution</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="paymentMethodsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Selling Items -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Selling Items</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item Name</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Quantity Sold</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Revenue</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($stats['top_selling_items'] ?? [] as $index => $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['name'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">{{ number_format($item['quantity']) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">KES {{ number_format($item['revenue'], 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No sales data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('pos.index') }}" class="block w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition text-center font-medium">
                    New Sale
                </a>
                @can('manage inventory')
                <a href="{{ route('inventory.index') }}" class="block w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 transition text-center font-medium">
                    Manage Inventory
                </a>
                @endcan
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
            <div class="space-y-3">
                <p class="text-gray-600">No recent activity</p>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Chart data from server - ensure arrays are properly formatted
const dailyData = @json($stats['daily_sales'] ?? ['labels' => [], 'revenue' => [], 'transactions' => []]);
const weeklyData = @json($stats['weekly_sales'] ?? ['labels' => [], 'revenue' => [], 'transactions' => []]);
const monthlyData = @json($stats['monthly_sales'] ?? ['labels' => [], 'revenue' => [], 'transactions' => []]);
const paymentData = @json($stats['payment_methods'] ?? ['labels' => [], 'amounts' => [], 'counts' => []]);

// Ensure arrays are not null/undefined
const ensureArray = (arr) => Array.isArray(arr) ? arr : [];

// Daily Sales Chart
const dailyCtx = document.getElementById('dailySalesChart');
if (dailyCtx) {
    const labels = ensureArray(dailyData.labels);
    const revenue = ensureArray(dailyData.revenue).map(v => parseFloat(v) || 0);
    
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: labels.length > 0 ? labels : ['No Data'],
            datasets: [{
                label: 'Revenue (KES)',
                data: revenue.length > 0 ? revenue : [0],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'KES ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
}

// Weekly Sales Chart
const weeklyCtx = document.getElementById('weeklySalesChart');
if (weeklyCtx) {
    const labels = ensureArray(weeklyData.labels);
    const revenue = ensureArray(weeklyData.revenue).map(v => parseFloat(v) || 0);
    
    new Chart(weeklyCtx, {
        type: 'bar',
        data: {
            labels: labels.length > 0 ? labels : ['No Data'],
            datasets: [{
                label: 'Revenue (KES)',
                data: revenue.length > 0 ? revenue : [0],
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                borderColor: 'rgb(16, 185, 129)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'KES ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
}

// Monthly Sales Chart
const monthlyCtx = document.getElementById('monthlySalesChart');
if (monthlyCtx) {
    const labels = ensureArray(monthlyData.labels);
    const revenue = ensureArray(monthlyData.revenue).map(v => parseFloat(v) || 0);
    const transactions = ensureArray(monthlyData.transactions).map(v => parseFloat(v) || 0);
    
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: labels.length > 0 ? labels : ['No Data'],
            datasets: [{
                label: 'Revenue (KES)',
                data: revenue.length > 0 ? revenue : [0],
                borderColor: 'rgb(139, 92, 246)',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Transactions',
                data: transactions.length > 0 ? transactions : [0],
                borderColor: 'rgb(236, 72, 153)',
                backgroundColor: 'rgba(236, 72, 153, 0.1)',
                tension: 0.4,
                fill: true,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'KES ' + value.toLocaleString();
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
}

// Payment Methods Chart
const paymentCtx = document.getElementById('paymentMethodsChart');
if (paymentCtx) {
    const labels = ensureArray(paymentData.labels);
    const amounts = ensureArray(paymentData.amounts).map(v => parseFloat(v) || 0);
    const colors = [
        'rgba(59, 130, 246, 0.8)',
        'rgba(16, 185, 129, 0.8)',
        'rgba(236, 72, 153, 0.8)',
        'rgba(139, 92, 246, 0.8)',
        'rgba(251, 191, 36, 0.8)',
        'rgba(239, 68, 68, 0.8)',
    ];
    
    new Chart(paymentCtx, {
        type: 'doughnut',
        data: {
            labels: labels.length > 0 ? labels : ['No Data'],
            datasets: [{
                data: amounts.length > 0 ? amounts : [0],
                backgroundColor: colors.slice(0, Math.max(labels.length, 1)),
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'right'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'KES ' + context.parsed.toLocaleString();
                            return label;
                        }
                    }
                }
            }
        }
    });
}
</script>
@endsection
