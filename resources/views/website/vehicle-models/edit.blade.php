@extends('layouts.app')

@section('title', 'Edit Website Vehicle Model')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('website.vehicle-models.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Vehicle Models
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Edit Website Vehicle Model</h1>
        <p class="text-gray-600 mt-1">{{ $vehicleModel->model_name }}</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form method="POST" action="{{ route('website.vehicle-models.update', $vehicleModel) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                {{ session('success') }}
            </div>
            @endif

            <!-- Vehicle Model Info -->
            <div class="mb-6 pb-6 border-b">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Vehicle Model Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Model Name</label>
                        <p class="text-sm text-gray-900">{{ $vehicleModel->model_name }}</p>
                    </div>
                    @if($vehicleModel->vehicleMake)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Make</label>
                        <p class="text-sm text-gray-900">{{ $vehicleModel->vehicleMake->make_name }}</p>
                    </div>
                    @endif
                    @if($vehicleModel->year_start || $vehicleModel->year_end)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Years</label>
                        <p class="text-sm text-gray-900">
                            {{ $vehicleModel->year_start }}{{ $vehicleModel->year_start && $vehicleModel->year_end ? '-' : '' }}{{ $vehicleModel->year_end }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Image -->
            <div class="mb-6">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                    Vehicle Model Image
                </label>
                
                @if($vehicleModel->image)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                    <img src="{{ asset('storage/' . $vehicleModel->image) }}" alt="{{ $vehicleModel->model_name }}" class="h-48 w-48 object-cover rounded-lg border border-gray-300" id="current-image">
                </div>
                @endif

                <!-- Image Preview Box -->
                <div id="image-preview-container" class="mb-4 hidden">
                    <p class="text-sm text-gray-600 mb-2">New Image Preview:</p>
                    <div class="relative inline-block">
                        <img id="image-preview" src="" alt="Preview" class="object-contain rounded-lg border border-gray-300" style="max-width: 150px; max-height: 150px;">
                        <button type="button" id="remove-preview" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition" title="Remove preview">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <input 
                    type="file" 
                    name="image" 
                    id="image"
                    accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image') border-red-500 @enderror"
                >
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Upload a new image to replace the current one. Max size: 2MB. Formats: JPEG, PNG, GIF, WebP</p>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('website.vehicle-models.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                    Update Vehicle Model
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Image Preview Functionality
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    const imagePreviewContainer = document.getElementById('image-preview-container');
    const currentImage = document.getElementById('current-image');
    const removePreviewBtn = document.getElementById('remove-preview');

    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.classList.remove('hidden');
                    if (currentImage) {
                        currentImage.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    if (removePreviewBtn) {
        removePreviewBtn.addEventListener('click', function() {
            imagePreviewContainer.classList.add('hidden');
            imagePreview.src = '';
            if (imageInput) {
                imageInput.value = '';
            }
            if (currentImage) {
                currentImage.style.display = 'block';
            }
        });
    }
</script>
@endsection

