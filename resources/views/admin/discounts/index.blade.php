@extends('layouts.admin')

@section('header', 'Discounts Management')

@section('content')
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">
        <form method="GET" class="flex gap-3 w-full md:w-1/2">
            <div class="relative w-full max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search discounts..." class="block w-full pl-10 pr-3.5 py-2.5 text-sm border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 shadow-sm font-semibold text-slate-800 bg-white placeholder-slate-400">
            </div>
            <button type="submit" class="bg-white border border-slate-200 text-slate-600 font-extrabold py-2.5 px-6 rounded-xl hover:bg-slate-50 shadow-sm transition-all duration-200 hover:-translate-y-0.5">Filter</button>
        </form>

        <a href="{{ route('admin.discounts.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-2.5 px-6 rounded-xl shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/30 transition-all duration-200 flex items-center hover:-translate-y-0.5 self-start md:self-auto shrink-0">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            Create New Discount
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover-lift">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3.5 text-left text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Type / Value</th>
                        <th class="px-6 py-3.5 text-left text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Usage (Limit)</th>
                        <th class="px-6 py-3.5 text-left text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Schedule</th>
                        <th class="px-6 py-3.5 text-center text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-right text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($discounts ?? [] as $discount)
                        <tr class="hover:bg-slate-50/40 transition-colors group">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="font-extrabold text-slate-900 text-base leading-snug">{{ $discount->name }}</div>
                                @if($discount->is_stackable)
                                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-100/30 text-[10px] px-2 py-0.5 mt-1.5 rounded-md font-extrabold uppercase tracking-wider inline-block">Stackable</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="px-2 py-0.5 inline-flex text-[9px] leading-5 font-extrabold rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100/20 uppercase tracking-wider mb-1">
                                    {{ str_replace('_', ' ', $discount->type) }}
                                </span>
                                <div class="font-black text-slate-900 text-sm">
                                    @if($discount->type === 'percentage')
                                        {{ $discount->value / 100 }}% OFF
                                    @elseif($discount->type === 'fixed_amount')
                                        ₹{{ number_format($discount->value / 100, 2) }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center text-sm font-semibold text-slate-800">
                                    <span>{{ $discount->usage_count }}</span>
                                    <span class="text-slate-400 mx-1">/</span>
                                    <span class="text-slate-500 font-medium">{!! $discount->usage_limit ?: '&infin;' !!}</span>
                                </div>
                                @if($discount->usage_limit && $discount->usage_count >= $discount->usage_limit)
                                    <span class="bg-rose-50 text-rose-700 border border-rose-100/30 text-[10px] px-2 py-0.5 rounded-md mt-1.5 font-extrabold uppercase tracking-wider inline-block">Limit Reached</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center text-xs font-semibold text-slate-600">
                                    <svg class="w-4 h-4 text-emerald-500 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $discount->starts_at->format('M j, Y') }}
                                </div>
                                @if($discount->ends_at)
                                    <div class="flex items-center mt-1.5 text-xs text-slate-400 font-medium">
                                        <svg class="w-4 h-4 text-slate-300 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        {{ $discount->ends_at->format('M j, Y') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-center">
                                @if($discount->is_active && (!$discount->ends_at || $discount->ends_at->isFuture()))
                                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-100/30 text-[10px] px-2.5 py-1 rounded-full font-extrabold uppercase tracking-wider">Active</span>
                                @else
                                    <span class="bg-rose-50 text-rose-700 border border-rose-100/30 text-[10px] px-2.5 py-1 rounded-full font-extrabold uppercase tracking-wider">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity duration-200">
                                    <a href="{{ route('admin.discounts.edit', $discount) }}" class="text-slate-400 hover:text-indigo-600 hover:scale-110 transition-all p-1.5 hover:bg-indigo-50 rounded-lg" title="Edit Discount">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h14a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" onsubmit="return confirm('Delete this discount entirely?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-rose-600 hover:scale-110 transition-all p-1.5 hover:bg-rose-50 rounded-lg" title="Delete Discount">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center text-slate-400">
                                <div class="mx-auto w-12 h-12 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                </div>
                                <h3 class="text-base font-extrabold text-slate-900 font-heading">No discounts found</h3>
                                <p class="text-xs text-slate-400 font-semibold mt-1 mb-5">Get started by creating your first promotional offer.</p>
                                <a href="{{ route('admin.discounts.create') }}" class="inline-flex bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs py-2.5 px-5 rounded-xl shadow-md transition-all duration-200 hover:-translate-y-0.5">Create Discount</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if(isset($discounts) && method_exists($discounts, 'links'))
        <div class="mt-6">
            {{ $discounts->links() }}
        </div>
    @endif
@endsection
