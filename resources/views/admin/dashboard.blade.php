@extends('layouts.admin')

@section('header', 'Dashboard Overview')

@section('content')
    <!-- 4 Premium Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Card 1: Active Discounts -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center hover-lift overflow-hidden relative group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50/40 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mr-4 relative z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Discounts</p>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1.5">{{ $stats['active_discounts'] ?? 0 }}</h3>
            </div>
        </div>

        <!-- Card 2: Coupons Used -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center hover-lift overflow-hidden relative group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50/40 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mr-4 relative z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Coupons Used</p>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1.5">
                    {{ $stats['coupons_used'] ?? 0 }}
                    <span class="text-[10px] font-extrabold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full ml-1.5">Today</span>
                </h3>
            </div>
        </div>

        <!-- Card 3: Total Savings -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center hover-lift overflow-hidden relative group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-violet-50/40 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="w-12 h-12 bg-violet-50 text-violet-600 rounded-xl flex items-center justify-center mr-4 relative z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Savings</p>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1.5">₹{{ number_format(($stats['total_savings'] ?? 0) / 100, 2) }}</h3>
            </div>
        </div>

        <!-- Card 4: Orders Today -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center hover-lift overflow-hidden relative group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50/40 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mr-4 relative z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Orders Today</p>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1.5">{{ $stats['orders_today'] ?? 0 }}</h3>
            </div>
        </div>

    </div>

    <!-- Data Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Recent Redemptions Table -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col h-full">
            <div class="px-6 py-5 border-b border-slate-100/60 flex justify-between items-center bg-white">
                <h3 class="text-base font-extrabold text-slate-950 font-heading">Recent Redemptions</h3>
                <a href="{{ route('admin.analytics.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1">
                    View Report &rarr;
                </a>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3.5 text-left text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Order</th>
                            <th class="px-6 py-3.5 text-left text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Discount</th>
                            <th class="px-6 py-3.5 text-right text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Saved Amount</th>
                            <th class="px-6 py-3.5 text-right text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($recentRedemptions ?? [] as $redemption)
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-800">{{ $redemption->order->user->name ?? 'Guest' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-mono font-bold text-indigo-600 bg-indigo-50 border border-indigo-100/30">
                                        #{{ $redemption->order_id }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 font-medium">{{ $redemption->discount->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-extrabold text-emerald-600 text-right">₹{{ number_format($redemption->saved_amount / 100, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-400 font-bold text-right">{{ $redemption->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-450 font-medium text-sm">No recent redemptions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Performing Discounts Card -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col h-full">
            <div class="px-6 py-5 border-b border-slate-100/60 bg-white">
                <h3 class="text-base font-extrabold text-slate-950 font-heading">Top Performing</h3>
            </div>
            <ul class="divide-y divide-slate-100 flex-1 overflow-y-auto">
                @forelse($topDiscounts ?? [] as $discount)
                    <li class="p-5 hover:bg-slate-50/40 transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="text-sm font-bold text-slate-800 leading-snug">{{ $discount->name }}</h4>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100/20">
                                {{ $discount->usages_count }} Uses
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-xs mt-3">
                            <span class="font-bold text-slate-400 uppercase tracking-wider text-[9px] bg-slate-100 px-2 py-0.5 rounded-md">{{ ucfirst(str_replace('_', ' ', $discount->type)) }}</span>
                            <span class="font-extrabold text-emerald-600 text-sm">Saved: ₹{{ number_format($discount->usages_sum_saved_amount / 100, 2) }}</span>
                        </div>
                    </li>
                @empty
                    <li class="p-12 text-center text-slate-400 font-medium text-sm">Not enough data to display top performers.</li>
                @endforelse
            </ul>
        </div>

    </div>
@endsection
