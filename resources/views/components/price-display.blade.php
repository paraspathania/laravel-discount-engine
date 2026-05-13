@props(['originalPrice', 'discountedPrice' => null])

<div class="flex items-end gap-2">
    @if($discountedPrice && $discountedPrice < $originalPrice)
        <!-- Has Discount -->
        <span class="text-2xl font-extrabold text-indigo-600">₹{{ number_format($discountedPrice / 100, 2) }}</span>
        <span class="text-sm font-semibold text-gray-400 line-through mb-1">₹{{ number_format($originalPrice / 100, 2) }}</span>
    @else
        <!-- Original Price Only -->
        <span class="text-2xl font-extrabold text-gray-900">₹{{ number_format($originalPrice / 100, 2) }}</span>
    @endif
</div>
