@extends('layouts.app')

@section('title', 'Add Vehicle Make')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('vehicle-makes.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Vehicle Makes
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Add New Vehicle Make</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form method="POST" action="{{ route('vehicle-makes.store') }}">
            @csrf

            <div class="space-y-6">
                <!-- Make Name -->
                <div>
                    <label for="make_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Vehicle Make Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="make_name" 
                        id="make_name"
                        value="{{ old('make_name') }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('make_name') border-red-500 @enderror"
                        placeholder="e.g., Toyota"
                    >
                    @error('make_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-4 pt-6 mt-6 border-t">
                <a href="{{ route('vehicle-makes.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                    Create Vehicle Make
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

