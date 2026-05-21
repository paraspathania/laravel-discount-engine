@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Checkout</h1>
        <p class="text-sm text-gray-400 mt-1">Review your order before placing it.</p>
    </div>

    <form action="{{ route('user.checkout.process') }}" method="POST">
        @csrf

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl font-bold flex items-center">
                <svg class="w-5 h-5 mr-3 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Left: Shipping Address & Items --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Shipping Address Card --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center gap-3 border-b border-gray-100 pb-4 mb-5">
                        <div class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h2 class="font-extrabold text-gray-900 text-lg">Shipping Address</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Name --}}
                        <div class="md:col-span-3">
                            <label for="shipping_name" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Full Name</label>
                            <input type="text" name="shipping_name" id="shipping_name" value="{{ old('shipping_name', auth()->user()->name) }}" required
                                   class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm text-sm px-4 py-3 @error('shipping_name') border-red-500 focus:ring-red-500 @enderror">
                            @error('shipping_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Street Address --}}
                        <div class="md:col-span-3">
                            <label for="shipping_address" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Street Address</label>
                            <input type="text" name="shipping_address" id="shipping_address" value="{{ old('shipping_address') }}" required placeholder="Flat, House no., Building, Company, Apartment, Street"
                                   class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm text-sm px-4 py-3 @error('shipping_address') border-red-500 focus:ring-red-500 @enderror">
                            @error('shipping_address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- City --}}
                        <div>
                            <label for="shipping_city" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">City</label>
                            <input type="text" name="shipping_city" id="shipping_city" value="{{ old('shipping_city') }}" required
                                   class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm text-sm px-4 py-3 @error('shipping_city') border-red-500 focus:ring-red-500 @enderror">
                            @error('shipping_city')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- State --}}
                        <div>
                            <label for="shipping_state" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">State</label>
                            <input type="text" name="shipping_state" id="shipping_state" value="{{ old('shipping_state') }}" required
                                   class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm text-sm px-4 py-3 @error('shipping_state') border-red-500 focus:ring-red-500 @enderror">
                            @error('shipping_state')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ZIP / Postal Code --}}
                        <div>
                            <label for="shipping_postal_code" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">PIN / Postal Code</label>
                            <input type="text" name="shipping_postal_code" id="shipping_postal_code" value="{{ old('shipping_postal_code') }}" required
                                   class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm text-sm px-4 py-3 @error('shipping_postal_code') border-red-500 focus:ring-red-500 @enderror">
                            @error('shipping_postal_code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="md:col-span-3">
                            <label for="shipping_phone" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Phone Number (For Delivery)</label>
                            <input type="text" name="shipping_phone" id="shipping_phone" value="{{ old('shipping_phone') }}" required placeholder="e.g. +91 98765 43210"
                                   class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm text-sm px-4 py-3 @error('shipping_phone') border-red-500 focus:ring-red-500 @enderror">
                            @error('shipping_phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Items Card --}}
                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <h2 class="font-extrabold text-gray-900 text-lg">Review Items</h2>
                        <a href="{{ route('user.cart.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Edit cart</a>
                    </div>
                    @foreach($finalCart->items as $item)
                        @php
                            $imgSrc = $item->product->image_url;
                        @endphp
                        <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-50 last:border-b-0">
                            <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden shrink-0">
                                <img src="{{ $imgSrc }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow">
                                <p class="font-bold text-gray-900 text-sm">{{ $item->product->name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">Qty: {{ $item->qty }}</p>
                            </div>
                            <p class="font-black text-gray-900 text-sm">₹{{ number_format($item->price / 100, 2) }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- Applied discounts info --}}
                @if(isset($finalCart->appliedDiscounts) && count($finalCart->appliedDiscounts) > 0)
                    <div class="bg-green-50 border border-green-100 rounded-2xl p-5 shadow-sm">
                        <p class="text-xs font-extrabold text-green-700 uppercase tracking-wider mb-3">Discounts applied to your order</p>
                        <ul class="space-y-1.5">
                            @foreach($finalCart->appliedDiscounts as $applied)
                                <li class="flex justify-between text-sm">
                                    <span class="text-green-800 font-bold">{{ $applied['name'] ?? 'Discount' }}</span>
                                    <span class="font-black text-green-700">−₹{{ number_format($applied['saved_amount'] / 100, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- Right: Summary + Place Order --}}
            <div>
                <div class="bg-white border border-gray-100 rounded-2xl p-6 sticky top-24 shadow-sm">
                    <h2 class="font-extrabold text-gray-900 text-lg mb-5 pb-4 border-b border-gray-100">Order Summary</h2>

                    <div class="space-y-4 text-sm mb-6">
                        <div class="flex justify-between text-gray-500">
                            <span class="font-medium">Subtotal ({{ $finalCart->itemCount }} items)</span>
                            <span class="font-bold text-gray-900">₹{{ number_format($finalCart->subtotal / 100, 2) }}</span>
                        </div>

                        @if($finalCart->itemDiscountsTotal > 0 || $finalCart->orderDiscountsTotal > 0)
                            <div class="flex justify-between text-green-600 font-bold">
                                <span>Savings</span>
                                <span>−₹{{ number_format(($finalCart->itemDiscountsTotal + $finalCart->orderDiscountsTotal) / 100, 2) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-gray-500">
                            <span class="font-medium">Shipping</span>
                            @if(isset($finalCart->shippingDiscountTotal) && $finalCart->shippingDiscountTotal > 0)
                                <span class="font-bold text-green-600">Free</span>
                            @else
                                <span class="font-bold text-gray-900">₹{{ number_format($finalCart->finalShippingCost / 100, 2) }}</span>
                            @endif
                        </div>

                        <div class="flex justify-between text-gray-500">
                            <span class="font-medium">Tax (8%)</span>
                            <span class="font-bold text-gray-900">₹{{ number_format($finalCart->taxTotal / 100, 2) }}</span>
                        </div>

                        <div class="flex justify-between pt-4 border-t border-gray-100">
                            <span class="font-extrabold text-gray-900 text-base">Total</span>
                            <span class="font-black text-2xl text-indigo-600">₹{{ number_format($finalCart->grandTotal / 100, 2) }}</span>
                        </div>
                    </div>

                    <label class="flex items-start gap-3 mb-5 cursor-pointer">
                        <input type="checkbox" name="terms" required class="mt-0.5 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-xs text-gray-500 leading-relaxed font-medium">I agree to the <a href="#" class="text-indigo-600 hover:underline">terms of service</a> and return policy.</span>
                    </label>

                    <button type="submit" class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-sm py-4 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Place Order
                    </button>
                    <p class="text-center text-[10px] text-gray-400 mt-4 font-medium uppercase tracking-wider">🔒 Your data is secure & encrypted</p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
