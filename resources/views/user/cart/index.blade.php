@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-12" id="cart-app" 
    x-data="cartManager({{ json_encode($finalCart) }})">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Shopping Cart</h1>
            <a href="{{ route('user.products.index') }}" class="text-indigo-600 hover:text-indigo-800 font-bold flex items-center transition-colors">
                &larr; Continue Shopping
            </a>
        </div>

        <template x-if="cart.items.length > 0">
            <div class="flex flex-col lg:flex-row gap-12">
                <!-- Left: Cart Items -->
                <div class="w-full lg:w-2/3">
                    <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Unit Price</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Line Total</th>
                                    <th class="px-6 py-4"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-for="item in cart.items" :key="item.product.id">
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-6 flex items-center">
                                            <img :src="item.product.category_id == 2 ? '{{ asset('images/clothing.png') }}' : (item.product.category_id == 3 ? '{{ asset('images/home.png') }}' : '{{ asset('images/electronics.png') }}')" class="h-16 w-16 rounded-lg object-cover mr-4 shadow-sm">
                                            <span class="font-extrabold text-gray-900 text-lg" x-text="item.product.name"></span>
                                        </td>
                                        <td class="px-6 py-6 text-center font-bold text-gray-700">
                                            ₹<span x-text="item.unitPriceFormatted"></span>
                                        </td>
                                        <td class="px-6 py-6 text-center">
                                            <div class="flex items-center justify-center border border-gray-300 rounded-lg overflow-hidden w-32 mx-auto shadow-sm">
                                                <button @click="updateQty(item.product.id, item.qty - 1)" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold transition-colors" :disabled="isLoading">-</button>
                                                <input type="number" x-model="item.qty" @change="updateQty(item.product.id, $event.target.value)" class="w-full text-center border-0 focus:ring-0 font-bold text-gray-900 p-2" :disabled="isLoading">
                                                <button @click="updateQty(item.product.id, item.qty + 1)" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold transition-colors" :disabled="isLoading">+</button>
                                            </div>
                                        </td>
                                        <td class="px-6 py-6 text-right font-extrabold text-gray-900 text-lg">
                                            ₹<span x-text="item.lineTotalFormatted"></span>
                                        </td>
                                        <td class="px-6 py-6 text-right">
                                            <button @click="removeItem(item.product.id)" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors" :disabled="isLoading">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Right: Order Summary -->
                <div class="w-full lg:w-1/3">
                    <div class="bg-white shadow-sm rounded-2xl p-8 border border-gray-200 sticky top-24">
                        <h2 class="text-xl font-extrabold text-gray-900 mb-6">Order Summary</h2>
                        
                        <!-- Coupon Input -->
                        <div class="mb-6 border-b border-gray-100 pb-6">
                            <template x-if="!cart.couponCode">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Have a coupon code?</label>
                                    <div class="flex gap-2 relative">
                                        <input type="text" x-model="couponInput" @keydown.enter="applyCoupon" placeholder="Enter code" class="flex-grow rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 uppercase font-mono tracking-wider shadow-sm" :disabled="isLoading">
                                        <button @click="applyCoupon" class="bg-gray-900 hover:bg-gray-800 text-white font-bold px-4 rounded-lg transition-colors shadow-sm" :disabled="isLoading">Apply</button>
                                    </div>
                                    <template x-if="couponError">
                                        <p class="mt-2 text-sm text-red-600 font-bold flex items-center">
                                            <svg class="w-4 h-4 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span x-text="couponError"></span>
                                        </p>
                                    </template>
                                </div>
                            </template>
                            
                            <template x-if="cart.couponCode">
                                <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center justify-between shadow-sm">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <div>
                                            <span class="font-black text-green-800 font-mono tracking-wider" x-text="cart.couponCode"></span>
                                            <span class="text-sm font-bold text-green-600 block">Applied successfully!</span>
                                        </div>
                                    </div>
                                    <button @click="removeCoupon" class="text-red-500 hover:text-red-700 bg-red-100 hover:bg-red-200 p-1.5 rounded-lg transition-colors" :disabled="isLoading">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <dl class="space-y-4 text-sm font-medium text-gray-600">
                            <div class="flex justify-between">
                                <dt>Subtotal (<span x-text="cart.itemCount"></span> items)</dt>
                                <dd class="font-bold text-gray-900">₹<span x-text="cart.subtotalFormatted"></span></dd>
                            </div>
                            
                            <template x-if="(cart.itemDiscountsTotal + cart.orderDiscountsTotal) > 0">
                                <div class="flex justify-between text-green-600 font-extrabold bg-green-50 p-2 rounded-lg -mx-2">
                                    <dt>Discounts Saved</dt>
                                    <dd>-₹<span x-text="cart.discountsFormatted"></span></dd>
                                </div>
                            </template>
                            
                            <div class="flex justify-between">
                                <dt>Shipping</dt>
                                <template x-if="cart.shippingDiscountTotal > 0">
                                    <dd class="flex items-center">
                                        <span class="line-through text-gray-400 mr-2">₹<span x-text="cart.baseShippingFormatted"></span></span>
                                        <span class="font-black text-green-600 uppercase tracking-wide">Free</span>
                                    </dd>
                                </template>
                                <template x-if="cart.shippingDiscountTotal == 0">
                                    <dd class="font-bold text-gray-900">₹<span x-text="cart.shippingFormatted"></span></dd>
                                </template>
                            </div>

                            <div class="flex justify-between border-b border-gray-100 pb-4">
                                <dt>Estimated Tax (8%)</dt>
                                <dd class="font-bold text-gray-900">₹<span x-text="cart.taxFormatted"></span></dd>
                            </div>

                            <div class="flex justify-between items-center pt-2">
                                <dt class="text-xl font-black text-gray-900">Grand Total</dt>
                                <dd class="text-3xl font-black text-indigo-600">₹<span x-text="cart.grandTotalFormatted"></span></dd>
                            </div>
                        </dl>

                        <a href="{{ route('user.checkout.index') }}" class="w-full mt-8 flex justify-center items-center bg-green-500 hover:bg-green-600 text-white text-lg font-black py-4 px-6 rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                            Proceed to Checkout
                            <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                        
                        <div x-show="isLoading" class="absolute inset-0 bg-white bg-opacity-70 flex items-center justify-center rounded-2xl" style="display: none;">
                            <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- Empty State -->
        <template x-if="cart.items.length === 0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 py-24 text-center">
                <div class="mx-auto w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Your cart is currently empty</h2>
                <p class="text-gray-500 font-medium mb-8 text-lg">Looks like you haven't added anything to your cart yet.</p>
                <a href="{{ route('user.products.index') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-lg py-4 px-10 rounded-xl transition-all shadow-lg hover:shadow-xl">
                    Start Shopping
                </a>
            </div>
        </template>

    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('cartManager', (initialCart) => ({
            cart: initialCart,
            couponInput: '',
            couponError: '',
            isLoading: false,

            init() {
                // Ensure cart badge matches initial state
                this.updateGlobalBadge();
            },

            async ajaxCall(url, data) {
                this.isLoading = true;
                this.couponError = '';
                
                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });
                    
                    const result = await response.json();
                    
                    if(result.cart) {
                        this.cart = result.cart;
                        this.updateGlobalBadge();
                    }
                    
                    if(result.success === false && result.message) {
                        this.couponError = result.message;
                    }
                    
                    if(result.success === true && result.message) {
                        this.couponInput = '';
                    }
                    
                } catch (error) {
                    console.error("Cart AJAX Error:", error);
                } finally {
                    this.isLoading = false;
                }
            },

            updateQty(productId, qty) {
                if (qty < 1) qty = 1; // Prevent going to 0 via input, use remove for 0
                this.ajaxCall('{{ route('user.cart.update') }}', { product_id: productId, qty: parseInt(qty) });
            },

            removeItem(productId) {
                this.ajaxCall('{{ route('user.cart.remove') }}', { product_id: productId });
            },

            applyCoupon() {
                if(!this.couponInput.trim()) return;
                this.ajaxCall('{{ route('user.cart.coupon.apply') }}', { code: this.couponInput.trim() });
            },

            removeCoupon() {
                this.ajaxCall('{{ route('user.cart.coupon.remove') }}', {});
            },

            updateGlobalBadge() {
                // Find all badges across the layout that might show the count
                document.querySelectorAll('.cart-count-badge').forEach(el => {
                    el.innerText = this.cart.itemCount;
                });
            }
        }));
    });
</script>
@endsection
