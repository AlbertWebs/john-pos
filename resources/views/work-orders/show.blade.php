@extends('layouts.app')

@section('title', 'Work Order - ' . $workOrder->work_order_number)

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('work-orders.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Work Orders
        </a>
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $workOrder->work_order_number }}</h1>
                <p class="text-gray-600 mt-1">Work Order Details</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('work-orders.edit', $workOrder) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                    Edit
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Work Order Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Work Order Information</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ 
                                $workOrder->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                ($workOrder->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                ($workOrder->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) 
                            }}">
                                {{ ucfirst(str_replace('_', ' ', $workOrder->status)) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $workOrder->created_at->format('M d, Y h:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Customer</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $workOrder->customer ? $workOrder->customer->name : '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Assigned To</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $workOrder->assignedTo ? $workOrder->assignedTo->name : 'Unassigned' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created By</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $workOrder->createdBy->name }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Vehicle Information -->
            @if($workOrder->vehicleMake || $workOrder->vehicle_registration)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Information</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($workOrder->vehicleMake)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Make & Model</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $workOrder->vehicleMake->make_name }}
                            @if($workOrder->vehicleModel)
                                - {{ $workOrder->vehicleModel->model_name }}
                            @endif
                        </dd>
                    </div>
                    @endif
                    @if($workOrder->vehicle_registration)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Registration</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $workOrder->vehicle_registration }}</dd>
                    </div>
                    @endif
                    @if($workOrder->vehicle_year)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Year</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $workOrder->vehicle_year }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
            @endif

            <!-- Description -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                <p class="text-sm text-gray-700">{{ $workOrder->description }}</p>
            </div>

            <!-- Work Order Items -->
            @if($workOrder->items->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Items</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($workOrder->items as $item)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $item->item_description }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                        {{ ucfirst($item->type) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-right text-gray-900">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 text-sm text-right text-gray-900">KES {{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-right font-semibold text-gray-900">KES {{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                            <tr class="bg-gray-50">
                                <td colspan="4" class="px-4 py-3 text-right font-semibold text-gray-900">Total</td>
                                <td class="px-4 py-3 text-right font-bold text-lg text-gray-900">
                                    KES {{ number_format($workOrder->getTotalCost(), 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Notes -->
            @if($workOrder->notes)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Notes</h3>
                <p class="text-sm text-gray-700">{{ $workOrder->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Cost Summary -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Cost Summary</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Estimated Cost</dt>
                        <dd class="text-sm text-gray-900">{{ $workOrder->estimated_cost ? 'KES ' . number_format($workOrder->estimated_cost, 2) : '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Actual Cost</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $workOrder->actual_cost ? 'KES ' . number_format($workOrder->actual_cost, 2) : '—' }}</dd>
                    </div>
                    <div class="flex justify-between pt-3 border-t">
                        <dt class="text-sm font-semibold text-gray-900">Items Total</dt>
                        <dd class="text-sm font-bold text-gray-900">KES {{ number_format($workOrder->getTotalCost(), 2) }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Dates -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Dates</h3>
                <dl class="space-y-3">
                    @if($workOrder->start_date)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $workOrder->start_date->format('M d, Y') }}</dd>
                    </div>
                    @endif
                    @if($workOrder->completion_date)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Completion Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $workOrder->completion_date->format('M d, Y') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection

