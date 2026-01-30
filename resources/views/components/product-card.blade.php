@props(['product'])

<article class="bg-white border border-gray-200 overflow-hidden group" data-product-id="{{ $product->id }}" itemscope itemtype="https://schema.org/Product">
    <div class="relative">
        <!-- Product Image with Link - Square aspect ratio -->
        <a href="{{ route('products.show', $product->slug) }}" 
        class="block flex justify-center items-center w-full bg-gray-100">
            <img src="{{ $product->main_image_url }}"
             alt="{{ $product->name }}" 
             class="w-[95%] aspect-square object-contain"
             itemprop="image"
             loading="lazy"
             onerror="this.src='https://via.placeholder.com/300x200/cccccc/ffffff?text=No+Image'; console.log('Image failed to load:', this.src);"
             onload="console.log('Image loaded successfully:', this.src);">
        </a>
        
        <!-- Sale Badge -->
        @if($product->badge)
            <div class="absolute top-2 left-2">
                <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                    {{ $product->badge }}
                </span>
            </div>
        @endif
        
        <!-- Action Buttons Container - Hidden by default, animated on hover -->
        <div class="absolute top-2 right-2 flex flex-col space-y-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-out">
            <!-- Wishlist Button -->
            <button class="wishlist-btn bg-white rounded-full p-2 shadow-md hover:bg-red-50 transition-all duration-200 z-10 w-8 h-8 flex items-center justify-center" 
                    onclick="toggleWishlist('{{ $product->id }}', '{{ $product->name }}')"
                    data-product-id="{{ $product->id }}"
                    title="Add to Wishlist">
                <i class="fas fa-heart text-gray-400 hover:text-red-500 transition-colors text-sm wishlist-icon"></i>
            </button>
            
            <!-- Remove from Wishlist Button (hidden by default) -->
            <button class="remove-wishlist-btn bg-white rounded-full p-2 shadow-md hover:bg-red-50 transition-all duration-200 z-10 w-8 h-8 flex items-center justify-center hidden" 
                    onclick="removeFromWishlist('{{ $product->id }}')"
                    data-product-id="{{ $product->id }}"
                    title="Remove from Wishlist">
                <i class="fas fa-times text-red-500 text-sm"></i>
            </button>
            
            <!-- Add to Cart Button -->
            <button class="bg-white rounded-full p-2 shadow-md hover:bg-blue-50 transition-all duration-200 z-10 w-8 h-8 flex items-center justify-center"
                    onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, '{{ $product->main_image_url }}')"
                    title="Add to Cart">
                <i class="fas fa-shopping-cart text-gray-400 hover:text-blue-600 transition-colors text-sm"></i>
            </button>
        </div>
    </div>
    
    <!-- Content Section - Longer to make card rectangular -->
    <div class="p-3 sm:p-4 flex flex-col">
        <!-- Product Name with Link -->
        <a href="{{ route('products.show', $product->slug) }}" class="">
            <h3 class="text-xs sm:text-sm font-medium text-gray-900 hover:text-blue-600 transition-colors line-clamp-2" itemprop="name">{{ $product->name }}</h3>
        </a>
        
        <!-- Rating -->
        <div class="flex items-center" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
            @for($i = 1; $i <= 5; $i++)
                @if($i <= $product->rating)
                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                @else
                    <i class="far fa-star text-gray-300 text-xs"></i>
                @endif
            @endfor
            <span class="text-xs text-gray-500 ml-1 text-xs">({{ $product->reviews_count }})</span>
            <meta itemprop="ratingValue" content="{{ $product->rating }}">
            <meta itemprop="reviewCount" content="{{ $product->reviews_count }}">
            <meta itemprop="bestRating" content="5">
            <meta itemprop="worstRating" content="1">
        </div>
        
        <!-- Price -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-2">
                @if($product->old_price)
                    <span class="text-xs sm:text-sm text-gray-500 line-through order-2 sm:order-1">{{ $product->formatted_old_price }}</span>
                @endif
                <span class="text-base sm:text-lg font-bold text-gray-900 order-1 sm:order-2" itemprop="price" content="{{ $product->price }}">
                    <span itemprop="priceCurrency" content="KES">{{ $product->formatted_price }}</span>
                </span>
            </div>
        </div>
    </div>
    
    <!-- Hidden structured data -->
    <meta itemprop="url" content="{{ route('products.show', $product->slug) }}">
    <meta itemprop="availability" content="{{ $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}">
    @if($product->category)
        <meta itemprop="category" content="{{ $product->category->name }}">
    @endif
</article>
