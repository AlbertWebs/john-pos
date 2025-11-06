@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.contact-messages.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Messages
        </a>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <!-- Message Header -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $contactMessage->subject }}</h1>
                    <p class="text-gray-600 mt-1">From: {{ $contactMessage->full_name }} ({{ $contactMessage->email }})</p>
                </div>
                <div class="flex space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $contactMessage->source_badge_class }}">
                        {{ ucfirst(str_replace('_', ' ', $contactMessage->source)) }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $contactMessage->status_badge_class }}">
                        {{ ucfirst($contactMessage->status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6">
            <!-- Message Content -->
            <div class="lg:col-span-2">
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Message</h3>
                    <div class="prose max-w-none">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $contactMessage->message }}</p>
                    </div>
                </div>

                <!-- Admin Notes -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Admin Notes</h3>
                    <form action="{{ route('admin.contact-messages.update-notes', $contactMessage) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <textarea name="admin_notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Add notes about this message...">{{ $contactMessage->admin_notes }}</textarea>
                        <button type="submit" class="mt-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Save Notes
                        </button>
                    </form>
                </div>
            </div>

            <!-- Message Details -->
            <div class="space-y-6">
                <!-- Sender Information -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Sender Information</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Name</label>
                            <p class="text-gray-900">{{ $contactMessage->full_name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Email</label>
                            <p class="text-gray-900">{{ $contactMessage->email }}</p>
                        </div>
                        @if($contactMessage->phone)
                        <div>
                            <label class="text-sm font-medium text-gray-600">Phone</label>
                            <p class="text-gray-900">{{ $contactMessage->phone }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Message Details -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Message Details</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Subject</label>
                            <p class="text-gray-900">{{ $contactMessage->subject }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Source</label>
                            <p class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $contactMessage->source)) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Status</label>
                            <p class="text-gray-900">{{ ucfirst($contactMessage->status) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Date</label>
                            <p class="text-gray-900">{{ $contactMessage->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <!-- Status Update -->
                        <form action="{{ route('admin.contact-messages.update-status', $contactMessage) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="unread" {{ $contactMessage->status === 'unread' ? 'selected' : '' }}>Unread</option>
                                    <option value="read" {{ $contactMessage->status === 'read' ? 'selected' : '' }}>Read</option>
                                    <option value="replied" {{ $contactMessage->status === 'replied' ? 'selected' : '' }}>Replied</option>
                                </select>
                                <button type="submit" class="mt-2 w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    Update Status
                                </button>
                            </div>
                        </form>

                        <!-- Delete Message -->
                        <form action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors" onclick="return confirm('Are you sure you want to delete this message?')">
                                Delete Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 