@extends('layouts.app')

@section('title', 'Accounts Receivable Report')

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap justify-between items-start gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Accounts Receivable</h1>
            <p class="text-gray-600 mt-1">Outstanding debtor balances, aging analysis, and collections for a period.</p>
        </div>
        <a href="{{ route('debtors.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Manage debtors →</a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('reports.accounts-receivable') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">As of (outstanding)</label>
                <input type="date" name="as_of" value="{{ $asOf->toDateString() }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Collections from</label>
                <input type="date" name="collections_from" value="{{ $collectionsFrom->toDateString() }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Collections to</label>
                <input type="date" name="collections_to" value="{{ $collectionsTo->toDateString() }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div class="flex flex-wrap gap-2 lg:col-span-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">Apply</button>
                <a href="{{ route('reports.accounts-receivable') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold">Reset</a>
                <a href="{{ route('reports.accounts-receivable', array_merge(request()->query(), ['export' => 'excel'])) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold">Excel</a>
                <a href="{{ route('reports.accounts-receivable', array_merge(request()->query(), ['export' => 'pdf'])) }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold">PDF</a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-xs text-gray-500">Total outstanding</p>
            <p class="text-xl font-bold text-red-600 mt-1">KES {{ number_format($summary['total_outstanding'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-xs text-gray-500">Open invoices</p>
            <p class="text-xl font-bold text-gray-900 mt-1">{{ number_format($summary['invoice_count']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-xs text-gray-500">Debtors</p>
            <p class="text-xl font-bold text-gray-900 mt-1">{{ number_format($summary['debtor_count']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-xs text-gray-500">Past due date</p>
            <p class="text-xl font-bold text-amber-700 mt-1">KES {{ number_format($summary['overdue_amount'], 2) }}</p>
            <p class="text-xs text-gray-500">{{ $summary['overdue_count'] }} invoice(s)</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-xs text-gray-500">Credit sales balance</p>
            <p class="text-xl font-bold text-gray-900 mt-1">KES {{ number_format($summary['credit_outstanding'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-xs text-gray-500">Collected (period)</p>
            <p class="text-xl font-bold text-green-700 mt-1">KES {{ number_format($summary['collections_total'], 2) }}</p>
            <p class="text-xs text-gray-500">{{ $summary['collections_count'] }} payment(s)</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-900">Aging summary</h2>
            <p class="text-sm text-gray-500">By days since invoice date (as of {{ $asOf->format('M d, Y') }})</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Bucket</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Invoices</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Amount</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">% of total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($aging as $bucket)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">{{ $bucket->label }}</td>
                        <td class="px-4 py-3 text-right">{{ $bucket->invoice_count }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-red-600">KES {{ number_format($bucket->amount, 2) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">
                            @if($summary['total_outstanding'] > 0)
                                {{ number_format(($bucket->amount / $summary['total_outstanding']) * 100, 1) }}%
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    <tr class="bg-gray-50 font-semibold">
                        <td class="px-4 py-3">Total</td>
                        <td class="px-4 py-3 text-right">{{ $summary['invoice_count'] }}</td>
                        <td class="px-4 py-3 text-right">KES {{ number_format($summary['total_outstanding'], 2) }}</td>
                        <td class="px-4 py-3 text-right">100%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-900">Debtor summary</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-left font-semibold text-gray-700">Customer</th>
                        <th class="px-3 py-3 text-right font-semibold text-gray-700">Inv.</th>
                        <th class="px-3 py-3 text-right font-semibold text-gray-700">0–30</th>
                        <th class="px-3 py-3 text-right font-semibold text-gray-700">31–60</th>
                        <th class="px-3 py-3 text-right font-semibold text-gray-700">61–90</th>
                        <th class="px-3 py-3 text-right font-semibold text-gray-700">90+</th>
                        <th class="px-3 py-3 text-right font-semibold text-gray-700">Total</th>
                        <th class="px-3 py-3 text-right font-semibold text-gray-700">Oldest</th>
                        <th class="px-3 py-3 text-right font-semibold text-gray-700"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($debtors->filter(fn ($d) => $d->balance > 0.01) as $debtor)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-3">
                            <div class="font-medium text-gray-900">{{ $debtor->customer_name }}</div>
                            @if($debtor->phone)<div class="text-xs text-gray-500">{{ $debtor->phone }}</div>@endif
                        </td>
                        <td class="px-3 py-3 text-right">{{ $debtor->invoice_count }}</td>
                        <td class="px-3 py-3 text-right tabular-nums">{{ number_format($debtor->aging['current'] ?? 0, 0) }}</td>
                        <td class="px-3 py-3 text-right tabular-nums">{{ number_format($debtor->aging['31_60'] ?? 0, 0) }}</td>
                        <td class="px-3 py-3 text-right tabular-nums">{{ number_format($debtor->aging['61_90'] ?? 0, 0) }}</td>
                        <td class="px-3 py-3 text-right tabular-nums text-red-600">{{ number_format($debtor->aging['over_90'] ?? 0, 0) }}</td>
                        <td class="px-3 py-3 text-right font-semibold tabular-nums">KES {{ number_format($debtor->balance, 2) }}</td>
                        <td class="px-3 py-3 text-right text-gray-600">{{ $debtor->oldest_days }}d</td>
                        <td class="px-3 py-3 text-right">
                            @if($debtor->customer_id)
                            <a href="{{ route('debtors.show', $debtor->customer_id) }}" class="text-blue-600 hover:text-blue-800 text-xs">Account</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="px-4 py-8 text-center text-gray-500">No outstanding balances.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-900">Outstanding invoices</h2>
        </div>
        <div class="overflow-x-auto max-h-[480px] overflow-y-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-50 sticky top-0">
                    <tr>
                        <th class="px-3 py-3 text-left font-semibold text-gray-700">Invoice</th>
                        <th class="px-3 py-3 text-left font-semibold text-gray-700">Customer</th>
                        <th class="px-3 py-3 text-left font-semibold text-gray-700">Date</th>
                        <th class="px-3 py-3 text-left font-semibold text-gray-700">Due</th>
                        <th class="px-3 py-3 text-right font-semibold text-gray-700">Total</th>
                        <th class="px-3 py-3 text-right font-semibold text-gray-700">Paid</th>
                        <th class="px-3 py-3 text-right font-semibold text-gray-700">Balance</th>
                        <th class="px-3 py-3 text-right font-semibold text-gray-700">Days</th>
                        <th class="px-3 py-3 text-left font-semibold text-gray-700">Aging</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($invoices as $inv)
                    <tr class="hover:bg-gray-50 {{ $inv->is_overdue ? 'bg-amber-50/50' : '' }}">
                        <td class="px-3 py-2">
                            <a href="{{ route('sales.show', $inv->sale) }}" class="text-blue-600 hover:text-blue-800 font-medium">{{ $inv->invoice_number }}</a>
                            @if($inv->is_credit)<span class="ml-1 text-xs text-amber-700">credit</span>@endif
                        </td>
                        <td class="px-3 py-2 text-gray-900">{{ $inv->customer_name }}</td>
                        <td class="px-3 py-2 whitespace-nowrap">{{ $inv->invoice_date->format('M d, Y') }}</td>
                        <td class="px-3 py-2 whitespace-nowrap {{ $inv->is_overdue ? 'text-red-600 font-medium' : 'text-gray-600' }}">
                            {{ $inv->due_date ? $inv->due_date->format('M d, Y') : '—' }}
                        </td>
                        <td class="px-3 py-2 text-right tabular-nums">{{ number_format($inv->total, 2) }}</td>
                        <td class="px-3 py-2 text-right tabular-nums">{{ number_format($inv->paid, 2) }}</td>
                        <td class="px-3 py-2 text-right font-semibold text-red-600 tabular-nums">{{ number_format($inv->balance, 2) }}</td>
                        <td class="px-3 py-2 text-right">{{ $inv->days_outstanding }}</td>
                        <td class="px-3 py-2 text-xs text-gray-600">{{ $inv->aging_label }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-900">Collections received</h2>
            <p class="text-sm text-gray-500">{{ $collectionsFrom->format('M d, Y') }} – {{ $collectionsTo->format('M d, Y') }} (payments on credit sales)</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Date</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Invoice</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Customer</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Method</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Amount</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Reference</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($collections as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap">{{ $row->payment_date->format('M d, Y H:i') }}</td>
                        <td class="px-4 py-3">
                            @if($row->sale)
                            <a href="{{ route('sales.show', $row->sale) }}" class="text-blue-600 hover:text-blue-800">{{ $row->invoice_number }}</a>
                            @else
                            {{ $row->invoice_number }}
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $row->customer_name }}</td>
                        <td class="px-4 py-3">{{ $row->payment_method }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-green-700">KES {{ number_format($row->amount, 2) }}</td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $row->reference ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No collections in this period.</td></tr>
                    @endforelse
                </tbody>
                @if($collections->count() > 0)
                <tfoot class="bg-gray-50 font-semibold">
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-right">Total</td>
                        <td class="px-4 py-3 text-right text-green-700">KES {{ number_format($summary['collections_total'], 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
