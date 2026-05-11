@extends('layouts.app')

@section('content')
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-4xl font-extrabold text-gray-900">All Products</h1>
            
            <!-- Search and Filter Bar -->
            <div class="mt-8 bg-gray-50 p-4 rounded-xl border border-gray-200">
                <form action="{{ route('user.products.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-grow">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    </div>
                    <div class="md:w-64">
                        <select name="category_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                            Filter
                        </button>
                    </div>
                    @if(request('search') || request('category_id'))
                        <div>
                            <a href="{{ route('user.products.index') }}" class="w-full md:w-auto flex items-center justify-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg transition-colors">
                                Clear
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @php
            $discountService = app(\App\Services\DiscountService::class);
            $activeDiscounts = $discountService->getActiveDiscounts();
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
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
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($product->name) }}&background=random&size=400" alt="{{ $product->name }}" class="w-full h-full object-cover">
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

                    <!-- Alpine component to handle AJAX add to cart -->
                    <div x-data="{ 
                        adding: false, 
                        addToCart() {
                            this.adding = true;
                            fetch('{{ route('user.cart.add') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').content,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ product_id: {{ $product->id }}, qty: 1 })
                            })
                            .then(res => res.json())
                            .then(data => {
                                document.querySelectorAll('.cart-count-badge').forEach(el => el.innerText = data.itemCount);
                                // Show quick success toast
                                const container = document.getElementById('toast-container') || document.body;
                                const t = document.createElement('div');
                                t.className = 'fixed bottom-5 right-5 z-50 bg-green-900 text-white px-6 py-3 rounded-lg shadow-xl font-bold flex items-center transition-all duration-300';
                                t.innerHTML = '<svg class=\"w-5 h-5 mr-2 text-green-400\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M5 13l4 4L19 7\"></path></svg> Added to cart!';
                                container.appendChild(t);
                                setTimeout(() => t.remove(), 2500);
                            })
                            .finally(() => { this.adding = false; });
                        }
                    }" class="mt-auto">
                        <button @click="addToCart" :disabled="adding" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition-colors flex justify-center items-center disabled:opacity-75">
                            <svg x-show="!adding" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <svg x-show="adding" class="w-5 h-5 mr-2 animate-spin" style="display:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span x-text="adding ? 'Adding...' : 'Add to Cart'"></span>
                        </button>
                    </div>
                </x-card>
            @empty
                <div class="col-span-full py-20 text-center bg-white rounded-xl border border-gray-200">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="text-xl font-bold text-gray-900">No products found</h3>
                    <p class="text-gray-500 mt-2">Try adjusting your search or category filters.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $products->links() }}
        </div>
    </div>
@endsection
