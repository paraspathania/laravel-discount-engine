@extends('layouts.app')

@section('content')
    <div class="bg-indigo-900 py-12 border-b border-indigo-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight mb-4">Current Offers & Promotions</h1>
            <p class="text-lg text-indigo-200 font-medium max-w-2xl mx-auto">Discover the best deals, stackable discounts, and exclusive coupon codes all in one place.</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex space-x-8 overflow-x-auto py-4" aria-label="Tabs">
                <a href="{{ route('user.offers.index', ['filter' => 'all']) }}" class="{{ $filter === 'all' ? 'text-indigo-600 border-indigo-600 bg-indigo-50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} px-4 py-2 font-bold text-sm rounded-lg transition-colors whitespace-nowrap">
                    All Promotions
                </a>
                <a href="{{ route('user.offers.index', ['filter' => 'percentage']) }}" class="{{ $filter === 'percentage' ? 'text-indigo-600 border-indigo-600 bg-indigo-50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} px-4 py-2 font-bold text-sm rounded-lg transition-colors whitespace-nowrap">
                    Percentage Discounts
                </a>
                <a href="{{ route('user.offers.index', ['filter' => 'fixed']) }}" class="{{ $filter === 'fixed' ? 'text-indigo-600 border-indigo-600 bg-indigo-50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} px-4 py-2 font-bold text-sm rounded-lg transition-colors whitespace-nowrap">
                    Fixed Amount Off
                </a>
                <a href="{{ route('user.offers.index', ['filter' => 'coupon']) }}" class="{{ $filter === 'coupon' ? 'text-indigo-600 border-indigo-600 bg-indigo-50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} px-4 py-2 font-bold text-sm rounded-lg transition-colors whitespace-nowrap">
                    Requires Coupon
                </a>
            </nav>
        </div>
    </div>

    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($offers as $offer)
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full overflow-hidden relative group">
                        
                        <!-- Top Banner / Badges -->
                        <div class="p-6 pb-0 flex justify-between items-start mb-4">
                            <x-badge color="blue" text="{{ str_replace('_', ' ', $offer->type) }}" />
                            
                            @if($offer->is_stackable)
                                <x-badge color="green" text="Stackable" />
                            @else
                                <x-badge color="red" text="Not Stackable" />
                            @endif
                        </div>
                        
                        <!-- Core Info -->
                        <div class="px-6 flex-grow">
                            <a href="{{ route('user.offers.show', $offer) }}" class="block mb-4 hover:text-indigo-600 transition-colors">
                                <h3 class="text-2xl font-extrabold text-gray-900 leading-tight">{{ $offer->name }}</h3>
                            </a>
                            
                            <div class="mb-6 flex items-center">
                                @if($offer->type === 'percentage')
                                    <span class="text-4xl font-black text-green-600">{{ $offer->value / 100 }}% OFF</span>
                                @elseif($offer->type === 'fixed_amount')
                                    <span class="text-4xl font-black text-green-600">₹{{ number_format($offer->value / 100, 2) }} OFF</span>
                                @else
                                    <span class="text-4xl font-black text-green-600">SPECIAL DEAL</span>
                                @endif
                            </div>

                            <ul class="space-y-3 mb-6 text-sm text-gray-600 font-medium">
                                @if($offer->min_order_value > 0)
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-gray-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Min Spend: ₹{{ number_format($offer->min_order_value / 100, 2) }}
                                    </li>
                                @endif
                                
                                @if($offer->ends_at)
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-gray-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Expires {{ $offer->ends_at->diffForHumans() }} ({{ $offer->ends_at->format('M j') }})
                                    </li>
                                @endif

                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    @if($offer->isSiteWide())
                                        Valid Sitewide!
                                    @else
                                        Valid on specific categories/products
                                    @endif
                                </li>
                            </ul>

                            @if(!$offer->isSiteWide())
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($offer->qualifiableCategories->take(3) as $cat)
                                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded font-bold">{{ $cat->name }}</span>
                                    @endforeach
                                    @foreach($offer->qualifiableProducts->take(3) as $prod)
                                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded font-bold">{{ $prod->name }}</span>
                                    @endforeach
                                    @if($offer->qualifiableCategories->count() + $offer->qualifiableProducts->count() > 3)
                                        <span class="bg-gray-100 text-gray-500 text-xs px-2 py-1 rounded font-bold">...and more</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        <!-- Footer Action Area -->
                        <div class="p-6 bg-gray-50 border-t border-gray-100 flex flex-col gap-3">
                            @if($offer->coupons->count() > 0)
                                @php $activeCoupon = $offer->coupons->first(); @endphp
                                <div class="w-full relative group/coupon">
                                    <button onclick="copyToClipboard('{{ $activeCoupon->code }}')" class="w-full flex items-center justify-between bg-white border-2 border-dashed border-indigo-300 hover:border-indigo-500 hover:bg-indigo-50 text-indigo-900 font-mono font-bold py-3 px-4 rounded-lg transition-colors">
                                        <span class="tracking-widest">{{ $activeCoupon->code }}</span>
                                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    </button>
                                </div>
                            @endif
                            <a href="{{ route('user.offers.show', $offer) }}" class="w-full text-center bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 px-4 rounded-lg transition-colors">
                                Offer Details
                            </a>
                        </div>

                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-white rounded-2xl border border-gray-200 shadow-sm">
                        <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                        <h3 class="text-2xl font-bold text-gray-900">No active offers found.</h3>
                        <p class="text-gray-500 mt-2 font-medium">There are currently no promotions matching this filter.</p>
                        <a href="{{ route('user.offers.index') }}" class="mt-6 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">View All Offers</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $offers->links() }}
            </div>
        </div>
    </div>

    <!-- Global Copy To Clipboard Toast Container -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2"></div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                showToast("Copied code: " + text);
            }, function(err) {
                console.error('Async: Could not copy text: ', err);
            });
        }

        function showToast(message) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = 'bg-gray-900 text-white px-6 py-3 rounded-lg shadow-xl font-bold flex items-center opacity-0 transform translate-y-4 transition-all duration-300';
            toast.innerHTML = `
                <svg class="w-5 h-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                ${message}
            `;
            container.appendChild(toast);
            
            // Trigger animation in
            setTimeout(() => {
                toast.classList.remove('opacity-0', 'translate-y-4');
            }, 10);

            // Trigger animation out
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-4');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }
    </script>
@endsection
