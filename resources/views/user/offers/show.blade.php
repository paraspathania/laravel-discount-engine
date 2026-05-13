@extends('layouts.app')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <a href="{{ route('user.offers.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 mb-8 transition-colors">
                <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Promotions
            </a>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 relative">
                
                <!-- Header Banner -->
                <div class="bg-indigo-900 py-10 px-8 md:px-12 relative overflow-hidden">
                    <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                    <div class="relative z-10 flex flex-col md:flex-row md:justify-between md:items-center gap-6">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <x-badge color="blue" text="{{ str_replace('_', ' ', $offer->type) }}" />
                                @if($offer->is_stackable)
                                    <x-badge color="green" text="Stackable" />
                                @endif
                            </div>
                            <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight mb-2">{{ $offer->name }}</h1>
                            <p class="text-indigo-200 text-lg font-medium">
                                @if($offer->type === 'percentage')
                                    Get a massive {{ $offer->value / 100 }}% discount!
                                @elseif($offer->type === 'fixed_amount')
                                    Save exactly ₹{{ number_format($offer->value / 100, 2) }} on your order!
                                @else
                                    Special deal rules apply.
                                @endif
                            </p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-white border border-white/20 shrink-0 text-center">
                            <span class="block text-sm font-bold uppercase tracking-wider text-indigo-200 mb-1">Time Remaining</span>
                            @if($offer->ends_at)
                                <span class="text-2xl font-black">{{ $offer->ends_at->diffForHumans(['parts' => 2]) }}</span>
                            @else
                                <span class="text-xl font-black">No Expiry</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-8 md:p-12">
                    
                    @if($offer->coupons->count() > 0)
                        <div class="mb-12 bg-indigo-50 rounded-xl p-8 border-2 border-dashed border-indigo-200 flex flex-col sm:flex-row items-center justify-between gap-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1">Use this exclusive code</h3>
                                <p class="text-gray-600 font-medium text-sm">Copy and paste this code at checkout to claim your discount.</p>
                            </div>
                            @php $activeCoupon = $offer->coupons->first(); @endphp
                            <button onclick="copyToClipboard('{{ $activeCoupon->code }}')" class="shrink-0 flex items-center bg-white border border-indigo-300 hover:border-indigo-500 hover:bg-indigo-50 text-indigo-900 font-mono font-black text-xl py-3 px-6 rounded-xl transition-all shadow-sm">
                                <span class="tracking-widest mr-4">{{ $activeCoupon->code }}</span>
                                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </button>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-12">
                        
                        <!-- Left Column: Terms -->
                        <div>
                            <h3 class="text-xl font-extrabold text-gray-900 mb-4 border-b border-gray-200 pb-2">Terms & Conditions</h3>
                            <ul class="space-y-4 text-gray-600 font-medium">
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-green-500 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @if($offer->min_order_value > 0)
                                        Requires a minimum order value of ₹{{ number_format($offer->min_order_value / 100, 2) }}.
                                    @else
                                        No minimum spend required.
                                    @endif
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-green-500 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @if($offer->is_stackable)
                                        Can be combined with other stackable discounts in your cart.
                                    @else
                                        Cannot be combined with other offers.
                                    @endif
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-green-500 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Valid from {{ $offer->starts_at->format('F j, Y') }} 
                                    @if($offer->ends_at)
                                        until {{ $offer->ends_at->format('F j, Y \a\t g:i A') }}.
                                    @else
                                        until further notice.
                                    @endif
                                </li>
                            </ul>
                        </div>

                        <!-- Right Column: Eligibility -->
                        <div>
                            <h3 class="text-xl font-extrabold text-gray-900 mb-4 border-b border-gray-200 pb-2">Eligibility</h3>
                            @if($offer->isSiteWide())
                                <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                                    <svg class="mx-auto w-10 h-10 text-green-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path></svg>
                                    <h4 class="font-bold text-green-900 text-lg">Valid Sitewide</h4>
                                    <p class="text-green-700 text-sm mt-1">This offer applies to all products in the store!</p>
                                </div>
                            @else
                                @if($offer->qualifiableCategories->count() > 0)
                                    <div class="mb-6">
                                        <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-2">Valid Categories</h4>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($offer->qualifiableCategories as $cat)
                                                <span class="bg-gray-100 text-gray-700 font-bold px-3 py-1.5 rounded-lg border border-gray-200">{{ $cat->name }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if($offer->qualifiableProducts->count() > 0)
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-2">Valid Products</h4>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($offer->qualifiableProducts as $prod)
                                                <a href="{{ route('user.products.show', $prod) }}" class="bg-gray-100 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 text-gray-700 font-bold px-3 py-1.5 rounded-lg border border-gray-200 transition-colors">
                                                    {{ $prod->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                        
                    </div>

                    <div class="border-t border-gray-200 pt-8 text-center">
                        <a href="{{ route('user.products.index') }}" class="inline-flex items-center justify-center bg-gray-900 hover:bg-gray-800 text-white font-extrabold text-lg py-4 px-12 rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-1">
                            Shop Eligible Items Now
                            <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>

                </div>
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
