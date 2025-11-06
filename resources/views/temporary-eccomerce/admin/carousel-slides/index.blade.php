@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Carousel Slides</h1>
        <a href="{{ route('admin.carousel-slides.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>Add New Slide
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Manage Slides</h2>
            <p class="text-sm text-gray-600">Drag and drop to reorder slides. Only active slides will be displayed on the homepage.</p>
        </div>

        <div id="slides-container" class="divide-y divide-gray-200">
            @forelse($slides as $slide)
                <div class="slide-item p-6 hover:bg-gray-50 transition-colors" data-id="{{ $slide->id }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <!-- Drag Handle -->
                            <div class="drag-handle cursor-move text-gray-400 hover:text-gray-600">
                                <i class="fas fa-grip-vertical text-lg"></i>
                            </div>

                            <!-- Slide Image -->
                            <div class="w-20 h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                @if($slide->image)
                                    <img src="{{ Storage::url($slide->image) }}" alt="{{ $slide->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Slide Info -->
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $slide->title }}</h3>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $slide->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $slide->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                        Order: {{ $slide->order }}
                                    </span>
                                </div>
                                <p class="text-gray-600 text-sm mb-2">{{ Str::limit($slide->description, 100) }}</p>
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span>Color: <span class="font-medium">{{ ucfirst($slide->background_color) }}</span></span>
                                    @if($slide->button_text)
                                        <span>Button: <span class="font-medium">{{ $slide->button_text }}</span></span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.carousel-slides.edit', $slide) }}" class="text-blue-600 hover:text-blue-800 p-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.carousel-slides.show', $slide) }}" class="text-green-600 hover:text-green-800 p-2">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.carousel-slides.toggle-status', $slide) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-yellow-600 hover:text-yellow-800 p-2">
                                    <i class="fas fa-{{ $slide->is_active ? 'eye-slash' : 'eye' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.carousel-slides.destroy', $slide) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this slide?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 p-2">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">
                    <i class="fas fa-images text-4xl mb-4"></i>
                    <h3 class="text-lg font-semibold mb-2">No Slides Found</h3>
                    <p class="text-sm">Create your first carousel slide to get started.</p>
                    <a href="{{ route('admin.carousel-slides.create') }}" class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Create First Slide
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('slides-container');
    
    if (container) {
        new Sortable(container, {
            animation: 150,
            handle: '.drag-handle',
            onEnd: function(evt) {
                const slides = Array.from(container.querySelectorAll('.slide-item')).map(item => item.dataset.id);
                
                fetch('{{ route("admin.carousel-slides.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ slides: slides })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update order numbers
                        container.querySelectorAll('.slide-item').forEach((item, index) => {
                            const orderSpan = item.querySelector('.bg-blue-100');
                            if (orderSpan) {
                                orderSpan.textContent = `Order: ${index + 1}`;
                            }
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    }
});
</script>
@endsection 