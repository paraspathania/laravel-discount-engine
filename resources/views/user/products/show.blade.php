@extends('layouts.app')

@section('content')

@php
    $discountService = app(\App\Services\DiscountService::class);
    $activeDiscounts = $discountService->getActiveDiscounts();
    $bestDiscountedPrice = $product->price;
    $applicableOffers = collect();
    foreach($activeDiscounts as $discount) {
        if ($discount->isSiteWide() || $discount->qualifiableProducts->contains('id', $product->id) || $discount->qualifiableCategories->contains('id', $product->category_id)) {
            $applicableOffers->push($discount);
            if(in_array($discount->type, ['percentage', 'fixed_amount'])) {
                $strategy = \App\Strategies\DiscountStrategyFactory::make($discount->type);
                $newPrice = $strategy->apply($product->price, $discount->value);
                if ($newPrice < $bestDiscountedPrice) { $bestDiscountedPrice = $newPrice; }
            }
        }
    }
    $imgSrc = asset('images/electronics.png');
    if ($product->category_id == 2) $imgSrc = asset('images/clothing.png');
    elseif ($product->category_id == 3) $imgSrc = asset('images/home.png');
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-8">
        <a href="{{ route('home') }}" class="hover:text-gray-600 transition-colors">Home</a>
        <span>/</span>
        <a href="{{ route('user.products.index') }}" class="hover:text-gray-600 transition-colors">Shop</a>
        <span>/</span>
        <span class="text-gray-700 font-medium truncate">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">

        {{-- Left: Image --}}
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden aspect-square flex items-center justify-center p-8">
            <img src="{{ $imgSrc }}" alt="{{ $product->name }}" class="w-full max-w-sm h-auto object-contain hover:scale-105 transition-transform duration-500">
        </div>

        {{-- Right: Details --}}
        <div class="flex flex-col justify-center">

            {{-- Category + title --}}
            <div class="mb-6">
                <span class="text-xs font-semibold tracking-widest text-indigo-600 uppercase">{{ $product->category->name ?? 'General' }}</span>
                <h1 class="text-3xl font-bold text-gray-900 mt-2 leading-snug">{{ $product->name }}</h1>
                <p class="text-xs text-gray-400 mt-1 font-mono">SKU: {{ $product->sku }}</p>
            </div>

            {{-- Price --}}
            <div class="mb-6 pb-6 border-b border-gray-100">
                @if($bestDiscountedPrice < $product->price)
                    <div class="flex items-baseline gap-3">
                        <span class="text-3xl font-bold text-gray-900">₹{{ number_format($bestDiscountedPrice / 100, 0) }}</span>
                        <span class="text-lg text-gray-400 line-through">₹{{ number_format($product->price / 100, 0) }}</span>
                        <span class="text-sm font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
                            Save ₹{{ number_format(($product->price - $bestDiscountedPrice) / 100, 0) }}
                        </span>
                    </div>
                @else
                    <span class="text-3xl font-bold text-gray-900">₹{{ number_format($product->price / 100, 0) }}</span>
                @endif
            </div>

            {{-- Stock & Add to Cart --}}
            @if($product->stock > 0)
                <div class="mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    <span class="text-sm font-medium text-green-700">In stock — {{ $product->stock }} left</span>
                </div>

                <div x-data="productDetail({{ $product->id }})" class="flex items-center gap-3 mb-6">
                    <select x-model="qty" class="text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-gray-50 w-20">
                        @for($i = 1; $i <= min(10, $product->stock); $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    <button @click="addToCart" :disabled="adding" class="flex-grow flex items-center justify-center gap-2 bg-gray-900 hover:bg-indigo-600 disabled:bg-gray-300 text-white text-sm font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                        <svg x-show="!adding" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                        <svg x-show="adding" class="w-4 h-4 animate-spin" style="display:none;" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                        <span x-text="adding ? 'Adding…' : 'Add to cart'"></span>
                    </button>
                </div>
            @else
                <div class="mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-red-400"></span>
                    <span class="text-sm font-medium text-red-600">Out of stock</span>
                </div>
                <button disabled class="w-full bg-gray-100 text-gray-400 text-sm font-semibold py-3 rounded-lg cursor-not-allowed mb-6">Currently unavailable</button>
            @endif

            {{-- Applicable Offers --}}
            @if($applicableOffers->count() > 0)
                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-5">
                    <p class="text-xs font-semibold text-indigo-700 uppercase tracking-wide mb-3">Offers on this product</p>
                    <ul class="space-y-2">
                        @foreach($applicableOffers as $offer)
                            <li class="flex items-start gap-2 text-sm text-indigo-800">
                                <svg class="w-4 h-4 text-indigo-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                <span><strong>{{ $offer->name }}</strong> — applied automatically at checkout</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Meta --}}
            <div class="mt-6 pt-6 border-t border-gray-100 grid grid-cols-2 gap-4 text-sm text-gray-500">
                <div>
                    <p class="text-xs text-gray-400 mb-1">Category</p>
                    <p class="font-medium text-gray-700">{{ $product->category->name ?? 'General' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Availability</p>
                    <p class="font-medium {{ $product->stock > 0 ? 'text-green-700' : 'text-red-600' }}">
                        {{ $product->stock > 0 ? $product->stock . ' units' : 'Out of stock' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@once
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('productDetail', (productId) => ({
        qty: 1,
        adding: false,
        addToCart() {
            this.adding = true;
            fetch('{{ route('user.cart.add') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                body: JSON.stringify({ product_id: productId, qty: parseInt(this.qty) })
            })
            .then(r => r.json())
            .then(data => {
                document.querySelectorAll('.cart-count-badge').forEach(el => { el.innerText = data.itemCount; el.classList.remove('hidden'); el.classList.add('flex'); });
                showToast('Added ' + this.qty + ' item(s) to cart');
            })
            .catch(() => showToast('Something went wrong', 'error'))
            .finally(() => this.adding = false);
        }
    }));
});
</script>
@endonce

@endsection
