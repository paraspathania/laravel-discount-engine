@extends('layouts.app')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <a href="{{ route('user.products.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 mb-8 transition-colors">
                <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Products
            </a>

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
                            if ($newPrice < $bestDiscountedPrice) {
                                $bestDiscountedPrice = $newPrice;
                            }
                        }
                    }
                }
            @endphp

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="flex flex-col md:flex-row">
                    <!-- Left: Image -->
                    <div class="w-full md:w-1/2 bg-gray-100 p-12 flex items-center justify-center border-b md:border-b-0 md:border-r border-gray-200">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($product->name) }}&background=random&size=500" alt="{{ $product->name }}" class="w-full max-w-sm h-auto rounded-lg shadow-lg transform hover:scale-105 transition-transform duration-500">
                    </div>

                    <!-- Right: Details -->
                    <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
                        <div class="mb-6">
                            <x-badge color="gray" text="{{ $product->category->name ?? 'Misc' }}" class="mb-4" />
                            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">{{ $product->name }}</h1>
                            <p class="text-sm text-gray-500 mt-2 font-mono">SKU: {{ $product->sku }}</p>
                        </div>

                        <div class="mb-8">
                            <x-price-display :original-price="$product->price" :discounted-price="$bestDiscountedPrice" />
                        </div>

                        @if($product->stock > 0)
                            <div class="mb-6 flex items-center">
                                <span class="h-3 w-3 bg-green-500 rounded-full mr-2"></span>
                                <span class="text-green-700 font-bold">In Stock ({{ $product->stock }} available)</span>
                            </div>

                            <div x-data="productDetail({{ $product->id }})" class="mt-auto border-t border-gray-100 pt-8 flex items-center gap-4">
                                <div class="w-24">
                                    <select x-model="qty" class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-xl bg-gray-50">
                                        @for($i = 1; $i <= min(10, $product->stock); $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <button @click="addToCart" :disabled="adding" class="flex-grow bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-3 px-8 rounded-xl transition-all shadow-md hover:shadow-xl flex justify-center items-center disabled:opacity-75">
                                    <svg x-show="!adding" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <svg x-show="adding" class="w-5 h-5 mr-2 animate-spin" style="display:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <span x-text="adding ? 'Adding...' : 'Add to Cart'"></span>
                                </button>
                            </div>
                        @else
                            <div class="mb-6 flex items-center">
                                <span class="h-3 w-3 bg-red-500 rounded-full mr-2"></span>
                                <span class="text-red-700 font-bold">Out of Stock</span>
                            </div>
                            <button disabled class="w-full bg-gray-300 text-gray-500 font-extrabold py-3 px-8 rounded-xl cursor-not-allowed border-t border-gray-100 mt-auto">
                                Currently Unavailable
                            </button>
                        @endif

                        <!-- Applicable Offers Widget -->
                        @if($applicableOffers->count() > 0)
                            <div class="mt-10 bg-indigo-50 border border-indigo-100 rounded-xl p-6">
                                <h4 class="font-bold text-indigo-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                                    Offers apply to this item!
                                </h4>
                                <ul class="space-y-2">
                                    @foreach($applicableOffers as $offer)
                                        <li class="text-sm text-indigo-700 flex items-start">
                                            <span class="mr-2">•</span>
                                            <span><strong>{{ $offer->name }}</strong>: Applied automatically at checkout!</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
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
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ product_id: productId, qty: this.qty })
                    })
                    .then(res => res.json())
                    .then(data => {
                        document.querySelectorAll('.cart-count-badge').forEach(el => el.innerText = data.itemCount);
                        const container = document.getElementById('toast-container') || document.body;
                        const t = document.createElement('div');
                        t.className = 'fixed bottom-5 right-5 z-50 bg-green-900 text-white px-6 py-3 rounded-lg shadow-xl font-bold flex items-center transition-all duration-300';
                        t.innerHTML = '<svg class="w-5 h-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Added ' + this.qty + ' to cart!';
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
