@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Checkout</h1>
        <p class="text-sm text-gray-400 mt-1">Review your order before placing it.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Left: Items --}}
        <div class="lg:col-span-2">
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden mb-4">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-900">Your items</h2>
                    <a href="{{ route('user.cart.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">Edit cart</a>
                </div>
                @foreach($finalCart->items as $item)
                    @php
                        $imgSrc = asset('images/electronics.png');
                        if ($item->product->category_id == 2) $imgSrc = asset('images/clothing.png');
                        elseif ($item->product->category_id == 3) $imgSrc = asset('images/home.png');
                    @endphp
                    <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-50 last:border-b-0">
                        <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden shrink-0">
                            <img src="{{ $imgSrc }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-grow">
                            <p class="font-medium text-gray-900 text-sm">{{ $item->product->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Qty: {{ $item->qty }}</p>
                        </div>
                        <p class="font-semibold text-gray-900 text-sm">₹{{ number_format($item->price / 100, 0) }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Applied discounts info --}}
            @if(isset($finalCart->appliedDiscounts) && count($finalCart->appliedDiscounts) > 0)
                <div class="bg-green-50 border border-green-100 rounded-2xl p-5 mb-4">
                    <p class="text-xs font-semibold text-green-700 uppercase tracking-wide mb-3">Discounts applied to your order</p>
                    <ul class="space-y-1.5">
                        @foreach($finalCart->appliedDiscounts as $applied)
                            <li class="flex justify-between text-sm">
                                <span class="text-green-800">{{ $applied['name'] }}</span>
                                <span class="font-bold text-green-700">−₹{{ number_format($applied['saved_amount'] / 100, 0) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- Right: Summary + Place Order --}}
        <div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 sticky top-24">
                <h2 class="font-semibold text-gray-900 mb-5">Order summary</h2>

                <div class="space-y-3 text-sm mb-5">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal ({{ $finalCart->itemCount }} items)</span>
                        <span class="font-medium text-gray-900">₹{{ number_format($finalCart->subtotal / 100, 0) }}</span>
                    </div>

                    @if($finalCart->itemDiscountsTotal > 0 || $finalCart->orderDiscountsTotal > 0)
                        <div class="flex justify-between text-green-600 font-semibold">
                            <span>Savings</span>
                            <span>−₹{{ number_format(($finalCart->itemDiscountsTotal + $finalCart->orderDiscountsTotal) / 100, 0) }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between text-gray-500">
                        <span>Shipping</span>
                        @if(isset($finalCart->shippingDiscountTotal) && $finalCart->shippingDiscountTotal > 0)
                            <span class="font-semibold text-green-600">Free</span>
                        @else
                            <span class="font-medium text-gray-900">₹{{ number_format($finalCart->finalShippingCost / 100, 0) }}</span>
                        @endif
                    </div>

                    <div class="flex justify-between text-gray-500">
                        <span>Tax (8%)</span>
                        <span class="font-medium text-gray-900">₹{{ number_format($finalCart->taxTotal / 100, 0) }}</span>
                    </div>

                    <div class="flex justify-between pt-4 border-t border-gray-100">
                        <span class="font-bold text-gray-900">Total</span>
                        <span class="font-bold text-xl text-gray-900">₹{{ number_format($finalCart->grandTotal / 100, 0) }}</span>
                    </div>
                </div>

                <form action="{{ route('user.checkout.process') }}" method="POST">
                    @csrf
                    <label class="flex items-start gap-3 mb-5 cursor-pointer">
                        <input type="checkbox" name="terms" required class="mt-0.5 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-xs text-gray-500 leading-relaxed">I agree to the <a href="#" class="text-indigo-600 hover:underline">terms of service</a> and return policy.</span>
                    </label>

                    <button type="submit" class="w-full flex items-center justify-center gap-2 bg-gray-900 hover:bg-indigo-600 text-white font-semibold text-sm py-3.5 rounded-xl transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Place Order
                    </button>
                    <p class="text-center text-xs text-gray-400 mt-3">Your data is secure & encrypted</p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
