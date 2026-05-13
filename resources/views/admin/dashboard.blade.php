@extends('layouts.admin')

@section('header', 'Dashboard Overview')

@section('content')
    <!-- 4 Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 mr-4 relative z-10">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Active Discounts</p>
                <h3 class="text-3xl font-black text-gray-900 mt-1">{{ $stats['active_discounts'] ?? 12 }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center text-green-600 mr-4 relative z-10">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Coupons Used</p>
                <h3 class="text-3xl font-black text-gray-900 mt-1">{{ $stats['coupons_used'] ?? 145 }} <span class="text-xs font-bold text-green-500 ml-1">Today</span></h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-purple-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600 mr-4 relative z-10">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Savings</p>
                <h3 class="text-3xl font-black text-gray-900 mt-1">₹{{ number_format(($stats['total_savings'] ?? 15430) / 100, 2) }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600 mr-4 relative z-10">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Orders Today</p>
                <h3 class="text-3xl font-black text-gray-900 mt-1">{{ $stats['orders_today'] ?? 42 }}</h3>
            </div>
        </div>

    </div>

    <!-- Data Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Recent Redemptions -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-white">
                <h3 class="text-lg font-extrabold text-gray-900">Recent Redemptions</h3>
                <a href="{{ route('admin.analytics.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800">View All</a>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Coupon Code</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Discount</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Saved Amount</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($recentRedemptions ?? [] as $redemption)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $redemption->order->user->name ?? 'Guest' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-bold text-indigo-600">{{ $redemption->coupon->code ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $redemption->discount->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-extrabold text-green-600 text-right">₹{{ number_format($redemption->saved_amount / 100, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium text-right">{{ $redemption->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 font-medium">No recent redemptions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Performing Discounts -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
            <div class="px-6 py-5 border-b border-gray-100 bg-white">
                <h3 class="text-lg font-extrabold text-gray-900">Top Performing</h3>
            </div>
            <ul class="divide-y divide-gray-100 flex-1 overflow-y-auto">
                @forelse($topDiscounts ?? [] as $discount)
                    <li class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="text-sm font-extrabold text-gray-900">{{ $discount->name }}</h4>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800">
                                {{ $discount->usages_count }} Uses
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-medium text-gray-500">{{ ucfirst(str_replace('_', ' ', $discount->type)) }}</span>
                            <span class="font-black text-green-600">Saved: ₹{{ number_format($discount->usages_sum_saved_amount / 100, 2) }}</span>
                        </div>
                    </li>
                @empty
                    <li class="p-12 text-center text-gray-500 font-medium">Not enough data to display top performers.</li>
                @endforelse
            </ul>
        </div>

    </div>
@endsection
