@extends('layouts.app')

@section('title', 'Debtor — ' . $customer->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="mb-2">
        <a href="{{ route('debtors.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Debtors
        </a>
    </div>

    <div class="flex flex-wrap justify-between items-start gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $customer->name }}</h1>
            <p class="text-gray-600 mt-1">{{ $customer->phone ?? 'No phone' }} · {{ $customer->email ?? 'No email' }}</p>
        </div>
        <a href="{{ route('pos.index', ['credit' => 1]) }}?customer={{ $customer->id }}" class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2 rounded-lg font-semibold text-sm">
            + Credit sale for this customer
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6">
        <p class="text-sm text-gray-500">Outstanding balance</p>
        <p class="text-4xl font-bold {{ $balance > 0 ? 'text-red-600' : 'text-green-700' }} mt-1">KES {{ number_format($balance, 2) }}</p>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b bg-amber-50">
            <h2 class="text-lg font-semibold text-gray-900">Unpaid invoices ({{ $openSales->count() }})</h2>
        </div>
        @forelse($openSales as $sale)
        <div class="p-6 border-b border-gray-100 last:border-0">
            <div class="flex flex-wrap justify-between items-start gap-4 mb-4">
                <div>
                    <a href="{{ route('sales.show', $sale) }}" class="text-lg font-semibold text-blue-600 hover:text-blue-800">{{ $sale->invoice_number }}</a>
                    <p class="text-sm text-gray-500">{{ $sale->date->format('M d, Y H:i') }}
                        @if($sale->due_date) · Due {{ $sale->due_date->format('M d, Y') }}@endif
                    </p>
                    @if($sale->credit_notes)<p class="text-sm text-gray-600 mt-1">{{ $sale->credit_notes }}</p>@endif
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Invoice total</p>
                    <p class="font-semibold">KES {{ number_format($sale->total_amount, 2) }}</p>
                    <p class="text-sm text-gray-500 mt-2">Paid</p>
                    <p>KES {{ number_format($sale->amountPaid(), 2) }}</p>
                    <p class="text-sm text-red-600 font-bold mt-2">Balance: KES {{ number_format($sale->balanceDue(), 2) }}</p>
                </div>
            </div>

            @if($sale->payments->count() > 0)
            <div class="mb-4 text-sm">
                <p class="font-medium text-gray-700 mb-1">Payments received</p>
                <ul class="space-y-1 text-gray-600">
                    @foreach($sale->payments as $payment)
                    <li>{{ $payment->payment_date->format('M d, Y') }} — {{ $payment->payment_method }} — KES {{ number_format($payment->amount, 2) }}
                        @if($payment->transaction_reference) ({{ $payment->transaction_reference }})@endif
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            @include('debtors.partials.payment-form', ['sale' => $sale])
        </div>
        @empty
        <p class="p-8 text-center text-gray-500">No outstanding invoices for this customer.</p>
        @endforelse
    </div>

    @if($paidCreditSales->count() > 0)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-900">Recently settled credit sales</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Invoice</th>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-right">Total</th>
                        <th class="px-4 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paidCreditSales as $sale)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3"><a href="{{ route('sales.show', $sale) }}" class="text-blue-600">{{ $sale->invoice_number }}</a></td>
                        <td class="px-4 py-3">{{ $sale->date->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-right">KES {{ number_format($sale->total_amount, 2) }}</td>
                        <td class="px-4 py-3"><span class="text-green-700 font-medium">Paid</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
