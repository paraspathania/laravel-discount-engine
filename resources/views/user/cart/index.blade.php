@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="cartManager({{ json_encode($finalCart) }})">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Your Cart
            <span class="text-lg font-normal text-gray-400 ml-2">(<span x-text="cart.itemCount"></span> items)</span>
        </h1>
        <a href="{{ route('user.products.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Continue shopping
        </a>
    </div>

    {{-- Cart has items --}}
    <template x-if="cart.items.length > 0">
        <div class="flex flex-col lg:flex-row gap-8">

            {{-- Left: Items --}}
            <div class="flex-grow">
                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
                    <template x-for="item in cart.items" :key="item.product.id">
                        <div class="flex items-center gap-4 p-5 border-b border-gray-50 last:border-b-0 hover:bg-gray-50/50 transition-colors">

                            {{-- Image --}}
                            <div class="w-16 h-16 bg-gray-100 rounded-xl overflow-hidden shrink-0">
                                <img :src="item.product.image_url"
                                     class="w-full h-full object-cover">
                            </div>

                            {{-- Name --}}
                            <div class="flex-grow min-w-0">
                                <p class="font-semibold text-gray-900 text-sm truncate" x-text="item.product.name"></p>
                                <p class="text-xs text-gray-400 mt-0.5">₹<span x-text="item.unitPriceFormatted"></span> each</p>
                            </div>

                            {{-- Qty stepper --}}
                            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden shrink-0">
                                <button @click="updateQty(item.product.id, item.qty - 1)" :disabled="isLoading || item.qty <= 1"
                                    class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors text-lg font-light disabled:opacity-40">−</button>
                                <span x-text="item.qty" class="w-8 text-center text-sm font-semibold text-gray-900"></span>
                                <button @click="updateQty(item.product.id, item.qty + 1)" :disabled="isLoading"
                                    class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors text-lg font-light disabled:opacity-40">+</button>
                            </div>

                            {{-- Line total --}}
                            <div class="text-right shrink-0 w-20">
                                <p class="font-bold text-gray-900 text-sm">₹<span x-text="item.lineTotalFormatted"></span></p>
                            </div>

                            {{-- Remove --}}
                            <button @click="removeItem(item.product.id)" :disabled="isLoading"
                                class="shrink-0 text-gray-300 hover:text-red-400 transition-colors p-1 disabled:opacity-40">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Right: Summary --}}
            <div class="lg:w-80 shrink-0">
                <div class="bg-white border border-gray-100 rounded-2xl p-6 sticky top-24">

                    {{-- Coupon --}}
                    <div class="mb-5 pb-5 border-b border-gray-100">
                        <template x-if="!cart.couponCode">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Coupon Code</p>
                                <div class="flex gap-2">
                                    <input type="text" x-model="couponInput" @keydown.enter="applyCoupon" placeholder="Enter code"
                                        class="flex-grow text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 uppercase font-mono tracking-wider">
                                    <button @click="applyCoupon" :disabled="isLoading"
                                        class="text-sm font-semibold bg-gray-900 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg transition-colors disabled:opacity-50">Apply</button>
                                </div>
                                <template x-if="couponError">
                                    <p class="text-xs text-red-500 mt-2 font-medium" x-text="couponError"></p>
                                </template>
                            </div>
                        </template>
                        <template x-if="cart.couponCode">
                            <div class="flex items-center justify-between bg-green-50 border border-green-100 rounded-lg px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span class="text-sm font-bold text-green-800 font-mono tracking-wider" x-text="cart.couponCode"></span>
                                </div>
                                <button @click="removeCoupon" class="text-gray-400 hover:text-red-400 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    {{-- Totals --}}
                    <div class="space-y-3 text-sm mb-5">
                        <div class="flex justify-between text-gray-500">
                            <span>Subtotal</span>
                            <span class="font-medium text-gray-900">₹<span x-text="cart.subtotalFormatted"></span></span>
                        </div>
                        <template x-if="(cart.itemDiscountsTotal + cart.orderDiscountsTotal) > 0">
                            <div class="flex justify-between text-green-600 font-semibold">
                                <span>Discounts</span>
                                <span>−₹<span x-text="cart.discountsFormatted"></span></span>
                            </div>
                        </template>
                        <div class="flex justify-between text-gray-500">
                            <span>Shipping</span>
                            <template x-if="cart.shippingDiscountTotal > 0">
                                <span class="flex items-center gap-1.5">
                                    <s class="text-gray-300">₹<span x-text="cart.baseShippingFormatted"></span></s>
                                    <span class="text-green-600 font-semibold">Free</span>
                                </span>
                            </template>
                            <template x-if="cart.shippingDiscountTotal == 0">
                                <span class="font-medium text-gray-900">₹<span x-text="cart.shippingFormatted"></span></span>
                            </template>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Tax (8%)</span>
                            <span class="font-medium text-gray-900">₹<span x-text="cart.taxFormatted"></span></span>
                        </div>
                        <div class="flex justify-between pt-3 border-t border-gray-100">
                            <span class="font-bold text-gray-900">Total</span>
                            <span class="font-bold text-xl text-gray-900">₹<span x-text="cart.grandTotalFormatted"></span></span>
                        </div>
                    </div>

                    <a href="{{ route('user.checkout.index') }}"
                        class="w-full flex items-center justify-center gap-2 bg-gray-900 hover:bg-indigo-600 text-white font-semibold text-sm py-3.5 rounded-xl transition-colors duration-200">
                        Proceed to checkout
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>

                    <p class="text-center text-xs text-gray-400 mt-3">Secure, encrypted checkout</p>
                </div>
            </div>

        </div>
    </template>

    {{-- Empty state --}}
    <template x-if="cart.items.length === 0">
        <div class="bg-white border border-gray-100 rounded-2xl py-24 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Your cart is empty</h2>
            <p class="text-sm text-gray-400 mb-6">Add some products to get started.</p>
            <a href="{{ route('user.products.index') }}" class="inline-flex items-center gap-2 bg-gray-900 hover:bg-indigo-600 text-white text-sm font-semibold px-6 py-3 rounded-lg transition-colors duration-200">
                Start shopping
            </a>
        </div>
    </template>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('cartManager', (initialCart) => ({
        cart: initialCart,
        couponInput: '',
        couponError: '',
        isLoading: false,

        async ajaxCall(url, data) {
            this.isLoading = true;
            this.couponError = '';
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await res.json();
                if (result.cart) { 
                    this.cart = result.cart; 
                    this.updateBadge(); 
                } else if (result.items) {
                    this.cart = result;
                    this.updateBadge();
                }
                if (result.success === false && result.message) { this.couponError = result.message; }
                if (result.success === true) { this.couponInput = ''; showToast('Coupon applied!'); }
            } catch { showToast('Something went wrong', 'error'); }
            finally { this.isLoading = false; }
        },

        updateQty(productId, qty) {
            if (qty < 1) return this.removeItem(productId);
            this.ajaxCall('{{ route('user.cart.update') }}', { product_id: productId, qty: parseInt(qty) });
        },
        removeItem(productId) { this.ajaxCall('{{ route('user.cart.remove') }}', { product_id: productId }); },
        applyCoupon() { if (!this.couponInput.trim()) return; this.ajaxCall('{{ route('user.cart.coupon.apply') }}', { code: this.couponInput.trim() }); },
        removeCoupon() { this.ajaxCall('{{ route('user.cart.coupon.remove') }}', {}); },
        updateBadge() { document.querySelectorAll('.cart-count-badge').forEach(el => el.innerText = this.cart.itemCount); }
    }));
});
</script>
@endsection
