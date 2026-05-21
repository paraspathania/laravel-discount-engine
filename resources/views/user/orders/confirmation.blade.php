@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-16 flex items-center justify-center">
    <div class="max-w-2xl w-full mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 text-center">
            
            <!-- Success Header -->
            <div class="bg-green-500 py-12 px-8">
                <div class="mx-auto w-24 h-24 bg-white rounded-full flex items-center justify-center mb-6 shadow-lg">
                    <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h1 class="text-4xl font-extrabold text-white tracking-tight">Order Placed Successfully!</h1>
                <p class="text-green-100 mt-2 font-bold text-lg">Thank you for your purchase.</p>
            </div>

            <div class="p-8 md:p-12">
                <p class="text-gray-600 font-medium mb-8 text-lg">
                    Your order <span class="font-black text-gray-900">#{{ $order->id }}</span> has been confirmed. A confirmation email has been sent to {{ auth()->user()->email }}.
                </p>

                <!-- Shipping Details -->
                @if($order->shipping_address)
                <div class="bg-gray-50 rounded-2xl border border-gray-200 p-6 mb-6 text-left">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-3 border-b border-gray-200 pb-2">Delivery Address</h3>
                    <div class="text-sm font-medium text-gray-800 space-y-1">
                        <p class="font-extrabold text-gray-900">{{ $order->shipping_name }}</p>
                        <p>{{ $order->shipping_address }}</p>
                        <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_postal_code }}</p>
                        <p class="mt-2 text-xs font-bold text-gray-500 uppercase tracking-wider">Phone: <span class="font-bold text-gray-800">{{ $order->shipping_phone }}</span></p>
                    </div>
                </div>
                @endif

                <!-- Receipt Table -->
                <div class="bg-gray-50 rounded-2xl border border-gray-200 p-6 mb-10 text-left">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">Order Summary</h3>
                    
                    <dl class="space-y-3 text-sm font-medium text-gray-600">
                        <div class="flex justify-between">
                            <dt>Items Purchased</dt>
                            <dd class="font-bold text-gray-900">{{ $order->items->sum('quantity') }} items</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt>Original Subtotal</dt>
                            <dd class="font-bold text-gray-900">₹{{ number_format($order->subtotal / 100, 2) }}</dd>
                        </div>
                        
                        @if($order->discount_total > 0)
                            <div class="flex justify-between text-green-600 font-extrabold bg-green-50 p-2 rounded-lg -mx-2">
                                <dt>You Saved</dt>
                                <dd>-₹{{ number_format($order->discount_total / 100, 2) }}</dd>
                            </div>
                        @endif

                        <div class="flex justify-between">
                            <dt>Tax Paid</dt>
                            <dd class="font-bold text-gray-900">₹{{ number_format($order->tax_total / 100, 2) }}</dd>
                        </div>
                        
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200 mt-2">
                            <dt class="text-lg font-black text-gray-900">Total Paid</dt>
                            <dd class="text-2xl font-black text-indigo-600">₹{{ number_format($order->grand_total / 100, 2) }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('user.orders.index') }}" class="inline-block bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 px-8 rounded-xl transition-colors shadow-md">
                        View My Orders
                    </a>
                    <a href="{{ route('user.products.index') }}" class="inline-block bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold py-3 px-8 rounded-xl border border-indigo-200 transition-colors">
                        Continue Shopping
                    </a>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
