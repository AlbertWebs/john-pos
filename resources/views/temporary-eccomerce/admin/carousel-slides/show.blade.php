@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Slide Details</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.carousel-slides.edit', $carouselSlide) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('admin.carousel-slides.index') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Back to Slides
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Slide Preview -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Slide Preview</h2>
            <div class="bg-gradient-to-r {{ $carouselSlide->background_classes }} p-6 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $carouselSlide->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ $carouselSlide->description }}</p>
                        @if($carouselSlide->button_text)
                            <a href="{{ $carouselSlide->button_link }}" class="inline-block bg-black text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800 transition-colors">
                                {{ $carouselSlide->button_text }}
                            </a>
                        @endif
                    </div>
                    @if($carouselSlide->image)
                        <div class="hidden md:block">
                            <img src="{{ Storage::url($carouselSlide->image) }}" alt="{{ $carouselSlide->title }}" class="max-w-xs object-cover rounded-lg">
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Slide Details -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Slide Information</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <p class="text-gray-900 font-medium">{{ $carouselSlide->title }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <p class="text-gray-900">{{ $carouselSlide->description }}</p>
                </div>

                @if($carouselSlide->button_text)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Button Text</label>
                        <p class="text-gray-900 font-medium">{{ $carouselSlide->button_text }}</p>
                    </div>
                @endif

                @if($carouselSlide->button_link)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Button Link</label>
                        <a href="{{ $carouselSlide->button_link }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                            {{ $carouselSlide->button_link }}
                        </a>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700">Background Color</label>
                    <div class="flex items-center space-x-2">
                        <div class="w-6 h-6 rounded-full bg-gradient-to-r {{ $carouselSlide->background_classes }}"></div>
                        <span class="text-gray-900 font-medium capitalize">{{ $carouselSlide->background_color }}</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Display Order</label>
                    <p class="text-gray-900 font-medium">{{ $carouselSlide->order }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <span class="px-2 py-1 text-xs rounded-full {{ $carouselSlide->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $carouselSlide->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Created</label>
                    <p class="text-gray-900">{{ $carouselSlide->created_at->format('F j, Y \a\t g:i A') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                    <p class="text-gray-900">{{ $carouselSlide->updated_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
            </div>

            @if($carouselSlide->image)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Slide Image</label>
                    <div class="border border-gray-300 rounded-lg p-4">
                        <img src="{{ Storage::url($carouselSlide->image) }}" alt="{{ $carouselSlide->title }}" 
                             class="max-w-full h-64 object-cover rounded-lg mx-auto">
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Actions</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.carousel-slides.edit', $carouselSlide) }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit Slide
            </a>
            
            <form action="{{ route('admin.carousel-slides.toggle-status', $carouselSlide) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors">
                    <i class="fas fa-{{ $carouselSlide->is_active ? 'eye-slash' : 'eye' }} mr-2"></i>
                    {{ $carouselSlide->is_active ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
            
            <form action="{{ route('admin.carousel-slides.destroy', $carouselSlide) }}" method="POST" class="inline" 
                  onsubmit="return confirm('Are you sure you want to delete this slide? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>Delete Slide
                </button>
            </form>
        </div>
    </div>
</div>
@endsection 