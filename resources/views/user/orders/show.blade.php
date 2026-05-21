@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-8">
        <a href="{{ route('user.orders.index') }}" class="hover:text-gray-600 transition-colors">My Orders</a>
        <span>/</span>
        <span class="text-gray-700 font-medium">Order #{{ $order->id }}</span>
    </nav>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
            <p class="text-sm text-gray-400 mt-1">Placed on {{ $order->created_at->format('M j, Y') }}</p>
        </div>
        @if($order->status === 'completed')
            <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-green-50 text-green-700">Completed</span>
        @elseif($order->status === 'pending')
            <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-amber-50 text-amber-700">Pending</span>
        @else
            <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-gray-100 text-gray-600">{{ ucfirst($order->status) }}</span>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-900">Items ordered</h2>
                </div>
                @foreach($order->items as $item)
                    @php
                        $imgSrc = asset('images/electronics.png');
                        if ($item->product->category_id == 2) $imgSrc = asset('images/clothing.png');
                        elseif ($item->product->category_id == 3) $imgSrc = asset('images/home.png');
                    @endphp
                    <div class="flex items-center gap-4 p-5 border-b border-gray-50 last:border-b-0">
                        <div class="w-14 h-14 rounded-xl bg-gray-100 overflow-hidden shrink-0">
                            <img src="{{ $imgSrc }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-grow">
                            <p class="font-medium text-gray-900 text-sm">{{ $item->product->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Qty {{ $item->quantity }} × ₹{{ number_format($item->unit_price / 100, 0) }}</p>
                        </div>
                        <p class="font-semibold text-gray-900 text-sm shrink-0">₹{{ number_format($item->line_total / 100, 0) }}</p>
                    </div>
                @endforeach
            </div>

            @if($order->discountUsages->count() > 0)
                <div class="bg-green-50 border border-green-100 rounded-2xl p-5 mt-4">
                    <p class="text-xs font-semibold text-green-700 uppercase tracking-wide mb-3">Discounts applied</p>
                    <ul class="space-y-2">
                        @foreach($order->discountUsages as $usage)
                            <li class="flex items-center justify-between text-sm">
                                <span class="text-green-800 font-medium">{{ $usage->discount->name ?? 'Discount' }}</span>
                                <span class="font-bold text-green-700">−₹{{ number_format($usage->saved_amount / 100, 0) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6">
                <h2 class="font-semibold text-gray-900 mb-5">Order summary</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal</span>
                        <span class="font-medium text-gray-900">₹{{ number_format($order->subtotal / 100, 0) }}</span>
                    </div>
                    @if($order->discount_total > 0)
                        <div class="flex justify-between text-green-600 font-semibold">
                            <span>Savings</span>
                            <span>−₹{{ number_format($order->discount_total / 100, 0) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-gray-500">
                        <span>Tax</span>
                        <span class="font-medium text-gray-900">₹{{ number_format($order->tax_total / 100, 0) }}</span>
                    </div>
                    <div class="flex justify-between pt-4 border-t border-gray-100">
                        <span class="font-bold text-gray-900">Grand Total</span>
                        <span class="font-bold text-xl text-gray-900">₹{{ number_format($order->grand_total / 100, 0) }}</span>
                    </div>
                </div>
                <div class="mt-6">
                    <a href="{{ route('user.products.index') }}" class="block w-full text-center text-sm font-semibold bg-gray-900 hover:bg-indigo-600 text-white py-3 rounded-lg transition-colors duration-200">
                        Shop again
                    </a>
                </div>
            </div>

            @if($order->shipping_address)
            <div class="bg-white border border-gray-100 rounded-2xl p-6 mt-6 shadow-sm">
                <h2 class="font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Shipping Details</h2>
                <div class="text-sm text-gray-600 space-y-1 font-medium">
                    <p class="font-bold text-gray-900">{{ $order->shipping_name }}</p>
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_postal_code }}</p>
                    <p class="pt-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Phone: <span class="text-gray-800 font-bold">{{ $order->shipping_phone }}</span></p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
