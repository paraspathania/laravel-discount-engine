@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Order #{{ $order->id }}</h1>
                <p class="text-gray-500 mt-1 font-medium">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
            </div>
            <a href="{{ route('user.orders.index') }}" class="text-indigo-600 hover:text-indigo-800 font-bold flex items-center transition-colors">
                &larr; Back to Orders
            </a>
        </div>

        <!-- Status & Identity Card -->
        <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-200 mb-8 p-6 flex justify-between items-center">
            <div>
                <span class="text-sm font-bold text-gray-400 uppercase tracking-wider block mb-1">Current Status</span>
                @if($order->status === 'completed')
                    <x-badge color="green" text="Completed" />
                @elseif($order->status === 'pending')
                    <x-badge color="yellow" text="Pending Processing" />
                @else
                    <x-badge color="gray" text="{{ $order->status }}" />
                @endif
            </div>
            <div class="text-right">
                <span class="text-sm font-bold text-gray-400 uppercase tracking-wider block mb-1">Customer Account</span>
                <span class="font-bold text-gray-900">{{ auth()->user()->email }}</span>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-200 mb-8">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg leading-6 font-extrabold text-gray-900">Items Purchased</h3>
            </div>
            <ul class="divide-y divide-gray-200">
                @foreach($order->items as $item)
                    <li class="p-6 flex items-center">
                        @php
                            $imgSrc = asset('images/electronics.png');
                            if ($item->product->category_id == 2) $imgSrc = asset('images/clothing.png');
                            elseif ($item->product->category_id == 3) $imgSrc = asset('images/home.png');
                        @endphp
                        <img src="{{ $imgSrc }}" class="h-16 w-16 rounded-lg object-cover mr-6 shadow-sm">
                        <div class="flex-1">
                            <a href="{{ route('user.products.show', $item->product) }}" class="text-lg font-bold text-indigo-600 hover:text-indigo-800">{{ $item->product->name }}</a>
                            <p class="text-sm text-gray-500 font-medium">Qty: {{ $item->quantity }} &times; ₹{{ number_format($item->unit_price / 100, 2) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-extrabold text-gray-900">₹{{ number_format($item->line_total / 100, 2) }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Receipt Breakdown -->
        <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-200">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg leading-6 font-extrabold text-gray-900">Price Breakdown</h3>
            </div>
            <div class="p-8">
                
                @if($order->discountUsages->count() > 0)
                    <!-- Discounts Applied Section -->
                    <div class="mb-8 pb-6 border-b border-gray-200">
                        <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Discounts Applied to this Order:</h4>
                        <div class="space-y-3">
                            @foreach($order->discountUsages as $usage)
                                <div class="flex justify-between items-center bg-green-50 p-3 rounded-lg border border-green-100">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                        <span class="font-bold text-green-900">{{ $usage->discount->name }}</span>
                                    </div>
                                    <span class="font-extrabold text-green-700">Saved ₹{{ number_format($usage->saved_amount / 100, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <dl class="space-y-4 text-sm font-medium text-gray-600">
                    <div class="flex justify-between">
                        <dt>Original Subtotal</dt>
                        <dd class="font-bold text-gray-900">₹{{ number_format($order->subtotal / 100, 2) }}</dd>
                    </div>
                    
                    @if($order->discount_total > 0)
                        <div class="flex justify-between text-green-600 font-extrabold">
                            <dt>Total Discounts</dt>
                            <dd>-₹{{ number_format($order->discount_total / 100, 2) }}</dd>
                        </div>
                    @endif

                    <div class="flex justify-between">
                        <dt>Tax Paid</dt>
                        <dd class="font-bold text-gray-900">₹{{ number_format($order->tax_total / 100, 2) }}</dd>
                    </div>

                    <div class="flex justify-between items-center border-t border-gray-200 pt-6 mt-4">
                        <dt class="text-xl font-black text-gray-900">Grand Total Paid</dt>
                        <dd class="text-3xl font-black text-indigo-600">₹{{ number_format($order->grand_total / 100, 2) }}</dd>
                    </div>
                </dl>
            </div>
        </div>

    </div>
</div>
@endsection
