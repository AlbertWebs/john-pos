@extends('layouts.app')

@section('title', 'Edit Vehicle Model')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('vehicle-models.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Vehicle Models
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Edit Vehicle Model</h1>
        <p class="text-gray-600 mt-1">{{ $vehicleModel->model_name }}</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form method="POST" action="{{ route('vehicle-models.update', $vehicleModel) }}">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Vehicle Make -->
                <div>
                    <label for="vehicle_make_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Vehicle Make <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="vehicle_make_id" 
                        id="vehicle_make_id"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vehicle_make_id') border-red-500 @enderror"
                    >
                        <option value="">Select Vehicle Make</option>
                        @foreach($vehicleMakes as $make)
                            <option value="{{ $make->id }}" {{ old('vehicle_make_id', $vehicleModel->vehicle_make_id) == $make->id ? 'selected' : '' }}>
                                {{ $make->make_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_make_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Model Name -->
                <div>
                    <label for="model_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Model Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="model_name" 
                        id="model_name"
                        value="{{ old('model_name', $vehicleModel->model_name) }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('model_name') border-red-500 @enderror"
                    >
                    @error('model_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Year Range -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="year_start" class="block text-sm font-medium text-gray-700 mb-2">Year Start</label>
                        <input 
                            type="number" 
                            name="year_start" 
                            id="year_start"
                            value="{{ old('year_start', $vehicleModel->year_start) }}"
                            min="1900"
                            max="2100"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('year_start') border-red-500 @enderror"
                        >
                        @error('year_start')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="year_end" class="block text-sm font-medium text-gray-700 mb-2">Year End</label>
                        <input 
                            type="number" 
                            name="year_end" 
                            id="year_end"
                            value="{{ old('year_end', $vehicleModel->year_end) }}"
                            min="1900"
                            max="2100"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('year_end') border-red-500 @enderror"
                        >
                        @error('year_end')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-4 pt-6 mt-6 border-t">
                <a href="{{ route('vehicle-models.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                    Update Vehicle Model
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

