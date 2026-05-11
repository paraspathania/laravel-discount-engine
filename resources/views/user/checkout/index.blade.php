@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Secure Checkout</h1>
            <p class="text-gray-500 mt-2 font-medium">Please review your items and confirm your order.</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Left: Order Review -->
            <div class="w-full lg:w-2/3">
                <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-200">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-extrabold text-gray-900">Order Items</h3>
                        <a href="{{ route('user.cart.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800">Edit Cart</a>
                    </div>
                    <ul class="divide-y divide-gray-200">
                        @foreach($finalCart->items as $item)
                            <li class="p-6 flex items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($item->product->name) }}&background=random" class="h-16 w-16 rounded-lg object-cover mr-6 shadow-sm">
                                <div class="flex-1">
                                    <h4 class="text-lg font-bold text-gray-900">{{ $item->product->name }}</h4>
                                    <p class="text-sm text-gray-500 font-medium">Qty: {{ $item->qty }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-extrabold text-gray-900">${{ number_format($item->price / 100, 2) }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Right: Payment Summary -->
            <div class="w-full lg:w-1/3">
                <div class="bg-white shadow-sm rounded-2xl p-8 border border-gray-200 sticky top-24">
                    <h2 class="text-xl font-extrabold text-gray-900 mb-6">Payment Summary</h2>
                    
                    <dl class="space-y-4 text-sm font-medium text-gray-600 mb-6 border-b border-gray-100 pb-6">
                        <div class="flex justify-between">
                            <dt>Subtotal ({{ $finalCart->itemCount }} items)</dt>
                            <dd class="font-bold text-gray-900">${{ number_format($finalCart->subtotal / 100, 2) }}</dd>
                        </div>
                        
                        <!-- Applied Discounts Breakdown -->
                        @if(isset($finalCart->appliedDiscounts) && count($finalCart->appliedDiscounts) > 0)
                            <div class="pt-2 pb-2">
                                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Discounts Applied:</dt>
                                @foreach($finalCart->appliedDiscounts as $applied)
                                    <div class="flex justify-between text-green-600 font-extrabold mb-1">
                                        <dd class="flex items-center">
                                            <svg class="w-4 h-4 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                            {{ $applied['name'] }}
                                        </dd>
                                        <dd>-${{ number_format($applied['saved_amount'] / 100, 2) }}</dd>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($finalCart->itemDiscountsTotal > 0 || $finalCart->orderDiscountsTotal > 0)
                            <!-- Fallback if appliedDiscounts array isn't populated but savings exist -->
                            <div class="flex justify-between text-green-600 font-extrabold">
                                <dt>Discounts Saved</dt>
                                <dd>-${{ number_format(($finalCart->itemDiscountsTotal + $finalCart->orderDiscountsTotal) / 100, 2) }}</dd>
                            </div>
                        @endif
                        
                        <div class="flex justify-between pt-2">
                            <dt>Shipping</dt>
                            @if($finalCart->shippingDiscountTotal > 0)
                                <dd class="flex items-center">
                                    <span class="line-through text-gray-400 mr-2">${{ number_format($finalCart->baseShippingCost / 100, 2) }}</span>
                                    <span class="font-black text-green-600 uppercase tracking-wide">Free</span>
                                </dd>
                            @else
                                <dd class="font-bold text-gray-900">${{ number_format($finalCart->finalShippingCost / 100, 2) }}</dd>
                            @endif
                        </div>

                        <div class="flex justify-between pt-2">
                            <dt>Estimated Tax</dt>
                            <dd class="font-bold text-gray-900">${{ number_format($finalCart->taxTotal / 100, 2) }}</dd>
                        </div>
                    </dl>

                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-xl font-black text-gray-900">Grand Total</h3>
                        <span class="text-3xl font-black text-indigo-600">${{ number_format($finalCart->grandTotal / 100, 2) }}</span>
                    </div>

                    <form action="{{ route('user.checkout.process') }}" method="POST">
                        @csrf
                        
                        <div class="mb-6 bg-gray-50 p-4 rounded-xl border border-gray-200 flex items-start">
                            <div class="flex items-center h-5">
                                <input id="terms" name="terms" type="checkbox" required class="focus:ring-indigo-500 h-5 w-5 text-indigo-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="terms" class="font-bold text-gray-700">I agree to the terms and conditions</label>
                                <p class="text-gray-500 font-medium mt-1">By placing this order, you agree to our return policy and terms of service.</p>
                            </div>
                        </div>

                        <button type="submit" class="w-full flex justify-center items-center bg-gray-900 hover:bg-gray-800 text-white text-lg font-black py-4 px-6 rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                            Place Order Now
                            <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
