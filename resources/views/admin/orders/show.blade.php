@extends('layouts.admin')

@section('header', 'Order #' . $order->id)

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-sm font-extrabold text-slate-500 hover:text-indigo-600 transition-colors">
        <svg class="w-4.5 h-4.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
        Back to Orders
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Left: Order Summary, Status Update, Shipping & Applied Discounts --}}
    <div class="space-y-8">

        {{-- Order Summary Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider">Order Summary</h3>
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-mono font-black bg-indigo-50 text-indigo-600 border border-indigo-100/50">
                    #{{ $order->id }}
                </span>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-400 font-bold">Date Placed</span>
                    <span class="font-extrabold text-slate-700">{{ $order->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between items-start text-sm">
                    <span class="text-slate-400 font-bold">Customer</span>
                    <div class="text-right">
                        <p class="font-extrabold text-slate-800">{{ $order->user->name ?? 'Guest' }}</p>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $order->user->email ?? '' }}</p>
                    </div>
                </div>

                <hr class="border-slate-100">

                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-400 font-bold">Subtotal</span>
                    <span class="font-bold text-slate-700">₹{{ number_format($order->subtotal / 100, 2) }}</span>
                </div>
                @if($order->discount_total > 0)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-emerald-600 font-extrabold">Discount Applied</span>
                        <span class="font-extrabold text-emerald-600">-₹{{ number_format($order->discount_total / 100, 2) }}</span>
                    </div>
                @endif
                @if($order->tax_total > 0)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-400 font-bold">Tax</span>
                        <span class="font-bold text-slate-700">₹{{ number_format($order->tax_total / 100, 2) }}</span>
                    </div>
                @endif
                <hr class="border-slate-100">
                <div class="flex justify-between items-center">
                    <span class="text-base font-black text-slate-800">Grand Total</span>
                    <span class="text-xl font-black text-slate-900">₹{{ number_format($order->grand_total / 100, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Status Update Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider">Update Status</h3>
            </div>
            <div class="p-6">
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
                @endphp
                <div class="flex items-center justify-between mb-5">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Current Status</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-black {{ $statusColors[$order->status] ?? 'bg-slate-100 text-slate-700' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="relative mb-4">
                        <select name="status" class="w-full rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700 text-sm px-4 py-3 bg-white outline-none transition-all appearance-none pr-10">
                            @foreach($statuses as $s)
                                <option value="{{ $s }}" @selected($order->status === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-3 rounded-xl transition-all shadow-md shadow-indigo-600/10 hover:shadow-indigo-600/20">
                        Update Status
                    </button>
                </form>
            </div>
        </div>

        {{-- Shipping Details Card --}}
        @if($order->shipping_address)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider">Shipping Details</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Recipient</span>
                        <p class="text-sm font-extrabold text-slate-800 mt-1">{{ $order->shipping_name }}</p>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Address Details</span>
                        <div class="text-sm text-slate-600 font-bold mt-1 space-y-1">
                            <p>{{ $order->shipping_address }}</p>
                            <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_postal_code }}</p>
                        </div>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Contact Phone</span>
                        <p class="text-sm font-black text-indigo-600 font-mono mt-1">{{ $order->shipping_phone }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Discount Usages --}}
        @if($order->discountUsages->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider">Applied Discounts</h3>
                </div>
                <ul class="divide-y divide-slate-100">
                    @foreach($order->discountUsages as $usage)
                        <li class="p-5 flex justify-between items-center hover:bg-slate-50/20 transition-colors">
                            <div>
                                <p class="text-sm font-extrabold text-slate-800">{{ $usage->discount->name ?? 'N/A' }}</p>
                                <p class="text-xs text-slate-400 font-bold mt-0.5">{{ ucfirst(str_replace('_', ' ', $usage->discount->type ?? '')) }}</p>
                            </div>
                            <span class="text-sm font-black text-emerald-600 bg-emerald-500/10 border border-emerald-500/15 px-2.5 py-1 rounded-lg">-₹{{ number_format($usage->saved_amount / 100, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>

    {{-- Right: Order Items --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider">Order Items</h3>
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-extrabold bg-slate-100 text-slate-600 font-mono">
                    {{ $order->items->count() }} item(s)
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-4.5 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider">Product Info</th>
                            <th class="px-6 py-4.5 text-center text-xs font-extrabold text-slate-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-4.5 text-right text-xs font-extrabold text-slate-500 uppercase tracking-wider">Unit Price</th>
                            <th class="px-6 py-4.5 text-right text-xs font-extrabold text-slate-500 uppercase tracking-wider">Line Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($order->items as $item)
                            <tr class="hover:bg-slate-50/20 transition-colors">
                                <td class="px-6 py-4.5">
                                    <p class="text-sm font-extrabold text-slate-800">{{ $item->product->name ?? 'Deleted Product' }}</p>
                                    @if($item->product)
                                        <p class="text-xs font-mono font-bold text-slate-400 mt-1">{{ $item->product->sku }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4.5 text-center text-sm font-black text-slate-700 bg-slate-50/30">{{ $item->quantity }}</td>
                                <td class="px-6 py-4.5 text-right text-sm font-semibold text-slate-600">₹{{ number_format($item->unit_price / 100, 2) }}</td>
                                <td class="px-6 py-4.5 text-right text-sm font-black text-slate-900">₹{{ number_format(($item->unit_price * $item->quantity) / 100, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center text-slate-400 font-bold">No items found for this order.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection
