@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">My Wishlist</h1>
            <p class="text-gray-600">Manage your saved products</p>
        </div>

        @if(count($products) > 0)
            <!-- Wishlist Items -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900">
                    {{ count($products) }} items in your wishlist
                </h2>
                <button onclick="clearWishlist()" class="text-red-600 hover:text-red-700 font-medium text-sm">
                    <i class="fas fa-trash mr-1"></i>
                    Clear All
                </button>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="max-w-md mx-auto">
                    <i class="fas fa-heart text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Your wishlist is empty</h3>
                    <p class="text-gray-600 mb-6">Start adding products to your wishlist to see them here.</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 transition-colors">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Browse Products
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function clearWishlist() {
    document.cookie = 'wishlist=; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT';
    location.reload();
}

// Override the global removeFromWishlist function for wishlist page
window.removeFromWishlist = function(productId) {
    // Call the global function first
    const existingIndex = window.wishlist ? window.wishlist.findIndex(item => item.id === productId) : -1;
    
    if (existingIndex !== -1) {
        // Remove from wishlist using global function
        window.wishlist.splice(existingIndex, 1);
        
        // Save to cookies
        const wishlistData = encodeURIComponent(JSON.stringify(window.wishlist));
        document.cookie = `wishlist=${wishlistData}; path=/; max-age=${60 * 60 * 24 * 30}`; // 30 days
        
        // Find and remove the product card from the DOM
        const productCard = document.querySelector(`[data-product-id="${productId}"]`);
        if (productCard) {
            productCard.remove();
        }
        
        // Update the count display
        const countElement = document.querySelector('h2 span');
        if (countElement) {
            const currentCount = parseInt(countElement.textContent);
            countElement.textContent = currentCount - 1;
        }
        
        // Update wishlist count in header
        const wishlistCountElements = document.querySelectorAll('.wishlist-count');
        wishlistCountElements.forEach(element => {
            element.textContent = window.wishlist.length;
        });
        
        // If no products left, show empty state
        const remainingProducts = document.querySelectorAll('.grid > div');
        if (remainingProducts.length === 0) {
            location.reload(); // Reload to show empty state
        }
        
        showNotification('Removed from wishlist', 'info');
    }
};
</script>
@endsection 