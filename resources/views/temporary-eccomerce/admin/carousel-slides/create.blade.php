@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Create New Slide</h1>
        <a href="{{ route('admin.carousel-slides.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to Slides
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="{{ route('admin.carousel-slides.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Slide Title *</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea id="description" name="description" rows="4" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Button Text -->
                    <div>
                        <label for="button_text" class="block text-sm font-medium text-gray-700 mb-2">Button Text</label>
                        <input type="text" id="button_text" name="button_text" value="{{ old('button_text') }}"
                               placeholder="e.g., Shop Now, Learn More"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('button_text')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Button Link -->
                    <div>
                        <label for="button_link" class="block text-sm font-medium text-gray-700 mb-2">Button Link</label>
                        <input type="text" id="button_link" name="button_link" value="{{ old('button_link') }}"
                               placeholder="e.g., /products, https://example.com"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('button_link')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Image Upload -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Slide Image *</label>
                        <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center overflow-hidden">
                            <div id="image-preview" class="hidden mb-4">
                                <img id="preview-img" src="" alt="Preview" class="max-w-full h-48 object-cover rounded-lg mx-auto">
                            </div>
                            <div id="upload-placeholder">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-4"></i>
                                <p class="text-sm text-gray-600 mb-2">Click to upload or drag and drop</p>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                            <input
                                type="file"
                                id="image"
                                name="image"
                                accept="image/*"
                                required
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                style="z-index: 10;"
                            >
                        </div>
                        @error('image')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- Background Color -->
                    <div>
                        <label for="background_color" class="block text-sm font-medium text-gray-700 mb-2">Background Color *</label>
                        <select id="background_color" name="background_color" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select a color</option>
                            <option value="blue" {{ old('background_color') == 'blue' ? 'selected' : '' }}>Blue</option>
                            <option value="green" {{ old('background_color') == 'green' ? 'selected' : '' }}>Green</option>
                            <option value="purple" {{ old('background_color') == 'purple' ? 'selected' : '' }}>Purple</option>
                            <option value="red" {{ old('background_color') == 'red' ? 'selected' : '' }}>Red</option>
                            <option value="yellow" {{ old('background_color') == 'yellow' ? 'selected' : '' }}>Yellow</option>
                            <option value="pink" {{ old('background_color') == 'pink' ? 'selected' : '' }}>Pink</option>
                            <option value="indigo" {{ old('background_color') == 'indigo' ? 'selected' : '' }}>Indigo</option>
                            <option value="gray" {{ old('background_color') == 'gray' ? 'selected' : '' }}>Gray</option>
                        </select>
                        @error('background_color')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order -->
                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                        <input type="number" id="order" name="order" value="{{ old('order') }}" min="0"
                               placeholder="Leave empty for auto-assignment"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('order')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Active (display on homepage)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Preview Section -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Preview</h3>
                <div id="slide-preview" class="bg-gray-100 rounded-lg p-4">
                    <div class="text-center text-gray-500">
                        <i class="fas fa-eye text-2xl mb-2"></i>
                        <p>Preview will appear here once you fill in the details</p>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.carousel-slides.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Create Slide
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    const uploadPlaceholder = document.getElementById('upload-placeholder');
    const slidePreview = document.getElementById('slide-preview');

    // Image preview functionality
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('hidden');
                uploadPlaceholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Live preview functionality
    function updatePreview() {
        const title = document.getElementById('title').value;
        const description = document.getElementById('description').value;
        const buttonText = document.getElementById('button_text').value;
        const backgroundColor = document.getElementById('background_color').value;
        const imageSrc = previewImg.src;

        if (title || description || buttonText || backgroundColor || imageSrc !== '') {
            const colorClasses = {
                'blue': 'from-blue-100 to-blue-200',
                'green': 'from-green-100 to-green-200',
                'purple': 'from-purple-100 to-purple-200',
                'red': 'from-red-100 to-red-200',
                'yellow': 'from-yellow-100 to-yellow-200',
                'pink': 'from-pink-100 to-pink-200',
                'indigo': 'from-indigo-100 to-indigo-200',
                'gray': 'from-gray-100 to-gray-200'
            };

            const bgClass = colorClasses[backgroundColor] || 'from-blue-100 to-blue-200';

            slidePreview.innerHTML = `
                <div class="bg-gradient-to-r ${bgClass} p-6 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">${title || 'Slide Title'}</h3>
                            <p class="text-gray-600 mb-4">${description || 'Slide description will appear here'}</p>
                            ${buttonText ? `<button class="bg-black text-white px-4 py-2 rounded-lg text-sm">${buttonText}</button>` : ''}
                        </div>
                        ${imageSrc !== '' ? `<div class="hidden md:block"><img src="${imageSrc}" alt="Preview" class="max-w-xs object-cover rounded-lg"></div>` : ''}
                    </div>
                </div>
            `;
        }
    }

    // Add event listeners for live preview
    ['title', 'description', 'button_text', 'background_color'].forEach(id => {
        document.getElementById(id).addEventListener('input', updatePreview);
    });
});
</script>
@endsection
