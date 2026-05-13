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
                                <!-- Map $activeDiscounts here if available -->
                                <option value="1">Black Friday Sale (20% OFF)</option>
                                <option value="2">Welcome Bonus (₹10 OFF)</option>
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
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-extrabold text-gray-900">Existing Coupon Codes</h3>
                    <div class="w-64">
                        <input type="text" placeholder="Search codes..." class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm">
                    </div>
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
                                        {{ $coupon->usage_count }} / {{ $coupon->max_uses ?: '&infin;' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($coupon->max_uses && $coupon->usage_count >= $coupon->max_uses)
                                            <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded font-bold uppercase">Exhausted</span>
                                        @else
                                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded font-bold uppercase">Active</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <button onclick="navigator.clipboard.writeText('{{ $coupon->code }}'); alert('Copied!');" class="text-gray-400 hover:text-indigo-600 transition-colors">
                                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                        <span class="font-bold">No coupons generated yet.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
@endsection
