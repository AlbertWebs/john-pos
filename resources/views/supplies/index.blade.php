@extends('layouts.app')

@section('title', 'Supplies')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Supplies</h1>
            <p class="text-gray-600 mt-1">Manage suppliers and link them to inventory</p>
        </div>
        <a href="{{ route('supplies.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Supply
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($supplies as $supply)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $supply->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $supply->contact_person ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $supply->phone ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $supply->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($supply->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ $supply->inventory()->count() }} items
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('supplies.show', $supply) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                <a href="{{ route('supplies.edit', $supply) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <form method="POST" action="{{ route('supplies.destroy', $supply) }}" class="inline" onsubmit="return confirm('Delete this supply?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            No supplies yet.
                            <a href="{{ route('supplies.create') }}" class="text-blue-600 hover:text-blue-800">Add your first supply</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($supplies->hasPages())
        <div class="px-6 py-4 border-t">{{ $supplies->links() }}</div>
        @endif
    </div>
</div>
@endsection
