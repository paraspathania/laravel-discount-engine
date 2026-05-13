@extends('layouts.app')

@section('content')

{{-- Hero --}}
<section class="relative bg-white overflow-hidden min-h-[85vh] flex items-center border-b border-gray-100">
    {{-- Background Decorations --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-200 rounded-full blur-[100px] opacity-40 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-200 rounded-full blur-[100px] opacity-30 pointer-events-none"></div>
    
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8 w-full py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            
            {{-- Left Column: Text --}}
            <div class="max-w-2xl">
                <p class="text-xs font-bold tracking-widest text-indigo-600 uppercase mb-4">India's Smartest Deals Platform</p>
                <h1 class="font-serif text-5xl lg:text-7xl text-gray-900 leading-[1.1] mb-6 tracking-tight">
                    Deals that<br>actually <em class="not-italic text-indigo-600">save</em><br>you money.
                </h1>
                <p class="text-gray-500 text-lg leading-relaxed mb-8 max-w-xl">
                    Curated discounts, verified coupons, and real promotions — applied automatically at checkout. No hunting, no guesswork.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('user.products.index') }}" class="inline-flex items-center gap-2 bg-gray-900 hover:bg-indigo-600 text-white font-semibold px-7 py-3.5 rounded-xl transition-all duration-200 text-sm shadow-md hover:shadow-lg hover:-translate-y-0.5">
                        Browse Products &rarr;
                    </a>
                    <a href="{{ route('user.offers.index') }}" class="inline-flex items-center gap-2 border-2 border-gray-200 hover:border-gray-300 text-gray-700 hover:text-gray-900 font-semibold px-7 py-3.5 rounded-xl transition-all duration-200 text-sm bg-white hover:bg-gray-50">
                        See All Offers
                    </a>
                </div>
            </div>

            {{-- Right Column: Image --}}
            <div class="hidden md:flex justify-center relative w-full">
                <div class="relative w-full max-w-lg">
                   <img 
    src="{{ asset('images/Hero.svg') }}" 
    alt="Shopping Deals" 
    class="w-full max-w-lg mx-auto 
           drop-shadow-2xl rounded-2xl 
           animate-float"
    onerror="this.onerror=null; 
             this.style.display='none'"
>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- Trust Bar --}}
<section class="bg-gray-50 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        <div class="flex flex-wrap justify-center md:justify-between items-center gap-6 text-sm text-gray-500">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>Verified Coupons Only</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span>Discounts Applied Automatically</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Free Shipping Above ₹999</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <span>Secure Checkout</span>
            </div>
        </div>
    </div>
</section>

{{-- Active Promotions --}}
@if($offers->count())
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex items-end justify-between mb-8">
        <div>
            <p class="text-xs font-semibold tracking-widest text-indigo-600 uppercase mb-1">Limited Time</p>
            <h2 class="text-2xl font-bold text-gray-900">Active Promotions</h2>
        </div>
        <a href="{{ route('user.offers.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1">
            View all <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($offers as $offer)
        <div class="bg-white border border-gray-100 rounded-2xl p-6 hover:border-indigo-200 hover:shadow-md transition-all duration-200">
            <div class="flex items-start justify-between mb-4">
                <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-full
                    {{ $offer->type === 'percentage' ? 'bg-green-50 text-green-700' : ($offer->type === 'fixed_amount' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700') }}">
                    {{ str_replace('_', ' ', ucfirst($offer->type)) }}
                </span>
                @if($offer->is_stackable)
                    <span class="text-xs text-gray-400 font-medium">Stackable</span>
                @endif
            </div>
            <h3 class="font-semibold text-gray-900 mb-2">{{ $offer->name }}</h3>
            <p class="text-2xl font-bold mb-3
                {{ $offer->type === 'percentage' ? 'text-green-600' : 'text-indigo-600' }}">
                @if($offer->type === 'percentage')
                    {{ $offer->value / 100 }}% OFF
                @elseif($offer->type === 'fixed_amount')
                    ₹{{ number_format($offer->value / 100, 0) }} OFF
                @else
                    Special Deal
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
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
    <div class="flex items-end justify-between mb-8">
        <div>
            <p class="text-xs font-semibold tracking-widest text-indigo-600 uppercase mb-1">Handpicked</p>
            <h2 class="text-2xl font-bold text-gray-900">Featured Products</h2>
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
                $imgSrc = asset('images/electronics.png');
                if ($product->category_id == 2) $imgSrc = asset('images/clothing.png');
                elseif ($product->category_id == 3) $imgSrc = asset('images/home.png');
            @endphp
            <div class="group bg-white border border-gray-100 rounded-2xl overflow-hidden hover:shadow-lg hover:border-gray-200 transition-all duration-300">
                <div class="relative aspect-[4/3] overflow-hidden bg-gray-50">
                    <img src="{{ $imgSrc }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-3 left-3 flex gap-2">
                        <span class="text-xs font-medium bg-white/90 backdrop-blur-sm text-gray-700 px-2.5 py-1 rounded-full border border-gray-100">
                            {{ $product->category->name ?? 'General' }}
                        </span>
                    </div>
                    @if($bestDiscount)
                        <div class="absolute top-3 right-3">
                            <span class="text-xs font-bold bg-red-500 text-white px-2.5 py-1 rounded-full">
                                @if($bestDiscount->type === 'percentage')
                                    -{{ $bestDiscount->value / 100 }}%
                                @else
                                    Sale
                                @endif
                            </span>
                        </div>
                    @endif
                </div>
                <div class="p-5">
                    <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-indigo-600 transition-colors">
                        <a href="{{ route('user.products.show', $product) }}">{{ $product->name }}</a>
                    </h3>
                    <div class="flex items-center gap-2 mb-4">
                        @if($bestDiscountedPrice < $product->price)
                            <span class="text-lg font-bold text-gray-900">₹{{ number_format($bestDiscountedPrice / 100, 0) }}</span>
                            <span class="text-sm text-gray-400 line-through">₹{{ number_format($product->price / 100, 0) }}</span>
                        @else
                            <span class="text-lg font-bold text-gray-900">₹{{ number_format($product->price / 100, 0) }}</span>
                        @endif
                    </div>
                    <div x-data="productCard({{ $product->id }})">
                        <button @click="addToCart" :disabled="adding" class="w-full flex items-center justify-center gap-2 bg-gray-900 hover:bg-indigo-600 disabled:bg-gray-300 text-white text-sm font-semibold py-2.5 rounded-lg transition-colors duration-200">
                            <svg x-show="!adding" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                            <svg x-show="adding" class="w-4 h-4 animate-spin" style="display:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                            <span x-text="adding ? 'Adding...' : 'Add to cart'"></span>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-20 text-gray-400">
                <p class="text-lg">No featured products yet.</p>
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
