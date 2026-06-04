@extends('layouts.app')

@section('title', $supply->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('supplies.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Supplies
        </a>
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-900">{{ $supply->name }}</h1>
            <a href="{{ route('supplies.edit', $supply) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">Edit</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8 space-y-4">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <dt class="text-sm text-gray-500">Contact person</dt>
                <dd class="text-gray-900">{{ $supply->contact_person ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Phone</dt>
                <dd class="text-gray-900">{{ $supply->phone ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Email</dt>
                <dd class="text-gray-900">{{ $supply->email ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Status</dt>
                <dd>{{ ucfirst($supply->status) }}</dd>
            </div>
            <div class="md:col-span-2">
                <dt class="text-sm text-gray-500">Address</dt>
                <dd class="text-gray-900">{{ $supply->address ?? '—' }}</dd>
            </div>
            @if($supply->notes)
            <div class="md:col-span-2">
                <dt class="text-sm text-gray-500">Notes</dt>
                <dd class="text-gray-900">{{ $supply->notes }}</dd>
            </div>
            @endif
            <div>
                <dt class="text-sm text-gray-500">Linked inventory items</dt>
                <dd><span class="px-2 py-1 text-sm rounded-full bg-blue-100 text-blue-800">{{ $supply->inventory_count }} items</span></dd>
            </div>
        </dl>
    </div>
</div>
@endsection
