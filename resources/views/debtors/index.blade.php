@extends('layouts.app')

@section('title', 'Debtor Management')

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap justify-between items-start gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Debtor Management</h1>
            <p class="text-gray-600 mt-1">Track unpaid credit sales and record customer payments against invoices.</p>
        </div>
        <div class="flex flex-wrap gap-2">
        <a href="{{ route('reports.accounts-receivable') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-5 py-2 rounded-lg font-semibold text-sm">AR Report</a>
        <a href="{{ route('pos.index', ['credit' => 1]) }}" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2 rounded-lg font-semibold flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            New Credit Sale
        </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-500">Total outstanding</p>
            <p class="text-3xl font-bold text-red-600 mt-1">KES {{ number_format($totalOutstanding, 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-500">Debtors with balance</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($debtorCount) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-500">How it works</p>
            <p class="text-sm text-gray-700 mt-2">Credit sales create a <strong>sale invoice</strong> with no payment. When the customer pays, record payment on that invoice (Cash or M-Pesa).</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="filter" value="with_balance" @checked(request('filter') === 'with_balance') class="rounded text-amber-600" onchange="this.form.submit()">
                Show only customers with outstanding balance
            </label>
            @if(request('filter'))
            <a href="{{ route('debtors.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Show all customers</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-900">Customers</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Customer</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Phone</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Open invoices</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Balance due</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50 {{ $customer->computed_balance > 0 ? 'bg-amber-50/40' : '' }}">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $customer->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $customer->phone ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">{{ $customer->open_invoices }}</td>
                        <td class="px-4 py-3 text-right font-semibold {{ $customer->computed_balance > 0 ? 'text-red-600' : 'text-gray-500' }}">
                            KES {{ number_format($customer->computed_balance, 2) }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('debtors.show', $customer) }}" class="text-blue-600 hover:text-blue-800 font-medium">View account →</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-gray-500">No customers found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($recentCreditSales->count() > 0)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-900">Recent credit sales</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Invoice</th>
                        <th class="px-4 py-3 text-left">Customer</th>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-right">Total</th>
                        <th class="px-4 py-3 text-right">Balance</th>
                        <th class="px-4 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentCreditSales as $sale)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3"><a href="{{ route('sales.show', $sale) }}" class="text-blue-600 hover:text-blue-800">{{ $sale->invoice_number }}</a></td>
                        <td class="px-4 py-3">{{ $sale->customer?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $sale->date->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-right">KES {{ number_format($sale->total_amount, 2) }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-red-600">KES {{ number_format($sale->balanceDue(), 2) }}</td>
                        <td class="px-4 py-3"><span class="px-2 py-0.5 text-xs rounded-full bg-amber-100 text-amber-800">{{ ucfirst($sale->payment_status) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
