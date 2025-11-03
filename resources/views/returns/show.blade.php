@extends('layouts.app')

@section('title', 'Return Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('returns.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Returns
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Return Details</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <div class="space-y-6">
            <!-- Return Information -->
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Return Information</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Return Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $return->created_at->format('M d, Y h:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $return->status === 'completed' ? 'bg-green-100 text-green-800' : ($return->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ ucfirst($return->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Invoice Number</dt>
                        <dd class="mt-1">
                            <a href="{{ route('sales.show', $return->sale_id) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                {{ $return->sale->invoice_number }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Processed By</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $return->user->name }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Part Information -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Part Information</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Part Name</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $return->part->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Part Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $return->part->part_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Quantity Returned</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $return->quantity_returned }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Refund Amount</dt>
                        <dd class="mt-1 text-lg font-bold text-green-600">KES {{ number_format($return->refund_amount, 2) }}</dd>
                    </div>
                </dl>
            </div>

            @if($return->reason)
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Reason</h3>
                <p class="text-sm text-gray-700">{{ $return->reason }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

