@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="text-gray-500 mt-2 font-medium">Manage your orders and discover available coupons below.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('user.products.index') }}" class="bg-gray-900 hover:bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm">
                Start Shopping
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Recent Orders -->
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Recent Orders</h2>
                <a href="{{ route('user.orders.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors">View All →</a>
            </div>

            @forelse($recentOrders as $order)
                <div class="bg-white border border-gray-100 rounded-2xl p-5 mb-4 hover:border-gray-200 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100">
                            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">Order #{{ $order->id }}</p>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $order->created_at->format('M j, Y') }} • {{ $order->items->sum('quantity') }} items</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs text-gray-400 font-medium mb-0.5">Total Paid</p>
                            <p class="font-bold text-gray-900 text-lg">₹{{ number_format($order->grand_total / 100, 0) }}</p>
                        </div>
                        <a href="{{ route('user.orders.show', $order) }}" class="bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition-colors border border-gray-200">
                            Details
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white border border-gray-100 rounded-2xl p-10 text-center shadow-sm">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <p class="font-bold text-gray-900 mb-1">No orders yet</p>
                    <p class="text-gray-500 text-sm">Your order history will appear here once you make a purchase.</p>
                </div>
            @endforelse
        </div>

        <!-- Right: My Coupons -->
        <div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">My Coupons</h2>
            </div>

            <div class="space-y-4">
                @forelse($coupons as $coupon)
                    <div class="bg-white border-2 border-dashed border-indigo-200 rounded-2xl overflow-hidden shadow-sm relative group hover:border-indigo-400 transition-colors">
                        <div class="absolute top-0 right-0 bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase px-2 py-1 rounded-bl-lg">
                            Ready to use
                        </div>
                        <div class="p-5">
                            <h3 class="font-extrabold text-gray-900 mb-1">{{ $coupon->discount->name }}</h3>
                            <p class="text-xs text-gray-500 mb-4 line-clamp-2">
                                @if($coupon->discount->type === 'percentage')
                                    Get {{ $coupon->discount->value / 100 }}% OFF
                                @else
                                    Save ₹{{ number_format($coupon->discount->value / 100, 0) }}
                                @endif
                                on eligible items.
                            </p>
                            
                            <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-xl p-2 pl-4">
                                <span class="font-mono font-black text-indigo-700 tracking-wider text-sm">{{ $coupon->code }}</span>
                                <button onclick="navigator.clipboard.writeText('{{ $coupon->code }}'); alert('Copied!');" class="bg-white hover:bg-indigo-50 text-gray-500 hover:text-indigo-600 border border-gray-200 p-2 rounded-lg transition-colors shadow-sm" title="Copy Code">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white border border-gray-100 rounded-2xl p-8 text-center shadow-sm">
                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </div>
                        <p class="font-bold text-gray-900 text-sm">No coupons available</p>
                        <p class="text-gray-500 text-xs mt-1">Check back later for special discounts and offers.</p>
                    </div>
                @endforelse
            </div>
            
            @if(count($coupons) > 0)
                <div class="mt-4 text-center">
                    <p class="text-xs text-gray-400">Copy a code and apply it in your shopping cart.</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
