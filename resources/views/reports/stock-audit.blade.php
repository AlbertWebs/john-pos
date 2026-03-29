@extends('layouts.app')

@section('title', 'Stock Audit')

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap justify-between items-start gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Stock Audit</h1>
            <p class="text-gray-600 mt-1">Opening stock, purchases, sales, closing (system), physical count, and variance for the selected period.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">{{ session('error') }}</div>
    @endif
    @if(empty($stockAuditTablesReady))
    <div class="bg-amber-50 border border-amber-200 text-amber-900 px-4 py-3 rounded-lg">
        <strong>Database setup required.</strong> Run migrations on the server so the stock audit tables exist:
        <code class="bg-amber-100 px-1 rounded">php artisan migrate</code>
        (migration file: <code class="bg-amber-100 px-1 rounded">2026_03_29_000001_create_stock_audits_tables</code>)
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('reports.stock-audit') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
                <input type="date" name="start_date" value="{{ $startDate->toDateString() }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                <input type="date" name="end_date" value="{{ $endDate->toDateString() }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
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
                <a href="{{ route('reports.stock-audit') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold">Reset</a>
            </div>
        </form>
        <p class="text-sm text-gray-500 mt-4">
            <strong>Opening stock</strong> = system closing at end of period minus net movement in period (reconciles to inventory movements).
            <strong>Items sold</strong> from sales in the date range. <strong>Purchases / returns</strong> from inventory movements (purchase / return types).
            <strong>Other</strong> = adjust + damage movements. <strong>Closing (system)</strong> = current stock on hand. <strong>Variance</strong> = physical − closing (system).
        </p>
    </div>

    <form method="POST" action="{{ route('reports.stock-audit.save') }}" class="space-y-4" @if(empty($stockAuditTablesReady)) onsubmit="return false" @endif>
        @csrf
        <input type="hidden" name="period_from" value="{{ $startDate->toDateString() }}">
        <input type="hidden" name="period_to" value="{{ $endDate->toDateString() }}">
        <input type="hidden" name="title" value="Count {{ $startDate->toDateString() }} – {{ $endDate->toDateString() }}">

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left font-semibold text-gray-700">Part</th>
                            <th class="px-3 py-2 text-right font-semibold text-gray-700">Opening</th>
                            <th class="px-3 py-2 text-right font-semibold text-gray-700">Purchases</th>
                            <th class="px-3 py-2 text-right font-semibold text-gray-700">Sold</th>
                            <th class="px-3 py-2 text-right font-semibold text-gray-700">Returns</th>
                            <th class="px-3 py-2 text-right font-semibold text-gray-700">Other</th>
                            <th class="px-3 py-2 text-right font-semibold text-gray-700">Closing (sys)</th>
                            <th class="px-3 py-2 text-right font-semibold text-gray-700 w-28">Physical</th>
                            <th class="px-3 py-2 text-right font-semibold text-gray-700">Variance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($rows as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2">
                                <div class="font-medium text-gray-900">{{ $row->part->name }}</div>
                                <div class="text-xs text-gray-500">{{ $row->part->part_number }}</div>
                            </td>
                            <td class="px-3 py-2 text-right tabular-nums">{{ number_format($row->opening_stock) }}</td>
                            <td class="px-3 py-2 text-right tabular-nums">{{ number_format($row->purchases) }}</td>
                            <td class="px-3 py-2 text-right tabular-nums">{{ number_format($row->items_sold) }}</td>
                            <td class="px-3 py-2 text-right tabular-nums">{{ number_format($row->returns) }}</td>
                            <td class="px-3 py-2 text-right tabular-nums">{{ number_format($row->other_movements) }}</td>
                            <td class="px-3 py-2 text-right tabular-nums font-medium">{{ number_format($row->closing_stock) }}</td>
                            <td class="px-3 py-2 text-right">
                                <input type="number" min="0" name="physical[{{ $row->part->id }}]" value="{{ $row->physical_stock }}" placeholder="—" class="w-24 border border-gray-300 rounded px-2 py-1 text-right tabular-nums">
                            </td>
                            <td class="px-3 py-2 text-right tabular-nums @if($row->variance !== null && $row->variance !== 0) font-semibold @endif @if($row->variance !== null && $row->variance !== 0) {{ $row->variance > 0 ? 'text-green-700' : 'text-red-700' }} @endif">
                                @if($row->variance !== null)
                                    {{ $row->variance > 0 ? '+' : '' }}{{ number_format($row->variance) }}
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-3 py-8 text-center text-gray-500">No inventory items match your filters.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($rows->count() > 0)
                    <tfoot class="bg-gray-100 font-semibold">
                        <tr>
                            <td class="px-3 py-2">Page totals</td>
                            <td class="px-3 py-2 text-right tabular-nums">{{ number_format($totals->opening_stock) }}</td>
                            <td class="px-3 py-2 text-right tabular-nums">{{ number_format($totals->purchases) }}</td>
                            <td class="px-3 py-2 text-right tabular-nums">{{ number_format($totals->items_sold) }}</td>
                            <td class="px-3 py-2 text-right tabular-nums">{{ number_format($totals->returns) }}</td>
                            <td class="px-3 py-2 text-right tabular-nums">{{ number_format($totals->other_movements) }}</td>
                            <td class="px-3 py-2 text-right tabular-nums">{{ number_format($totals->closing_stock) }}</td>
                            <td class="px-3 py-2 text-right">
                                @if($totals->physical_stock !== null)
                                    {{ number_format($totals->physical_stock) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-3 py-2 text-right tabular-nums">
                                @if($totals->variance !== null)
                                    {{ $totals->variance > 0 ? '+' : '' }}{{ number_format($totals->variance) }}
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
            @if($rows->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">{{ $rows->links() }}</div>
            @endif
        </div>

        @if($rows->count() > 0 && !empty($stockAuditTablesReady))
        <div class="flex flex-wrap items-center gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                Save physical counts (this page)
            </button>
            <span class="text-sm text-gray-500">Saves physical stock for the rows on this page. Latest saved counts appear on future reports.</span>
        </div>
        @endif
    </form>

    @if($recentAudits->count())
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-3">Recent audits</h2>
        <ul class="text-sm space-y-2">
            @foreach($recentAudits as $a)
            <li class="flex justify-between border-b border-gray-100 pb-2">
                <span>{{ $a->title ?? 'Audit #' . $a->id }} — {{ $a->period_from->toDateString() }} to {{ $a->period_to->toDateString() }}</span>
                <span class="text-gray-500">{{ $a->created_at->format('M j, Y H:i') }} ({{ $a->user->name ?? 'User' }})</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@endsection
