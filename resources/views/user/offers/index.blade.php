@extends('layouts.app')

@section('content')

{{-- Header --}}
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <p class="text-xs font-semibold tracking-widest text-indigo-600 uppercase mb-1">Savings</p>
                <h1 class="text-3xl font-bold text-gray-900">Offers & Deals</h1>
            </div>
            {{-- Filter tabs --}}
            <nav class="flex gap-1 bg-gray-100 p-1 rounded-lg">
                @foreach(['all' => 'All', 'percentage' => '% Off', 'fixed' => 'Fixed', 'coupon' => 'Coupon'] as $key => $label)
                    <a href="{{ route('user.offers.index', ['filter' => $key]) }}"
                       class="text-xs font-semibold px-3 py-1.5 rounded-md transition-colors
                              {{ $filter === $key ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </nav>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($offers as $offer)
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden hover:shadow-md hover:border-gray-200 transition-all duration-200 flex flex-col">

                {{-- Top colored strip based on discount type --}}
                <div class="h-1.5 {{ $offer->type === 'percentage' ? 'bg-green-400' : ($offer->type === 'fixed_amount' ? 'bg-blue-400' : 'bg-purple-400') }}"></div>

                <div class="p-6 flex flex-col flex-grow">
                    {{-- Type + stackable --}}
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full
                            {{ $offer->type === 'percentage' ? 'bg-green-50 text-green-700' : ($offer->type === 'fixed_amount' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700') }}">
                            {{ str_replace('_', ' ', ucfirst($offer->type)) }}
                        </span>
                        @if($offer->is_stackable)
                            <span class="text-[11px] font-medium text-gray-400">Stackable</span>
                        @endif
                    </div>

                    {{-- Value --}}
                    <div class="mb-3">
                        <p class="text-3xl font-black {{ $offer->type === 'percentage' ? 'text-green-600' : 'text-indigo-600' }}">
                            @if($offer->type === 'percentage')
                                {{ $offer->value / 100 }}% OFF
                            @elseif($offer->type === 'fixed_amount')
                                ₹{{ number_format($offer->value / 100, 0) }} OFF
                            @else
                                Special Deal
                            @endif
                        </p>
                    </div>

                    {{-- Name --}}
                    <h3 class="font-semibold text-gray-900 mb-3 leading-snug">
                        <a href="{{ route('user.offers.show', $offer) }}" class="hover:text-indigo-600 transition-colors">
                            {{ $offer->name }}
                        </a>
                    </h3>

                    {{-- Details list --}}
                    <ul class="space-y-1.5 mb-5 flex-grow">
                        @if($offer->min_order_value > 0)
                            <li class="flex items-center gap-2 text-xs text-gray-500">
                                <svg class="w-3.5 h-3.5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Min spend: ₹{{ number_format($offer->min_order_value / 100, 0) }}
                            </li>
                        @endif
                        @if($offer->ends_at)
                            <li class="flex items-center gap-2 text-xs text-gray-500">
                                <svg class="w-3.5 h-3.5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Ends {{ $offer->ends_at->format('M j, Y') }}
                            </li>
                        @endif
                        <li class="flex items-center gap-2 text-xs text-gray-500">
                            <svg class="w-3.5 h-3.5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            {{ $offer->isSiteWide() ? 'Applies sitewide' : 'Selected products/categories' }}
                        </li>
                    </ul>

                    {{-- Scope pills --}}
                    @if(!$offer->isSiteWide())
                        <div class="flex flex-wrap gap-1.5 mb-4">
                            @foreach($offer->qualifiableCategories->take(3) as $cat)
                                <span class="text-[10px] font-medium bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $cat->name }}</span>
                            @endforeach
                            @foreach($offer->qualifiableProducts->take(2) as $prod)
                                <span class="text-[10px] font-medium bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $prod->name }}</span>
                            @endforeach
                        </div>
                    @endif

                    {{-- Coupon code button --}}
                    @if($offer->coupons->count() > 0)
                        @php $coupon = $offer->coupons->first(); @endphp
                        <button onclick="copyToClipboard('{{ $coupon->code }}')"
                            class="w-full flex items-center justify-between mb-3 bg-gray-50 hover:bg-indigo-50 border border-dashed border-gray-300 hover:border-indigo-300 rounded-lg px-4 py-2.5 transition-colors group">
                            <span class="font-mono text-sm font-bold text-gray-800 tracking-widest group-hover:text-indigo-700">{{ $coupon->code }}</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </button>
                    @endif

                    <a href="{{ route('user.offers.show', $offer) }}"
                        class="w-full text-center text-sm font-semibold text-gray-600 hover:text-indigo-600 py-2 border border-gray-200 hover:border-indigo-200 rounded-lg transition-colors">
                        View details →
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-24">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">No offers right now</h3>
                <p class="text-sm text-gray-400 mb-5">Check back soon for new promotions.</p>
                @if($filter !== 'all')
                    <a href="{{ route('user.offers.index') }}" class="text-sm font-medium text-indigo-600 hover:underline">View all offers</a>
                @endif
            </div>
        @endforelse
    </div>

    @if($offers->hasPages())
        <div class="mt-10">{{ $offers->withQueryString()->links() }}</div>
    @endif
</div>

@endsection
