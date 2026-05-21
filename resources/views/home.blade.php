@extends('layouts.app')

@section('content')

{{-- Hero --}}
<section class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16 lg:py-24">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

            {{-- Left: Text --}}
            <div>
                <p class="text-sm font-medium text-indigo-600 mb-5 tracking-wide">India's smartest deals platform</p>
                <h1 class="font-serif text-5xl lg:text-6xl text-gray-900 leading-[1.1] tracking-tight mb-6">
                    Discounts that<br>actually work.
                </h1>
                <p class="text-lg text-gray-500 leading-relaxed mb-10 max-w-lg">
                    Real promotions, verified coupons, automatic savings at checkout — no code-hunting needed.
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('user.products.index') }}"
                       class="inline-flex items-center gap-2 bg-gray-900 hover:bg-indigo-600 text-white font-semibold px-6 py-3 rounded-lg transition-colors duration-200 text-sm">
                        Browse products
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route('user.offers.index') }}"
                       class="inline-flex items-center gap-2 border border-gray-300 hover:border-gray-400 text-gray-700 hover:text-gray-900 font-semibold px-6 py-3 rounded-lg transition-colors duration-200 text-sm bg-white">
                        See all offers
                    </a>
                </div>
            </div>

            {{-- Right: Illustration --}}
            <div class="hidden md:flex justify-center items-center">
                <img src="{{ asset('images/Hero.svg') }}"
                     alt="Shopping deals illustration"
                     class="w-full max-w-md object-contain drop-shadow-sm"
                     onerror="this.style.display='none'">
            </div>

        </div>
    </div>
</section>

{{-- Trust Bar --}}
<section class="border-b border-gray-100 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-wrap justify-center md:justify-between items-center gap-6 text-sm text-gray-500">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>Verified coupons only</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span>Discounts applied automatically</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                <span>Free shipping above ₹999</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <span>Secure checkout</span>
            </div>
        </div>
    </div>
</section>

{{-- Active Promotions --}}
@if($offers->count())
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex items-end justify-between mb-8">
        <div>
            <p class="text-xs font-semibold tracking-widest text-indigo-600 uppercase mb-1">Limited time</p>
            <h2 class="text-2xl font-bold text-gray-900">Active promotions</h2>
        </div>
        <a href="{{ route('user.offers.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1">
            View all <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($offers as $offer)
        <div class="bg-white border border-gray-200 rounded-xl p-6 hover:border-gray-300 hover:shadow-sm transition-all duration-200">
            <div class="flex items-start justify-between mb-4">
                <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-md
                    {{ $offer->type === 'percentage' ? 'bg-green-50 text-green-700' : ($offer->type === 'fixed_amount' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700') }}">
                    {{ str_replace('_', ' ', ucfirst($offer->type)) }}
                </span>
                @if($offer->is_stackable)
                    <span class="text-xs text-gray-400 font-medium">Stackable</span>
                @endif
            </div>
            <h3 class="font-semibold text-gray-900 mb-2">{{ $offer->name }}</h3>
            <p class="text-2xl font-bold mb-3 {{ $offer->type === 'percentage' ? 'text-green-600' : 'text-indigo-600' }}">
                @if($offer->type === 'percentage')
                    {{ $offer->value / 100 }}% off
                @elseif($offer->type === 'fixed_amount')
                    ₹{{ number_format($offer->value / 100, 0) }} off
                @else
                    Special deal
                @endif
            </p>
            @if($offer->ends_at)
                <p class="text-xs text-gray-400">Ends {{ $offer->ends_at->format('M j, Y') }}</p>
            @else
                <p class="text-xs text-gray-400">No expiry</p>
            @endif
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- Featured Products --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
    <div class="flex items-end justify-between mb-8">
        <div>
            <p class="text-xs font-semibold tracking-widest text-indigo-600 uppercase mb-1">Handpicked</p>
            <h2 class="text-2xl font-bold text-gray-900">Featured products</h2>
        </div>
        <a href="{{ route('user.products.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1">
            Shop all <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    @php
        $discountService = app(\App\Services\DiscountService::class);
        $activeDiscounts = $discountService->getActiveDiscounts();
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
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
                $imgSrc = $product->image_url;
            @endphp
            <div class="group bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md hover:border-gray-300 transition-all duration-200">
                <div class="relative aspect-[4/3] overflow-hidden bg-gray-50">
                    <a href="{{ route('user.products.show', $product) }}">
                        <img src="{{ $imgSrc }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </a>
                    <div class="absolute top-3 left-3">
                        <span class="text-xs font-medium bg-white text-gray-600 px-2 py-0.5 rounded border border-gray-200 shadow-sm">
                            {{ $product->category->name ?? 'General' }}
                        </span>
                    </div>
                    @if($bestDiscount)
                        <div class="absolute top-3 right-3">
                            <span class="text-xs font-bold bg-red-500 text-white px-2 py-0.5 rounded">
                                @if($bestDiscount->type === 'percentage')-{{ $bestDiscount->value / 100 }}%@else Sale @endif
                            </span>
                        </div>
                    @endif
                    @if($product->stock <= 0)
                        <div class="absolute inset-0 bg-white/70 flex items-center justify-center">
                            <span class="text-xs font-semibold text-gray-500 bg-white border border-gray-200 px-3 py-1 rounded">Out of stock</span>
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <a href="{{ route('user.products.show', $product) }}" class="font-medium text-gray-900 hover:text-indigo-600 transition-colors text-sm leading-snug block mb-2">
                        {{ $product->name }}
                    </a>
                    <div class="flex items-baseline gap-2 mb-3">
                        @if($bestDiscountedPrice < $product->price)
                            <span class="text-base font-bold text-gray-900">₹{{ number_format($bestDiscountedPrice / 100, 0) }}</span>
                            <span class="text-sm text-gray-400 line-through">₹{{ number_format($product->price / 100, 0) }}</span>
                        @else
                            <span class="text-base font-bold text-gray-900">₹{{ number_format($product->price / 100, 0) }}</span>
                        @endif
                    </div>
                    <div x-data="productCard({{ $product->id }})">
                        @if($product->stock <= 0)
                            <button disabled class="w-full text-xs font-semibold py-2.5 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">
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
            <div class="col-span-full text-center py-20 text-gray-400">
                <p class="text-base">No featured products yet.</p>
            </div>
        @endforelse
    </div>
</section>

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
                const btn = document.getElementById('cart-nav-btn');
                if (btn) { btn.classList.add('cart-shake'); setTimeout(() => btn.classList.remove('cart-shake'), 400); }
            })
            .catch(() => showToast('Something went wrong', 'error'))
            .finally(() => this.adding = false);
        }
    }));
});
</script>
@endonce

@endsection
