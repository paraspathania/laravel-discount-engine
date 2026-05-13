@extends('layouts.app')

@section('content')
    <!-- Hero Banner -->
    <div class="relative bg-[#0a0a0a] py-32 overflow-hidden border-b border-gray-800">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/40 to-purple-900/40"></div>
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-full bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-500/20 via-transparent to-transparent"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-5xl md:text-7xl font-extrabold text-white tracking-tight mb-6">
                Discover <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400">Premium Tech</span>
            </h1>
            <p class="mt-4 text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto mb-10 font-medium">
                Experience unparalleled quality and design. Elevate your lifestyle with our curated collection.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('user.products.index') }}" class="px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-full shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-105 transition-all duration-300">
                    Explore Collection
                </a>
                <a href="{{ route('user.offers.index') }}" class="px-8 py-4 bg-white/5 backdrop-blur-sm border border-white/10 text-white font-bold rounded-full hover:bg-white/10 transition-all duration-300">
                    View Offers
                </a>
            </div>
        </div>
    </div>

    <!-- Featured Offers Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex justify-between items-end mb-8 border-b border-gray-200 pb-4">
            <h2 class="text-3xl font-extrabold text-gray-900">Current Promotions</h2>
            <a href="{{ route('user.offers.index') }}" class="text-indigo-600 hover:text-indigo-800 font-bold flex items-center">
                View All Offers &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($offers as $offer)
                <x-card class="bg-gradient-to-br from-white to-gray-50 border-gray-200 border-2 hover:border-indigo-500">
                    <div class="mb-4 flex justify-between items-start">
                        <span class="text-sm font-black uppercase text-indigo-600 tracking-wider">Promo</span>
                        @if($offer->is_stackable)
                            <x-badge color="green" text="Stackable!" />
                        @endif
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $offer->name }}</h3>
                    <p class="text-gray-600 mb-4 font-medium">
                        @if($offer->type === 'percentage')
                            Get <span class="text-green-600 font-extrabold">{{ $offer->value / 100 }}% OFF</span>
                        @elseif($offer->type === 'fixed_amount')
                            Save <span class="text-green-600 font-extrabold">₹{{ number_format($offer->value / 100, 2) }}</span>
                        @else
                            <span class="text-green-600 font-extrabold">Special Deal Active</span>
                        @endif
                    </p>
                    <div class="text-sm text-gray-400 font-medium flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $offer->ends_at ? 'Ends ' . $offer->ends_at->format('M j') : 'No Expiry' }}
                    </div>
                </x-card>
            @empty
                <p class="text-gray-500 col-span-3">No active promotions right now.</p>
            @endforelse
        </div>
    </div>

    <!-- Featured Products Section -->
    <div class="bg-gray-50 py-16 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-8 border-b border-gray-200 pb-4">Our Products</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $discountService = app(\App\Services\DiscountService::class);
                    $activeDiscounts = $discountService->getActiveDiscounts();
                @endphp

                @forelse($products as $product)
                    @php
                        $bestDiscountedPrice = $product->price;
                        $bestDiscount = null;

                        foreach($activeDiscounts as $discount) {
                            if ($discount->isSiteWide() || $discount->qualifiableProducts->contains('id', $product->id) || $discount->qualifiableCategories->contains('id', $product->category_id)) {
                                if(in_array($discount->type, ['percentage', 'fixed_amount'])) {
                                    $strategy = \App\Strategies\DiscountStrategyFactory::make($discount->type);
                                    $newPrice = $strategy->apply($product->price, $discount->value);
                                    if ($newPrice < $bestDiscountedPrice) {
                                        $bestDiscountedPrice = $newPrice;
                                        $bestDiscount = $discount;
                                    }
                                }
                            }
                        }
                    @endphp

                    <x-card>
                        <x-slot name="image">
                            @php
                                $imgSrc = asset('images/electronics.png');
                                if ($product->category_id == 2) $imgSrc = asset('images/clothing.png');
                                elseif ($product->category_id == 3) $imgSrc = asset('images/home.png');
                            @endphp
                            <img src="{{ $imgSrc }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            <div class="absolute top-2 left-2 flex flex-col gap-2">
                                <x-badge color="gray" text="{{ $product->category->name ?? 'Misc' }}" />
                            </div>
                            @if($bestDiscount)
                                <div class="absolute top-2 right-2">
                                    @if($bestDiscount->type === 'percentage')
                                        <x-badge color="red" text="Save {{ $bestDiscount->value / 100 }}%" />
                                    @else
                                        <x-badge color="red" text="Sale Active!" />
                                    @endif
                                </div>
                            @endif
                        </x-slot>

                        <div class="mb-4">
                            <a href="{{ route('user.products.show', $product) }}" class="hover:text-indigo-600 transition-colors">
                                <h3 class="text-xl font-bold text-gray-900 leading-tight mb-2">{{ $product->name }}</h3>
                            </a>
                            <x-price-display :original-price="$product->price" :discounted-price="$bestDiscountedPrice" />
                        </div>

                        <div x-data="productCard({{ $product->id }})" class="mt-auto">
                            <button @click="addToCart" :disabled="adding" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 px-4 rounded-lg transition-all shadow-md hover:shadow-lg flex justify-center items-center disabled:opacity-75">
                                <svg x-show="!adding" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <svg x-show="adding" class="w-5 h-5 mr-2 animate-spin" style="display:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span x-text="adding ? 'Adding...' : 'Add to Cart'"></span>
                            </button>
                        </div>
                    </x-card>
                @empty
                    <p class="text-gray-500">No products available.</p>
                @endforelse
            </div>
        </div>
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
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ product_id: productId, qty: 1 })
                    })
                    .then(res => res.json())
                    .then(data => {
                        document.querySelectorAll('.cart-count-badge').forEach(el => el.innerText = data.itemCount);
                        const container = document.getElementById('toast-container') || document.body;
                        const t = document.createElement('div');
                        t.className = 'fixed bottom-5 right-5 z-50 bg-green-900 text-white px-6 py-3 rounded-lg shadow-xl font-bold flex items-center transition-all duration-300';
                        t.innerHTML = '<svg class="w-5 h-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Added to cart!';
                        container.appendChild(t);
                        setTimeout(() => t.remove(), 2500);
                    })
                    .finally(() => { this.adding = false; });
                }
            }));
        });
    </script>
    @endonce
@endsection
