@extends('layouts.app')

@section('title', 'Sale Receipt')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Receipt Header -->
    <div class="bg-white rounded-lg shadow-md p-8 mb-6" id="receipt">
        <div class="text-center mb-6">
            @if(isset($settings['logo']) && $settings['logo'])
            <div class="mb-4">
                <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo" class="h-16 mx-auto">
            </div>
            @endif
            <h1 class="text-2xl font-bold text-gray-900">{{ $settings['company_name'] ?? config('app.name', 'Spare Parts POS') }}</h1>
            <p class="text-gray-600 mt-1">Sale Receipt</p>
            @if(isset($settings['address']) || isset($settings['phone']) || isset($settings['email']))
            <div class="mt-2 text-xs text-gray-500">
                @if(isset($settings['address']))<p>{{ $settings['address'] }}</p>@endif
                @if(isset($settings['phone']))<p>Phone: {{ $settings['phone'] }}</p>@endif
                @if(isset($settings['email']))<p>Email: {{ $settings['email'] }}</p>@endif
            </div>
            @endif
        </div>

        <!-- Invoice Details -->
        <div class="grid grid-cols-2 gap-4 mb-6 pb-6 border-b">
            <div>
                <p class="text-sm text-gray-500">Invoice Number</p>
                <p class="font-semibold text-gray-900">{{ $sale->invoice_number }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Date</p>
                <p class="font-semibold text-gray-900">{{ $sale->date->format('M d, Y h:i A') }}</p>
            </div>
            @if($sale->customer)
            <div>
                <p class="text-sm text-gray-500">Customer</p>
                <p class="font-semibold text-gray-900">{{ $sale->customer->name }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Cashier</p>
                <p class="font-semibold text-gray-900">{{ $sale->user->name }}</p>
            </div>
            @else
            <div class="col-span-2 text-right">
                <p class="text-sm text-gray-500">Cashier</p>
                <p class="font-semibold text-gray-900">{{ $sale->user->name }}</p>
            </div>
            @endif
        </div>

        <!-- Items Table -->
        <div class="mb-6">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($sale->saleItems as $item)
                    <tr>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-medium text-gray-900">{{ $item->part->name }}</p>
                                <p class="text-xs text-gray-500">{{ $item->part->part_number }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right text-gray-900">{{ $item->quantity }}</td>
                        <td class="px-4 py-3 text-right text-gray-900">KES {{ number_format($item->price, 2) }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-900">KES {{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="border-t pt-4">
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-medium text-gray-900">KES {{ number_format($sale->subtotal, 2) }}</span>
                </div>
                @if($sale->tax > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Tax</span>
                    <span class="font-medium text-gray-900">KES {{ number_format($sale->tax, 2) }}</span>
                </div>
                @endif
                @if($sale->discount > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Discount</span>
                    <span class="font-medium text-red-600">-KES {{ number_format($sale->discount, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between text-lg font-bold border-t pt-2 mt-2">
                    <span>Total</span>
                    <span class="text-green-600">KES {{ number_format($sale->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        @if($sale->payments->count() > 0)
        <div class="mt-6 pt-6 border-t">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Payment Information</h3>
            @foreach($sale->payments as $payment)
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">{{ $payment->payment_method }}</span>
                <span class="font-medium text-gray-900">KES {{ number_format($payment->amount, 2) }}</span>
            </div>
            @if($payment->transaction_reference)
            <p class="text-xs text-gray-500 mt-1">Ref: {{ $payment->transaction_reference }}</p>
            @endif
            @endforeach
        </div>
        @endif

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t text-center">
            <p class="text-xs text-gray-500">Thank you for your business!</p>
            @if($sale->customer)
            <p class="text-xs text-gray-500 mt-1">Loyalty Points: {{ number_format($sale->customer->loyalty_points) }}</p>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-center gap-4">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Print Receipt
        </button>
        <a href="{{ route('sales.show', ['sale' => $sale, 'export' => 'pdf']) }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Download PDF
        </a>
        <a href="{{ route('pos.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to POS
        </a>
        <a href="{{ route('sales.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg font-semibold transition flex items-center gap-2">
            View All Sales
        </a>
    </div>
</div>

<!-- Print Styles -->
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #receipt, #receipt * {
        visibility: visible;
    }
    #receipt {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .no-print {
        display: none !important;
    }
}
</style>
@endsection

