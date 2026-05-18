@extends('layouts.admin')

@section('header', 'Discount Analytics')

@section('content')

    {{-- Filter Panel --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="p-6 bg-slate-900 border-b border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-white font-extrabold flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filter Reports
            </h3>
            <a href="{{ route('admin.analytics.export', request()->query()) }}"
               class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg transition-colors flex items-center text-sm shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export CSV
            </a>
        </div>
        <div class="p-6 bg-white">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Specific Discount</label>
                    <select name="discount_id" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm">
                        <option value="">All Discounts</option>
                        @foreach($allDiscounts as $d)
                            <option value="{{ $d->id }}" @selected(request('discount_id') == $d->id)>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-2.5 px-6 rounded-xl shadow-md transition-colors">Generate Report</button>
                    @if(request()->hasAny(['from_date','to_date','discount_id']))
                        <a href="{{ route('admin.analytics.index') }}" class="font-bold text-gray-500 hover:text-gray-800 px-3 py-2.5 rounded-xl hover:bg-gray-100 transition-colors text-sm">Clear</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl p-8 text-white shadow-lg relative overflow-hidden">
            <svg class="absolute -right-4 -bottom-4 w-32 h-32 opacity-10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path></svg>
            <p class="text-indigo-100 font-bold uppercase tracking-wider text-sm mb-1">Total Redemptions</p>
            <h3 class="text-5xl font-black">{{ $summary['total_redemptions'] }}</h3>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-emerald-700 rounded-2xl p-8 text-white shadow-lg relative overflow-hidden">
            <svg class="absolute -right-4 -bottom-4 w-32 h-32 opacity-10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path></svg>
            <p class="text-green-100 font-bold uppercase tracking-wider text-sm mb-1">Total Customer Savings</p>
            <h3 class="text-5xl font-black">₹{{ number_format($summary['total_saved'] / 100, 2) }}</h3>
        </div>
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
            <p class="text-gray-500 font-bold uppercase tracking-wider text-xs mb-3">Top Performers</p>
            <ul class="space-y-2">
                @forelse($topDiscounts as $top)
                    <li class="flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-800 truncate">{{ $top->discount->name ?? 'N/A' }}</span>
                        <span class="text-xs font-black text-indigo-600 ml-2 shrink-0">{{ $top->uses }} uses</span>
                    </li>
                @empty
                    <li class="text-sm text-gray-400 font-medium">No data yet.</li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- Detailed Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date/Time</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Discount Rule Used</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Amount Saved</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($usages as $usage)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">{{ $usage->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $usage->order->user->email ?? 'Guest' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.orders.show', $usage->order_id) }}" class="text-sm font-mono text-indigo-600 font-bold hover:underline">#{{ $usage->order_id }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-bold">{{ $usage->discount->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-green-600 text-right">₹{{ number_format($usage->saved_amount / 100, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                <span class="font-bold">No analytic data found for this period.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($usages, 'links') && $usages->hasPages())
        <div class="mt-6">
            {{ $usages->links() }}
        </div>
    @endif

@endsection
