@extends('layouts.admin')

@section('header', 'Coupon Manager')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Generator Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden sticky top-8">
                <div class="px-6 py-5 border-b border-gray-100 bg-slate-900">
                    <h3 class="text-lg font-extrabold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        Bulk Generate Codes
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.coupons.store') }}" method="POST">
                        @csrf
                        <div class="mb-5">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Target Discount Rule</label>
                            <select name="discount_id" required class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm py-3">
                                <option value="">Select a discount...</option>
                                @foreach($activeDiscounts as $discount)
                                    <option value="{{ $discount->id }}">{{ $discount->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-5">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Number of Codes to Generate</label>
                            <input type="number" name="quantity" min="1" max="1000" value="50" required class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-bold shadow-sm py-3">
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Code Prefix (Optional)</label>
                            <input type="text" name="prefix" placeholder="e.g. VIP-" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-mono shadow-sm py-3 uppercase">
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Max Uses Per Code (Optional)</label>
                            <input type="number" name="max_uses" placeholder="Default: 1" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm py-3">
                        </div>

                        <button type="submit" class="w-full flex justify-center items-center bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-4 px-6 rounded-xl transition-all shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            Generate Coupons
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right: Table of Existing Codes -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                
                <!-- Filter & Search Form -->
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <form action="{{ route('admin.coupons.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">
                        <div class="w-full md:w-1/3">
                            <label class="sr-only">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search code/discount..." class="block w-full pl-9 pr-3 py-2.5 text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                            </div>
                        </div>
                        
                        <div class="w-full md:w-2/3 flex flex-wrap md:flex-nowrap gap-3 justify-end items-center">
                            <!-- Discount Filter -->
                            <select name="discount_id" onchange="this.form.submit()" class="rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm py-2.5">
                                <option value="">All Discounts</option>
                                @foreach($activeDiscounts as $discount)
                                    <option value="{{ $discount->id }}" {{ request('discount_id') == $discount->id ? 'selected' : '' }}>
                                        {{ $discount->name }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- Status Filter -->
                            <select name="status" onchange="this.form.submit()" class="rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm py-2.5">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="exhausted" {{ request('status') === 'exhausted' ? 'selected' : '' }}>Exhausted</option>
                            </select>

                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm py-2.5 px-4 rounded-lg shadow-sm transition-colors shrink-0">
                                Apply
                            </button>
                            
                            @if(request()->hasAny(['search', 'discount_id', 'status']))
                                <a href="{{ route('admin.coupons.index') }}" class="text-xs font-bold text-gray-500 hover:text-indigo-600 transition-colors ml-1">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Attached Discount</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Uses</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($coupons ?? [] as $coupon)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-black text-indigo-700">{{ $coupon->code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">{{ $coupon->discount->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-500">
                                        {{ $coupon->usage_count }} / {!! $coupon->max_uses_per_user ?: '&infin;' !!}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($coupon->max_uses_per_user && $coupon->usage_count >= $coupon->max_uses_per_user)
                                            <span class="bg-red-100 text-red-800 text-xs px-2.5 py-1 rounded-full font-extrabold uppercase tracking-wider">Exhausted</span>
                                        @else
                                            <span class="bg-green-100 text-green-800 text-xs px-2.5 py-1 rounded-full font-extrabold uppercase tracking-wider">Active</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <div class="flex justify-end items-center gap-3">
                                            <button onclick="navigator.clipboard.writeText('{{ $coupon->code }}'); alert('Copied!');" class="text-gray-400 hover:text-indigo-600 transition-colors" title="Copy code">
                                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                            </button>
                                            
                                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to revoke/delete this coupon code?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" title="Delete coupon">
                                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                        <span class="font-bold">No coupons generated yet or none matching filters.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($coupons->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $coupons->links() }}
                    </div>
                @endif
            </div>
        </div>
        
    </div>
@endsection
