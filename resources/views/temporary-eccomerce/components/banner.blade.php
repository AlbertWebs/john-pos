@props(['title', 'subtitle', 'buttonText', 'buttonLink', 'image' => null, 'backgroundColor' => 'bg-gray-800'])

<div class="{{ $backgroundColor }} text-white py-16">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <!-- Content -->
            <div class="text-center lg:text-left">
                <h2 class="text-4xl lg:text-5xl font-bold mb-4">{{ $title }}</h2>
                <p class="text-lg mb-8 text-gray-300">{{ $subtitle }}</p>
                <a href="{{ $buttonLink }}" class="inline-block bg-white text-gray-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    {{ $buttonText }}
                </a>
            </div>
            
            <!-- Image -->
            @if($image)
                <div class="flex justify-center lg:justify-end">
                    <img src="{{ $image }}" alt="Banner" class="max-w-md">
                </div>
            @endif
        </div>
    </div>
</div> 