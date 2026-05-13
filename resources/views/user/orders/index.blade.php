@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">My Orders</h1>
            <p class="text-gray-500 mt-2 font-medium">Review your order history and track your savings.</p>
        </div>

        <div class="bg-white shadow-sm sm:rounded-2xl overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Items</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Discount Saved</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-6 whitespace-nowrap text-sm font-black text-gray-900">#{{ $order->id }}</td>
                                <td class="px-6 py-6 whitespace-nowrap text-sm text-gray-600 font-medium">{{ $order->created_at->format('M j, Y') }}</td>
                                <td class="px-6 py-6 whitespace-nowrap text-sm text-gray-600 font-medium text-center">{{ $order->items->sum('quantity') }}</td>
                                <td class="px-6 py-6 whitespace-nowrap text-sm font-black text-gray-900 text-right">₹{{ number_format($order->grand_total / 100, 2) }}</td>
                                <td class="px-6 py-6 whitespace-nowrap text-sm font-extrabold text-green-600 text-right">
                                    @if($order->discount_total > 0)
                                        -₹{{ number_format($order->discount_total / 100, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap text-center">
                                    @if($order->status === 'completed')
                                        <x-badge color="green" text="Completed" />
                                    @elseif($order->status === 'pending')
                                        <x-badge color="yellow" text="Pending" />
                                    @else
                                        <x-badge color="gray" text="{{ $order->status }}" />
                                    @endif
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('user.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900 font-bold bg-indigo-50 px-4 py-2 rounded-lg hover:bg-indigo-100 transition-colors">View Details</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    <h3 class="text-lg font-bold text-gray-900">No orders found</h3>
                                    <p class="text-gray-500 font-medium mt-1">You haven't placed any orders yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
