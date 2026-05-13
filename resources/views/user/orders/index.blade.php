@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">My Orders</h1>
        <p class="text-sm text-gray-400 mt-1">Your complete order history.</p>
    </div>

    @forelse($orders as $order)
        <div class="bg-white border border-gray-100 rounded-2xl mb-4 hover:border-gray-200 hover:shadow-sm transition-all duration-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-5 gap-4">
                <div class="flex items-center gap-5">
                    <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Order #{{ $order->id }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('M j, Y · g:i A') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-6 sm:gap-8 text-sm">
                    <div class="text-center">
                        <p class="text-xs text-gray-400 mb-0.5">Items</p>
                        <p class="font-semibold text-gray-900">{{ $order->items->sum('quantity') }}</p>
                    </div>
                    @if($order->discount_total > 0)
                        <div class="text-center">
                            <p class="text-xs text-gray-400 mb-0.5">Saved</p>
                            <p class="font-semibold text-green-600">₹{{ number_format($order->discount_total / 100, 0) }}</p>
                        </div>
                    @endif
                    <div class="text-center">
                        <p class="text-xs text-gray-400 mb-0.5">Total</p>
                        <p class="font-bold text-gray-900">₹{{ number_format($order->grand_total / 100, 0) }}</p>
                    </div>
                    <div>
                        @if($order->status === 'completed')
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-green-50 text-green-700">Completed</span>
                        @elseif($order->status === 'pending')
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700">Pending</span>
                        @else
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-100 text-gray-600">{{ ucfirst($order->status) }}</span>
                        @endif
                    </div>
                    <a href="{{ route('user.orders.show', $order) }}"
                        class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors whitespace-nowrap">
                        View →
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white border border-gray-100 rounded-2xl py-24 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">No orders yet</h2>
            <p class="text-sm text-gray-400 mb-6">Place your first order to see it here.</p>
            <a href="{{ route('user.products.index') }}" class="inline-flex items-center gap-2 bg-gray-900 hover:bg-indigo-600 text-white text-sm font-semibold px-6 py-3 rounded-lg transition-colors duration-200">
                Start shopping
            </a>
        </div>
    @endforelse

    @if($orders->hasPages())
        <div class="mt-8">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
