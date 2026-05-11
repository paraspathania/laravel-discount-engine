<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DiscountStore') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 min-h-screen flex flex-col">
    
    <!-- Top Navigation -->
    <nav class="bg-white border-b border-gray-200 shadow-sm" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                <!-- Left: Logo -->
                <div class="flex items-center">
                    <a href="{{ route('user.products.index') }}" class="text-2xl font-extrabold text-indigo-600 tracking-tight flex items-center gap-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        DiscountStore
                    </a>
                </div>

                <!-- Middle: Desktop Links -->
                <div class="hidden sm:flex sm:items-center sm:space-x-8">
                    <a href="{{ route('user.products.index') }}" class="text-gray-600 hover:text-indigo-600 font-bold px-3 py-2 transition-colors">Products</a>
                    <a href="{{ route('user.offers.index') }}" class="text-gray-600 hover:text-indigo-600 font-bold px-3 py-2 transition-colors">Offers</a>
                </div>

                <!-- Right: Cart & Auth -->
                <div class="hidden sm:flex sm:items-center sm:space-x-6">
                    @php
                        $cartCount = collect(session()->get('cart', []))->sum('qty');
                    @endphp
                    <a href="{{ route('user.cart.index') }}" class="relative text-gray-600 hover:text-indigo-600 font-bold flex items-center transition-colors">
                        <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Cart
                        @if($cartCount > 0)
                            <span class="cart-count-badge absolute -top-2 -right-3 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow">{{ $cartCount }}</span>
                        @endif
                    </a>

                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="flex items-center text-gray-600 hover:text-indigo-600 font-bold focus:outline-none">
                                {{ Auth::user()->name }}
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-xl py-1 border border-gray-100 z-50" style="display: none;">
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-indigo-600">Admin Dashboard</a>
                                @endif
                                <a href="{{ route('user.orders.index') }}" class="block px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-indigo-600">My Orders</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-indigo-600">Log Out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 font-bold transition-colors">Log in</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white hover:bg-indigo-700 font-bold px-5 py-2 rounded-lg transition-colors shadow-sm">Register</a>
                    @endauth
                </div>

                <!-- Mobile Hamburger -->
                <div class="flex items-center sm:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display:none;"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" class="sm:hidden border-t border-gray-200" style="display: none;">
            <div class="pt-2 pb-3 space-y-1 bg-gray-50">
                <a href="{{ route('user.products.index') }}" class="block pl-3 pr-4 py-2 text-base font-bold text-gray-700 hover:bg-gray-100 hover:text-indigo-600">Products</a>
                <a href="{{ route('user.offers.index') }}" class="block pl-3 pr-4 py-2 text-base font-bold text-gray-700 hover:bg-gray-100 hover:text-indigo-600">Offers</a>
                <a href="{{ route('user.cart.index') }}" class="block pl-3 pr-4 py-2 text-base font-bold text-gray-700 hover:bg-gray-100 hover:text-indigo-600 flex items-center">
                    Cart
                    <span class="cart-count-badge ml-2 bg-indigo-100 text-indigo-800 py-0.5 px-2 rounded-full text-xs">{{ $cartCount ?? 0 }}</span>
                </a>
            </div>
            <div class="pt-4 pb-3 border-t border-gray-200 bg-white">
                @auth
                    <div class="px-4 mb-2">
                        <div class="text-base font-bold text-gray-800">{{ Auth::user()->name }}</div>
                    </div>
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-indigo-600">Admin Dashboard</a>
                    @endif
                    <a href="{{ route('user.orders.index') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-indigo-600">My Orders</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left pl-3 pr-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-indigo-600">Log Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-indigo-600">Log in</a>
                    <a href="{{ route('register') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-indigo-600">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Support old x-app-layout Header Slot -->
    @if (isset($header))
        <header class="bg-white shadow-sm border-b border-gray-100">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

    <!-- Global Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        @if(session('success'))
            <x-alert type="success" :message="session('success')" />
        @endif
        @if(session('error'))
            <x-alert type="error" :message="session('error')" />
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow">
        <!-- Support both Yields and Slots to prevent breaking earlier views -->
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
            <div class="text-gray-500 font-medium text-sm mb-4 md:mb-0">
                &copy; {{ date('Y') }} DiscountStore. All rights reserved.
            </div>
            <div class="flex space-x-6 text-sm font-bold text-gray-500">
                <a href="#" class="hover:text-indigo-600 transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-indigo-600 transition-colors">Terms of Service</a>
                <a href="#" class="hover:text-indigo-600 transition-colors">Support</a>
            </div>
        </div>
    </footer>

    <!-- Include AlpineJS for dynamic behaviors -->
    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
