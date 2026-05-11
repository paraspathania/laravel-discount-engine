<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Secure Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-xl sm:rounded-2xl overflow-hidden border border-gray-200">
                <div class="p-8 sm:p-12">
                    
                    <div class="text-center mb-10">
                        <svg class="mx-auto h-12 w-12 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        <h2 class="mt-4 text-3xl font-extrabold text-gray-900">Review Your Order</h2>
                        <p class="mt-2 text-lg text-gray-500">Please confirm your items and totals before placing the order.</p>
                    </div>

                    <!-- Final Math Display -->
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-100 mb-8">
                        <dl class="space-y-4 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <dt>Subtotal ({{ count($finalCart->items) }} items)</dt>
                                <dd class="font-medium text-gray-900">${{ number_format($finalCart->subtotal / 100, 2) }}</dd>
                            </div>
                            
                            @if($finalCart->itemDiscountsTotal > 0 || $finalCart->orderDiscountsTotal > 0)
                                <div class="flex justify-between text-green-600 font-bold border-t border-gray-200 pt-4">
                                    <dt>Discounts Saved</dt>
                                    <dd>-${{ number_format(($finalCart->itemDiscountsTotal + $finalCart->orderDiscountsTotal) / 100, 2) }}</dd>
                                </div>
                            @endif
                            
                            <div class="flex justify-between border-t border-gray-200 pt-4">
                                <dt>Shipping</dt>
                                @if($finalCart->shippingDiscountTotal > 0)
                                    <dd class="flex items-center">
                                        <span class="line-through text-gray-400 mr-2">${{ number_format($finalCart->baseShippingCost / 100, 2) }}</span>
                                        <span class="font-bold text-green-600">FREE</span>
                                    </dd>
                                @else
                                    <dd class="font-medium text-gray-900">${{ number_format($finalCart->finalShippingCost / 100, 2) }}</dd>
                                @endif
                            </div>

                            <div class="flex justify-between border-t border-gray-200 pt-4">
                                <dt>Estimated Tax</dt>
                                <dd class="font-medium text-gray-900">${{ number_format($finalCart->taxTotal / 100, 2) }}</dd>
                            </div>

                            <div class="flex justify-between items-center border-t border-gray-300 pt-6 pb-2">
                                <dt class="text-xl font-extrabold text-gray-900">Total to Pay</dt>
                                <dd class="text-3xl font-extrabold text-indigo-600">${{ number_format($finalCart->grandTotal / 100, 2) }}</dd>
                            </div>
                        </dl>
                    </div>

                    <form action="{{ route('user.checkout.process') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-gray-900 hover:bg-gray-800 text-white font-extrabold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-gray-300 text-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Confirm and Place Order
                        </button>
                    </form>
                    
                    <p class="mt-4 text-center text-xs text-gray-500">By placing this order, you agree to our terms of service.</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
