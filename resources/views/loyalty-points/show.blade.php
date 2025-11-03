@extends('layouts.app')

@section('title', 'Loyalty Points - ' . $customer->name)

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('loyalty-points.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Loyalty Points
        </a>
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $customer->name }}</h1>
                <p class="text-gray-600 mt-1">{{ $customer->phone ?? 'No phone' }} • {{ $customer->email ?? 'No email' }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Points Statistics -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Points Statistics</h2>
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Current Points</dt>
                        <dd class="mt-1 text-3xl font-bold text-green-600">{{ number_format($stats['total_points']) }} pts</dd>
                        <p class="text-xs text-gray-500 mt-1">≈ KES {{ number_format($stats['total_points']) }}</p>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Points from Sales</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($stats['points_earned_from_sales']) }} pts</dd>
                        <p class="text-xs text-gray-500 mt-1">Based on purchases</p>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Spent</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">KES {{ number_format($stats['total_spent'], 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Transactions</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($stats['total_transactions']) }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Points History -->
            @if(count($pointsHistory) > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Points History</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Points</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pointsHistory as $history)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $history['date']->format('M d, Y') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $history['transaction'] }}</td>
                                <td class="px-4 py-3 text-sm text-right text-gray-900">KES {{ number_format($history['amount'], 2) }}</td>
                                <td class="px-4 py-3 text-sm text-right font-semibold text-green-600">+{{ number_format($history['points']) }} pts</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                        Earned
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Points Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Points Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Redeem Points -->
                    <div class="border rounded-lg p-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Redeem Points</h3>
                        <p class="text-sm text-gray-600 mb-4">Convert points to discount (1 point = 1 KES)</p>
                        <button @click="showRedeemModal = true" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg font-medium transition">
                            Redeem Points
                        </button>
                    </div>

                    <!-- Adjust Points -->
                    <div class="border rounded-lg p-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Adjust Points</h3>
                        <p class="text-sm text-gray-600 mb-4">Manually add or deduct points</p>
                        <button @click="showAdjustModal = true" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition">
                            Adjust Points
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Info</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Available Points</dt>
                        <dd class="mt-1 text-2xl font-bold text-green-600">{{ number_format($customer->loyalty_points) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Points Value</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">KES {{ number_format($customer->loyalty_points) }}</dd>
                        <p class="text-xs text-gray-500 mt-1">1 point = 1 KES discount</p>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>

<!-- Redeem Modal -->
<div x-show="showRedeemModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-data="{ showRedeemModal: false }">
    <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full mx-4" @click.away="showRedeemModal = false">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Redeem Points</h3>
        <form method="POST" action="{{ route('loyalty-points.redeem', $customer) }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="points" class="block text-sm font-medium text-gray-700 mb-2">
                        Points to Redeem <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="points" 
                        id="points"
                        required
                        min="1"
                        max="{{ $customer->loyalty_points }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Enter points"
                        oninput="document.getElementById('discount_amount').value = this.value"
                    >
                    <p class="mt-1 text-xs text-gray-500">Max: {{ number_format($customer->loyalty_points) }} points</p>
                </div>
                <div>
                    <label for="discount_amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Discount Amount (KES) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        step="0.01"
                        name="discount_amount" 
                        id="discount_amount"
                        required
                        min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="0.00"
                        readonly
                    >
                    <p class="mt-1 text-xs text-gray-500">1 point = 1 KES</p>
                </div>
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea 
                        name="notes" 
                        id="notes"
                        rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Reason for redemption..."
                    ></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-4 mt-6 pt-6 border-t">
                <button type="button" @click="showRedeemModal = false" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition">
                    Redeem Points
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Adjust Modal -->
<div x-show="showAdjustModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-data="{ showAdjustModal: false }">
    <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full mx-4" @click.away="showAdjustModal = false">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Adjust Points</h3>
        <form method="POST" action="{{ route('loyalty-points.adjust', $customer) }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="adjust_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Adjustment Type <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="type" 
                        id="adjust_type"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="add">Add Points</option>
                        <option value="deduct">Deduct Points</option>
                    </select>
                </div>
                <div>
                    <label for="adjust_points" class="block text-sm font-medium text-gray-700 mb-2">
                        Points <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="points" 
                        id="adjust_points"
                        required
                        min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Enter points"
                    >
                </div>
                <div>
                    <label for="adjust_reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Reason <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="reason" 
                        id="adjust_reason"
                        required
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Reason for adjustment..."
                    ></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-4 mt-6 pt-6 border-t">
                <button type="button" @click="showAdjustModal = false" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                    Adjust Points
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function loyaltyPointsPage() {
    return {
        showRedeemModal: false,
        showAdjustModal: false
    }
}
</script>
@endsection

