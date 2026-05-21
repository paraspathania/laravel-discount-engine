@extends('layouts.app')

@section('content')

{{-- Page Header --}}
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Shop</h1>
        @if($products->total() > 0)
            <p class="text-sm text-gray-400 mb-6">{{ $products->total() }} products available</p>
        @else
            <p class="text-sm text-gray-400 mb-6">Browse our catalogue</p>
        @endif

        {{-- Search & Filter --}}
        <form action="{{ route('user.products.index') }}" method="GET">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-grow">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products…" class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                </div>
                <select name="category_id" class="text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white sm:w-48">
                    <option value="">All categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="text-sm font-semibold bg-gray-900 hover:bg-indigo-600 text-white px-5 py-2.5 rounded-lg transition-colors duration-200">Filter</button>
                @if(request('search') || request('category_id'))
                    <a href="{{ route('user.products.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800 px-4 py-2.5 rounded-lg border border-gray-200 hover:border-gray-300 transition-colors text-center">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Products Grid --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    @php
        $discountService = app(\App\Services\DiscountService::class);
        $activeDiscounts = $discountService->getActiveDiscounts();
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @forelse($products as $product)
            @php
                $bestDiscountedPrice = $product->price;
                $bestDiscount = null;
                foreach($activeDiscounts as $discount) {
                    if ($discount->isSiteWide() || $discount->qualifiableProducts->contains('id', $product->id) || $discount->qualifiableCategories->contains('id', $product->category_id)) {
                        if(in_array($discount->type, ['percentage', 'fixed_amount'])) {
                            $strategy = \App\Strategies\DiscountStrategyFactory::make($discount->type);
                            $newPrice = $strategy->apply($product->price, $discount->value);
                            if ($newPrice < $bestDiscountedPrice) { $bestDiscountedPrice = $newPrice; $bestDiscount = $discount; }
                        }
                    }
                }
                $imgSrc = asset('images/electronics.png');
                if ($product->category_id == 2) $imgSrc = asset('images/clothing.png');
                elseif ($product->category_id == 3) $imgSrc = asset('images/home.png');
            @endphp

            <div class="group bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md hover:border-gray-300 transition-all duration-200 flex flex-col">
                {{-- Image --}}
                <div class="relative aspect-square overflow-hidden bg-gray-50">
                    <a href="{{ route('user.products.show', $product) }}">
                        <img src="{{ $imgSrc }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </a>
                    {{-- Category Badge --}}
                    <div class="absolute top-2.5 left-2.5">
                        <span class="text-[11px] font-medium bg-white text-gray-600 px-2 py-0.5 rounded border border-gray-200 shadow-sm">
                            {{ $product->category->name ?? 'General' }}
                        </span>
                    </div>
                    {{-- Discount Badge --}}
                    @if($bestDiscount)
                        <div class="absolute top-2.5 right-2.5">
                            <span class="text-[11px] font-bold bg-red-500 text-white px-2 py-0.5 rounded">
                                @if($bestDiscount->type === 'percentage')-{{ $bestDiscount->value / 100 }}%@else Sale @endif
                            </span>
                        </div>
                    @endif
                    {{-- Out of Stock Overlay --}}
                    @if($product->stock <= 0)
                        <div class="absolute inset-0 bg-white/70 flex items-center justify-center">
                            <span class="text-xs font-semibold text-gray-500 bg-white border border-gray-200 px-3 py-1 rounded">Out of stock</span>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="p-4 flex flex-col flex-grow">
                    <a href="{{ route('user.products.show', $product) }}" class="font-medium text-gray-900 hover:text-indigo-600 transition-colors mb-1 text-sm leading-snug line-clamp-2">
                        {{ $product->name }}
                    </a>
                    <div class="flex items-baseline gap-2 mt-1 mb-4">
                        @if($bestDiscountedPrice < $product->price)
                            <span class="text-base font-bold text-gray-900">₹{{ number_format($bestDiscountedPrice / 100, 0) }}</span>
                            <span class="text-xs text-gray-400 line-through">₹{{ number_format($product->price / 100, 0) }}</span>
                        @else
                            <span class="text-base font-bold text-gray-900">₹{{ number_format($product->price / 100, 0) }}</span>
                        @endif
                    </div>
                    <div class="mt-auto" x-data="productCard({{ $product->id }})">
                        @if($product->stock <= 0)
                            <button disabled class="w-full flex items-center justify-center text-xs font-semibold py-2.5 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">
                                Out of stock
                            </button>
                        @else
                            <button @click="addToCart" :disabled="adding"
                                class="w-full flex items-center justify-center gap-1.5 text-xs font-semibold py-2.5 rounded-lg transition-colors duration-200 bg-gray-900 hover:bg-indigo-600 text-white disabled:opacity-60">
                                <svg x-show="!adding" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                                <svg x-show="adding" class="w-3.5 h-3.5 animate-spin" style="display:none;" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                                <span x-text="adding ? 'Adding…' : 'Add to cart'">Add to cart</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

        @empty
            <div class="col-span-full py-24 text-center">
                <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-1">No products found</h3>
                <p class="text-sm text-gray-400 mb-5">Try a different search term or clear the filters.</p>
                <a href="{{ route('user.products.index') }}" class="text-sm font-medium text-indigo-600 hover:underline">Clear filters</a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
        <div class="mt-10">
            {{ $products->withQueryString()->links() }}
        </div>
    @endif
</div>

@once
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('productCard', (productId) => ({
        adding: false,
        addToCart() {
            this.adding = true;
            fetch('{{ route('user.cart.add') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                body: JSON.stringify({ product_id: productId, qty: 1 })
            })
            .then(r => r.json())
            .then(data => {
                document.querySelectorAll('.cart-count-badge').forEach(el => { el.innerText = data.itemCount; el.classList.remove('hidden'); el.classList.add('flex'); });
                showToast('Added to cart');
            })
            .catch(() => showToast('Something went wrong', 'error'))
            .finally(() => this.adding = false);
        }
    }));
});
</script>
@endonce

@endsection
