@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
            <p class="text-gray-600">User details and information</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.users.edit', $user) }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Edit User
            </a>
            <a href="{{ route('admin.users.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                Back to Users
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">User Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Full Name</p>
                        <p class="font-medium">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email Address</p>
                        <p class="font-medium">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Role</p>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $user->is_admin ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $user->is_admin ? 'Admin' : 'User' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email Verification</p>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- User Orders -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">User Orders ({{ $user->orders->count() }})</h2>
                
                @if($user->orders->count() > 0)
                    <div class="space-y-4">
                        @foreach($user->orders->take(5) as $order)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-medium text-gray-900">Order #{{ $order->order_number }}</h3>
                                        <p class="text-sm text-gray-600">{{ $order->created_at->format('M j, Y g:i A') }}</p>
                                        <p class="text-sm text-gray-600">{{ $order->orderItems->count() }} items</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium text-lg text-green-600">{{ $order->formatted_total_amount }}</p>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $order->status_color }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($user->orders->count() > 5)
                            <div class="text-center">
                                <a href="{{ route('admin.orders.index', ['user' => $user->id]) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    View all {{ $user->orders->count() }} orders →
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-shopping-bag text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">No orders found for this user.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- User Stats -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">User Stats</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Total Orders</p>
                        <p class="font-medium text-lg">{{ $user->orders->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Spent</p>
                        <p class="font-medium text-lg text-green-600">{{ $user->orders->sum('total_amount') ? 'KES ' . number_format($user->orders->sum('total_amount'), 2) : 'KES 0.00' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Average Order Value</p>
                        <p class="font-medium">{{ $user->orders->count() > 0 ? 'KES ' . number_format($user->orders->avg('total_amount'), 2) : 'KES 0.00' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Last Order</p>
                        <p class="font-medium">{{ $user->orders->latest()->first() ? $user->orders->latest()->first()->created_at->format('M j, Y') : 'Never' }}</p>
                    </div>
                </div>
            </div>

            <!-- User Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Account Details</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Account Created</p>
                        <p class="font-medium">{{ $user->created_at->format('M j, Y g:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Last Updated</p>
                        <p class="font-medium">{{ $user->updated_at->format('M j, Y g:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">User ID</p>
                        <p class="font-medium text-sm text-gray-500">{{ $user->id }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                <div class="space-y-2">
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Edit User
                    </a>
                    @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')"
                                    class="block w-full text-center bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                Delete User
                            </button>
                        </form>
                    @else
                        <div class="text-center text-sm text-gray-500 py-2">
                            Cannot delete your own account
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 