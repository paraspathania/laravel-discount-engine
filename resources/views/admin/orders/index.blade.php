@extends('layouts.admin')

@section('header', 'Orders')

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Orders -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center hover-lift">
        <div class="p-3.5 bg-indigo-500/10 text-indigo-600 rounded-xl mr-4 border border-indigo-500/10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Orders</p>
            <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $stats['total_orders'] }}</h3>
        </div>
    </div>
    
    <!-- Total Revenue -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center hover-lift">
        <div class="p-3.5 bg-emerald-500/10 text-emerald-600 rounded-xl mr-4 border border-emerald-500/10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Revenue</p>
            <h3 class="text-2xl font-black text-slate-800 mt-1">₹{{ number_format($stats['total_revenue'] / 100, 2) }}</h3>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center hover-lift">
        <div class="p-3.5 bg-amber-500/10 text-amber-600 rounded-xl mr-4 border border-amber-500/10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pending Orders</p>
            <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $stats['pending_count'] }}</h3>
        </div>
    </div>

    <!-- Delivered Orders -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center hover-lift">
        <div class="p-3.5 bg-sky-500/10 text-sky-600 rounded-xl mr-4 border border-sky-500/10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Delivered Orders</p>
            <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $stats['delivered_count'] }}</h3>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="flex flex-col md:flex-row md:items-center gap-4 mb-8 bg-slate-100/50 p-4 rounded-2xl border border-slate-200/40">
    <form method="GET" class="flex flex-wrap gap-4 flex-1 items-center">
        <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer or order ID…"
                   class="rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700 placeholder:text-slate-400 text-sm px-4 py-2.5 w-64 bg-white shadow-sm outline-none transition-all">
        </div>
        
        <div class="relative">
            <select name="status" class="rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700 text-sm px-4 py-2.5 bg-white shadow-sm outline-none transition-all appearance-none pr-10">
                <option value="">All Statuses</option>
                @foreach($statuses as $s)
                    <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>

        <div class="flex items-center gap-2.5 bg-white border border-slate-200 px-3.5 py-1.5 rounded-xl shadow-sm">
            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">From</span>
            <input type="date" name="from_date" value="{{ request('from_date') }}"
                   class="font-bold text-slate-700 text-sm bg-transparent outline-none focus:text-indigo-600 transition-colors">
        </div>

        <div class="flex items-center gap-2.5 bg-white border border-slate-200 px-3.5 py-1.5 rounded-xl shadow-sm">
            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">To</span>
            <input type="date" name="to_date" value="{{ request('to_date') }}"
                   class="font-bold text-slate-700 text-sm bg-transparent outline-none focus:text-indigo-600 transition-colors">
        </div>

        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold px-6 py-2.5 rounded-xl text-sm transition-all shadow-md shadow-indigo-600/10">
            Filter
        </button>
        @if(request()->hasAny(['search','status','from_date','to_date']))
            <a href="{{ route('admin.orders.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-extrabold px-5 py-2.5 rounded-xl text-sm transition-colors">
                Clear
            </a>
        @endif
    </form>
</div>

{{-- Orders Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-100">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-6 py-4.5 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Order</th>
                    <th class="px-6 py-4.5 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-4.5 text-right text-xs font-extrabold text-slate-500 uppercase tracking-wider">Subtotal</th>
                    <th class="px-6 py-4.5 text-right text-xs font-extrabold text-slate-500 uppercase tracking-wider">Discount</th>
                    <th class="px-6 py-4.5 text-right text-xs font-extrabold text-slate-500 uppercase tracking-wider">Grand Total</th>
                    <th class="px-6 py-4.5 text-center text-xs font-extrabold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4.5 text-right text-xs font-extrabold text-slate-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4.5 text-right text-xs font-extrabold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($orders as $order)
                    <tr class="hover:bg-slate-50/40 transition-colors">
                        <td class="px-6 py-4.5 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-mono font-black bg-indigo-50 text-indigo-600 border border-indigo-100/50">
                                #{{ $order->id }}
                            </span>
                        </td>
                        <td class="px-6 py-4.5 whitespace-nowrap">
                            <p class="text-sm font-extrabold text-slate-800">{{ $order->user->name ?? 'Guest' }}</p>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $order->user->email ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4.5 whitespace-nowrap text-sm font-semibold text-slate-600 text-right">₹{{ number_format($order->subtotal / 100, 2) }}</td>
                        <td class="px-6 py-4.5 whitespace-nowrap text-sm font-extrabold text-emerald-600 text-right">
                            @if($order->discount_total > 0)
                                -₹{{ number_format($order->discount_total / 100, 2) }}
                            @else
                                <span class="text-slate-300 font-normal">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4.5 whitespace-nowrap text-sm font-black text-slate-900 text-right">₹{{ number_format($order->grand_total / 100, 2) }}</td>
                        <td class="px-6 py-4.5 whitespace-nowrap text-center">
                            @php
                                $statusColors = [
                                    'pending'    => 'bg-amber-500/10 text-amber-600 border border-amber-500/20',
                                    'confirmed'  => 'bg-blue-500/10 text-blue-600 border border-blue-500/20',
                                    'processing' => 'bg-purple-500/10 text-purple-600 border border-purple-500/20',
                                    'shipped'    => 'bg-indigo-500/10 text-indigo-600 border border-indigo-500/20',
                                    'delivered'  => 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20',
                                    'cancelled'  => 'bg-rose-500/10 text-rose-600 border border-rose-500/20',
                                    'refunded'   => 'bg-slate-500/10 text-slate-600 border border-slate-500/20',
                                ];
                                $color = $statusColors[$order->status] ?? 'bg-slate-100 text-slate-700';
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-extrabold {{ $color }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4.5 whitespace-nowrap text-xs text-slate-400 font-bold text-right">{{ $order->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4.5 whitespace-nowrap text-right">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="inline-flex items-center text-xs font-extrabold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-2 rounded-lg transition-all">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-24 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            <p class="text-slate-500 font-extrabold text-lg">No orders found.</p>
                            <p class="text-slate-400 text-xs mt-1.5 font-medium">Orders placed by customers will be visible here.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50">
            {{ $orders->links() }}
        </div>
    @endif
</div>

@endsection
