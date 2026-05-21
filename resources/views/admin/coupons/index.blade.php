@extends('layouts.admin')

@section('header', 'Coupon Manager')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Generator Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden sticky top-8 hover-lift">
                <div class="px-6 py-5 border-b border-slate-900 bg-slate-950 relative overflow-hidden">
                    <div class="absolute -top-20 -left-20 w-40 h-40 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
                    <h3 class="text-base font-extrabold text-white flex items-center relative z-10 font-heading">
                        <svg class="w-5 h-5 mr-2.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        Bulk Generate Codes
                    </h3>
                </div>
                <div class="p-6 bg-white/70 backdrop-blur-md">
                    <form action="{{ route('admin.coupons.store') }}" method="POST">
                        @csrf
                        <div class="mb-5">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 font-heading">Target Discount Rule</label>
                            <select name="discount_id" required class="w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 font-semibold shadow-sm text-sm text-slate-800 bg-slate-50/50 focus:bg-white transition-all duration-200 py-3">
                                <option value="">Select a discount...</option>
                                @foreach($activeDiscounts as $discount)
                                    <option value="{{ $discount->id }}">{{ $discount->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-5">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 font-heading">Number of Codes to Generate</label>
                            <input type="number" name="quantity" min="1" max="1000" value="50" required class="w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 font-extrabold shadow-sm text-sm text-slate-800 bg-slate-50/50 focus:bg-white transition-all duration-200 py-3">
                        </div>

                        <div class="mb-5">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 font-heading">Code Prefix (Optional)</label>
                            <input type="text" name="prefix" placeholder="e.g. VIP-" class="w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 font-mono font-bold shadow-sm text-sm text-slate-800 bg-slate-50/50 focus:bg-white transition-all duration-200 py-3 uppercase">
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 font-heading">Max Uses Per Code (Optional)</label>
                            <input type="number" name="max_uses" placeholder="Default: 1" class="w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 font-semibold shadow-sm text-sm text-slate-800 bg-slate-50/50 focus:bg-white transition-all duration-200 py-3">
                        </div>

                        <button type="submit" class="w-full flex justify-center items-center bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-3.5 px-6 rounded-xl transition-all duration-200 shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/30 hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            Generate Coupons
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right: Table of Existing Codes -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover-lift">
                
                <!-- Filter & Search Form -->
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <form action="{{ route('admin.coupons.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">
                        <div class="w-full md:w-1/3">
                            <label class="sr-only">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search code/discount..." class="block w-full pl-10 pr-3.5 py-2.5 text-sm border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 shadow-sm font-semibold text-slate-800 bg-white placeholder-slate-400">
                            </div>
                        </div>
                        
                        <div class="w-full md:w-2/3 flex flex-wrap md:flex-nowrap gap-3 justify-end items-center">
                            <!-- Discount Filter -->
                            <select name="discount_id" onchange="this.form.submit()" class="rounded-xl border-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm shadow-sm py-2.5 font-semibold text-slate-850 bg-white">
                                <option value="">All Discounts</option>
                                @foreach($activeDiscounts as $discount)
                                    <option value="{{ $discount->id }}" {{ request('discount_id') == $discount->id ? 'selected' : '' }}>
                                        {{ $discount->name }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- Status Filter -->
                            <select name="status" onchange="this.form.submit()" class="rounded-xl border-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm shadow-sm py-2.5 font-semibold text-slate-850 bg-white">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="exhausted" {{ request('status') === 'exhausted' ? 'selected' : '' }}>Exhausted</option>
                            </select>

                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-sm py-2.5 px-5 rounded-xl shadow-md transition-all duration-200 hover:-translate-y-0.5 shrink-0">
                                Apply
                            </button>
                            
                            @if(request()->hasAny(['search', 'discount_id', 'status']))
                                <a href="{{ route('admin.coupons.index') }}" class="font-bold text-slate-500 hover:text-indigo-600 px-4 py-2.5 rounded-xl hover:bg-slate-100 transition-all duration-200 text-sm flex items-center justify-center border border-slate-100">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-3.5 text-left text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Attached Discount</th>
                                <th class="px-6 py-3.5 text-center text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Uses</th>
                                <th class="px-6 py-3.5 text-center text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3.5 text-right text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse($coupons ?? [] as $coupon)
                                <tr class="hover:bg-slate-50/40 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-mono font-black text-indigo-600 bg-indigo-50 border border-indigo-100/30">
                                            {{ $coupon->code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-800">{{ $coupon->discount->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-slate-500">
                                        {{ $coupon->usage_count }} / {!! $coupon->max_uses_per_user ?: '&infin;' !!}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($coupon->max_uses_per_user && $coupon->usage_count >= $coupon->max_uses_per_user)
                                            <span class="bg-rose-50 text-rose-700 border border-rose-100/30 text-[10px] px-2.5 py-1 rounded-full font-extrabold uppercase tracking-wider">Exhausted</span>
                                        @else
                                            <span class="bg-emerald-50 text-emerald-700 border border-emerald-100/30 text-[10px] px-2.5 py-1 rounded-full font-extrabold uppercase tracking-wider">Active</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <div class="flex justify-end items-center gap-2" x-data="{ copied: false }">
                                            <button @click="navigator.clipboard.writeText('{{ $coupon->code }}'); copied = true; setTimeout(() => copied = false, 1500)" 
                                                    class="text-slate-400 hover:text-indigo-600 hover:scale-110 transition-all p-1.5 hover:bg-indigo-50 rounded-lg relative" title="Copy code">
                                                <svg x-show="!copied" class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                                <svg x-show="copied" x-cloak class="w-5 h-5 inline text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                            
                                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to revoke/delete this coupon code?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-slate-400 hover:text-rose-600 hover:scale-110 transition-all p-1.5 hover:bg-rose-50 rounded-lg" title="Delete coupon">
                                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                                        <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center mx-auto mb-3 border border-slate-100">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                        </div>
                                        <span class="font-bold text-sm">No coupons generated yet or none matching filters.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($coupons->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $coupons->links() }}
                    </div>
                @endif
            </div>
        </div>
        
    </div>
@endsection
