@extends('layouts.admin')

@section('header', 'Discounts Management')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <form method="GET" class="flex gap-4 w-1/2">
            <div class="relative w-full max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search discounts..." class="block w-full pl-10 pr-3 py-3 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 font-medium shadow-sm">
            </div>
            <button type="submit" class="bg-white border border-gray-300 text-gray-700 font-bold py-3 px-6 rounded-xl hover:bg-gray-50 shadow-sm transition-colors">Filter</button>
        </form>

        <a href="{{ route('admin.discounts.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Create New Discount
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">Type / Value</th>
                        <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">Usage (Limit)</th>
                        <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">Schedule</th>
                        <th class="px-6 py-4 text-center text-xs font-extrabold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-extrabold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($discounts ?? [] as $discount)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="font-extrabold text-gray-900 text-base">{{ $discount->name }}</div>
                                @if($discount->is_stackable)
                                    <span class="text-xs font-bold text-green-600 uppercase tracking-wider block mt-1">Stackable</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-blue-100 text-blue-800 uppercase tracking-wider mb-1">
                                    {{ str_replace('_', ' ', $discount->type) }}
                                </span>
                                <div class="font-black text-gray-900">
                                    @if($discount->type === 'percentage')
                                        {{ $discount->value / 100 }}% OFF
                                    @elseif($discount->type === 'fixed_amount')
                                        ${{ number_format($discount->value / 100, 2) }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="font-bold text-gray-900">{{ $discount->usage_count }}</div>
                                    <div class="text-gray-400 mx-1">/</div>
                                    <div class="font-medium text-gray-500">{{ $discount->usage_limit ?: '&infin;' }}</div>
                                </div>
                                @if($discount->usage_limit && $discount->usage_count >= $discount->usage_limit)
                                    <span class="text-xs font-bold text-red-500 mt-1 block">Limit Reached</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-600 font-medium">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-green-500 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $discount->starts_at->format('M j, Y') }}
                                </div>
                                @if($discount->ends_at)
                                    <div class="flex items-center mt-1 text-gray-400">
                                        <svg class="w-4 h-4 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        {{ $discount->ends_at->format('M j, Y') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-center">
                                @if($discount->is_active && (!$discount->ends_at || $discount->ends_at->isFuture()))
                                    <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full font-bold uppercase tracking-wider">Active</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs px-3 py-1 rounded-full font-bold uppercase tracking-wider">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.discounts.edit', $discount) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" onsubmit="return confirm('Delete this discount entirely?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                </div>
                                <h3 class="text-xl font-extrabold text-gray-900">No discounts found</h3>
                                <p class="text-gray-500 font-medium mt-1 mb-6">Get started by creating your first promotional offer.</p>
                                <a href="{{ route('admin.discounts.create') }}" class="bg-indigo-600 text-white font-bold py-2.5 px-6 rounded-lg hover:bg-indigo-700 transition-colors">Create Discount</a>
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
