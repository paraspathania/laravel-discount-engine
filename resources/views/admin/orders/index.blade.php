@extends('layouts.admin')

@section('header', 'Orders')

@section('content')

{{-- Flash --}}
@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-xl font-bold flex items-center">
        <svg class="w-5 h-5 mr-3 shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        {{ session('success') }}
    </div>
@endif

{{-- Stats Cards --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Total Orders -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center">
        <div class="p-3.5 bg-indigo-50 text-indigo-600 rounded-xl mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Orders</p>
            <h3 class="text-2xl font-black text-gray-900 mt-1">{{ $stats['total_orders'] }}</h3>
        </div>
    </div>
    
    <!-- Total Revenue -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center">
        <div class="p-3.5 bg-green-50 text-green-600 rounded-xl mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Revenue</p>
            <h3 class="text-2xl font-black text-gray-900 mt-1">₹{{ number_format($stats['total_revenue'] / 100, 2) }}</h3>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center">
        <div class="p-3.5 bg-yellow-50 text-yellow-600 rounded-xl mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pending Orders</p>
            <h3 class="text-2xl font-black text-gray-900 mt-1">{{ $stats['pending_count'] }}</h3>
        </div>
    </div>

    <!-- Delivered Orders -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center">
        <div class="p-3.5 bg-blue-50 text-blue-600 rounded-xl mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Delivered Orders</p>
            <h3 class="text-2xl font-black text-gray-900 mt-1">{{ $stats['delivered_count'] }}</h3>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="flex flex-col md:flex-row md:items-center gap-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 flex-1 items-center">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by customer or order ID…"
               class="rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm text-sm px-4 py-2 w-64">
        
        <select name="status" class="rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm text-sm px-4 py-2">
            <option value="">All Statuses</option>
            @foreach($statuses as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>

        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">From:</span>
            <input type="date" name="from_date" value="{{ request('from_date') }}"
                   class="rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm text-sm px-3 py-2 w-40">
        </div>

        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">To:</span>
            <input type="date" name="to_date" value="{{ request('to_date') }}"
                   class="rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm text-sm px-3 py-2 w-40">
        </div>

        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-5 py-2 rounded-xl text-sm transition-colors shadow">
            Filter
        </button>
        @if(request()->hasAny(['search','status','from_date','to_date']))
            <a href="{{ route('admin.orders.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-5 py-2 rounded-xl text-sm transition-colors">
                Clear
            </a>
        @endif
    </form>
</div>

{{-- Orders Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Order #</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Subtotal</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Discount</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Grand Total</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-black text-indigo-700">#{{ $order->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-bold text-gray-900">{{ $order->user->name ?? 'Guest' }}</p>
                            <p class="text-xs text-gray-400">{{ $order->user->email ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700 text-right">₹{{ number_format($order->subtotal / 100, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600 text-right">
                            @if($order->discount_total > 0)
                                -₹{{ number_format($order->discount_total / 100, 2) }}
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-gray-900 text-right">₹{{ number_format($order->grand_total / 100, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @php
                                $statusColors = [
                                    'pending'    => 'bg-yellow-100 text-yellow-800',
                                    'confirmed'  => 'bg-blue-100 text-blue-800',
                                    'processing' => 'bg-purple-100 text-purple-800',
                                    'shipped'    => 'bg-indigo-100 text-indigo-800',
                                    'delivered'  => 'bg-green-100 text-green-800',
                                    'cancelled'  => 'bg-red-100 text-red-800',
                                    'refunded'   => 'bg-gray-100 text-gray-700',
                                ];
                                $color = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $color }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 font-medium text-right">{{ $order->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="inline-flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-20 text-center">
                            <svg class="mx-auto h-14 w-14 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <p class="text-gray-500 font-bold text-lg">No orders found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $orders->links() }}
        </div>
    @endif
</div>

@endsection
