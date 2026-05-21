@extends('layouts.admin')

@section('header', 'Order #' . $order->id)

@section('content')

{{-- Flash --}}
@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-xl font-bold flex items-center">
        <svg class="w-5 h-5 mr-3 shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        {{ session('success') }}
    </div>
@endif

<div class="mb-4">
    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-indigo-600 transition-colors">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Back to Orders
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left: Order Summary + Status Update --}}
    <div class="space-y-6">

        {{-- Order Summary Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-slate-900">
                <h3 class="text-base font-extrabold text-white">Order Summary</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500 font-medium">Order ID</span>
                    <span class="text-sm font-black text-indigo-600 font-mono">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500 font-medium">Date</span>
                    <span class="text-sm font-bold text-gray-800">{{ $order->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500 font-medium">Customer</span>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-800">{{ $order->user->name ?? 'Guest' }}</p>
                        <p class="text-xs text-gray-400">{{ $order->user->email ?? '' }}</p>
                    </div>
                </div>

                <hr class="border-gray-100">

                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500 font-medium">Subtotal</span>
                    <span class="text-sm font-semibold text-gray-800">₹{{ number_format($order->subtotal / 100, 2) }}</span>
                </div>
                @if($order->discount_total > 0)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-green-600 font-bold">Discount Applied</span>
                        <span class="text-sm font-black text-green-600">-₹{{ number_format($order->discount_total / 100, 2) }}</span>
                    </div>
                @endif
                @if($order->tax_total > 0)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 font-medium">Tax</span>
                        <span class="text-sm font-semibold text-gray-600">₹{{ number_format($order->tax_total / 100, 2) }}</span>
                    </div>
                @endif
                <hr class="border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-base font-black text-gray-900">Grand Total</span>
                    <span class="text-xl font-black text-gray-900">₹{{ number_format($order->grand_total / 100, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Status Update Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-base font-extrabold text-gray-900">Update Status</h3>
            </div>
            <div class="p-6">
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
                @endphp
                <p class="text-sm text-gray-500 mb-3">Current status:</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-black mb-4 {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ ucfirst($order->status) }}
                </span>
                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                    @csrf @method('PATCH')
                    <select name="status" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm py-2.5 mb-4">
                        @foreach($statuses as $s)
                            <option value="{{ $s }}" @selected($order->status === $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-2.5 rounded-xl transition-colors shadow">
                        Update Status
                    </button>
                </form>
            </div>
        </div>

        {{-- Shipping Details Card --}}
        @if($order->shipping_address)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-slate-900">
                    <h3 class="text-base font-extrabold text-white">Shipping Details</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Recipient Name</span>
                        <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $order->shipping_name }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Delivery Address</span>
                        <div class="text-sm text-gray-700 font-medium mt-0.5 space-y-0.5">
                            <p>{{ $order->shipping_address }}</p>
                            <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_postal_code }}</p>
                        </div>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Contact Phone</span>
                        <p class="text-sm font-black text-indigo-600 font-mono mt-0.5">{{ $order->shipping_phone }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Discount Usages --}}
        @if($order->discountUsages->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="text-base font-extrabold text-gray-900">Discounts Applied</h3>
                </div>
                <ul class="divide-y divide-gray-100">
                    @foreach($order->discountUsages as $usage)
                        <li class="p-5 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $usage->discount->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-400">{{ ucfirst(str_replace('_', ' ', $usage->discount->type ?? '')) }}</p>
                            </div>
                            <span class="text-sm font-black text-green-600">-₹{{ number_format($usage->saved_amount / 100, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>

    {{-- Right: Order Items --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-base font-extrabold text-gray-900">Order Items ({{ $order->items->count() }})</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Qty</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Unit Price</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Line Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($order->items as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900">{{ $item->product->name ?? 'Deleted Product' }}</p>
                                    @if($item->product)
                                        <p class="text-xs font-mono text-gray-400">{{ $item->product->sku }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-bold text-gray-700">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right text-sm font-semibold text-gray-700">₹{{ number_format($item->unit_price / 100, 2) }}</td>
                                <td class="px-6 py-4 text-right text-sm font-black text-gray-900">₹{{ number_format(($item->unit_price * $item->quantity) / 100, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400 font-medium">No items found for this order.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection
