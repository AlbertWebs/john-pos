@props(['sale', 'defaultAmount' => null])

<form method="POST" action="{{ route('debtors.sales.payments.store', $sale) }}" class="bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-4">
    @csrf
    <h4 class="font-semibold text-gray-900">Record payment — {{ $sale->invoice_number }}</h4>
    <p class="text-sm text-gray-600">Balance due: <strong class="text-red-600">KES {{ number_format($sale->balanceDue(), 2) }}</strong></p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Amount (KES) <span class="text-red-500">*</span></label>
            <input type="number" name="amount" step="0.01" min="0.01" max="{{ $sale->balanceDue() }}" required
                value="{{ old('amount', $defaultAmount ?? $sale->balanceDue()) }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg @error('amount') border-red-500 @enderror">
            @error('amount')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Payment method <span class="text-red-500">*</span></label>
            <select name="payment_method" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                <option value="Cash" @selected(old('payment_method') === 'Cash')>Cash</option>
                <option value="M-Pesa" @selected(old('payment_method') === 'M-Pesa')>M-Pesa</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Payment date</label>
            <input type="date" name="payment_date" value="{{ old('payment_date', now()->toDateString()) }}" max="{{ now()->toDateString() }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Reference (M-Pesa code, etc.)</label>
            <input type="text" name="transaction_reference" value="{{ old('transaction_reference') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Optional">
        </div>
    </div>
    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold">
        Record payment on this sale
    </button>
</form>
