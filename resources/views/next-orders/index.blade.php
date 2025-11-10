@extends('layouts.app')

@section('title', 'Next Orders')

@section('content')
<div class="space-y-6" x-data="nextOrdersPage({ ids: @json($nextOrders->pluck('id')) })">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Next Orders</h1>
            <p class="text-gray-600 mt-1">Track unavailable items requested by customers for future restock.</p>
        </div>
        <div class="flex items-center gap-3">
            <button 
                type="button"
                @click="markPurchased()"
                :disabled="selectedIds.length === 0"
                class="px-4 py-2 rounded-lg font-semibold transition flex items-center gap-2"
                :class="selectedIds.length === 0 ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700 text-white'"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Mark Purchased
            </button>
            <button 
                type="button"
                @click="$dispatch('open-next-order-modal', {});"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition flex items-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Manual Entry
            </button>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form method="GET" action="{{ route('next-orders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-700 block mb-1">Search</label>
                <input 
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by item, part number, or customer"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 block mb-1">Status</label>
                <select 
                    name="status"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">All</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">Filter</button>
                <a href="{{ route('next-orders.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium">Reset</a>
            </div>
        </form>
    </div>

    <div 
        x-show="selectedIds.length > 0" 
        x-cloak
        class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between"
    >
        <span x-text="selectedIds.length + ' item(s) selected'"></span>
        <button 
            type="button"
            class="text-sm font-semibold underline"
            @click="selectedIds = []"
        >
            Clear selection
        </button>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <input 
                                    type="checkbox"
                                    @change="toggleAll($event)"
                                    :checked="isAllSelected()"
                                    x-bind:indeterminate="isIndeterminate()"
                                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                >
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($nextOrders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <input 
                                type="checkbox"
                                value="{{ $order->id }}"
                                x-model="selectedIds"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            >
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900">{{ $order->item_name }}</div>
                            <div class="text-xs text-gray-500">
                                Part #: {{ $order->part_number ?? 'N/A' }}
                            </div>
                            @if($order->notes)
                                <div class="text-xs text-gray-500 mt-1">
                                    Notes: {{ $order->notes }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                Qty: {{ number_format($order->requested_quantity) }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $order->created_at->format('M d, Y H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $order->customer_name ?? 'Walk-in' }}</div>
                            @if($order->customer_contact)
                                <div class="text-xs text-blue-600">{{ $order->customer_contact }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ optional($order->requester)->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'ordered' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-gray-100 text-gray-600',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                            <div class="text-xs text-gray-500 mt-1 space-y-1">
                                @if($order->ordered_at)
                                    <div>Ordered: {{ $order->ordered_at->format('M d, Y') }}</div>
                                @endif
                                @if($order->fulfilled_at)
                                    <div>Fulfilled: {{ $order->fulfilled_at->format('M d, Y') }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button 
                                    type="button"
                                    @click="statusForm = { id: {{ $order->id }}, status: '{{ $order->status }}' }; showStatusModal = true;"
                                    class="px-3 py-1 text-sm border border-blue-500 text-blue-600 rounded-lg hover:bg-blue-50 transition"
                                >
                                    Update Status
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            No next orders recorded yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t px-6 py-4">
            {{ $nextOrders->links() }}
        </div>
    </div>

    <form x-ref="markPurchasedForm" method="POST" action="{{ route('next-orders.mark-purchased') }}" class="hidden">
        @csrf
    </form>

    <!-- Status Modal -->
    <div 
        x-show="showStatusModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 p-4"
    >
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6" @click.away="showStatusModal = false">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Update Status</h2>
            <form method="POST" :action="statusForm.id ? '{{ url('/next-orders') }}/' + statusForm.id + '/status' : '#'">
                @csrf
                @method('PATCH')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select 
                            name="status"
                            x-model="statusForm.status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            @foreach($statuses as $status)
                                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button 
                        type="button"
                        @click="showStatusModal = false"
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition"
                    >
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('nextOrdersPage', (config = {}) => ({
            showStatusModal: false,
            statusForm: { id: null, status: '' },
            selectedIds: [],
            allIds: (config.ids || []).map(id => String(id)),

            toggleAll(event) {
                if (event.target.checked) {
                    this.selectedIds = [...this.allIds];
                } else {
                    this.selectedIds = [];
                }
            },

            isAllSelected() {
                return this.allIds.length > 0 && this.selectedIds.length === this.allIds.length;
            },

            isIndeterminate() {
                return this.selectedIds.length > 0 && this.selectedIds.length < this.allIds.length;
            },

            markPurchased() {
                if (!this.selectedIds.length) {
                    return;
                }
                const form = this.$refs.markPurchasedForm;
                form.querySelectorAll('input[name="selected_ids[]"]').forEach(el => el.remove());
                this.selectedIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'selected_ids[]';
                    input.value = id;
                    form.appendChild(input);
                });
                form.submit();
            },
        }));

        document.addEventListener('next-order-created', () => {
            window.location.reload();
        });
    });
</script>
@endpush
@endsection

