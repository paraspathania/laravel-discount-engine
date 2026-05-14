<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Bazaar') }} — Deals That Matter</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }

        /* ── Navbar glass effect on scroll ── */
        .navbar-glass {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(0,0,0,0.06);
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }
        .navbar-glass.scrolled {
            background: rgba(255,255,255,0.95);
            box-shadow: 0 1px 24px rgba(0,0,0,0.07);
        }

        /* ── Active pill nav ── */
        .nav-pill {
            position: relative;
            font-size: 0.8125rem;
            font-weight: 500;
            color: #6b7280;
            padding: 6px 14px;
            border-radius: 8px;
            transition: color 0.2s, background 0.2s;
        }
        .nav-pill:hover { color: #111827; background: #f3f4f6; }
        .nav-pill.active { color: #4f46e5; background: #eef2ff; font-weight: 600; }

        /* ── Logo hover glow ── */
        .logo-glow:hover .logo-icon {
            box-shadow: 0 0 0 6px rgba(79,70,229,0.12);
        }
        .logo-icon { transition: box-shadow 0.25s ease, background 0.25s ease; }

        /* ── Cart button ── */
        .cart-btn {
            position: relative;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #374151;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            transition: all 0.2s ease;
        }
        .cart-btn:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
            color: #111827;
            transform: translateY(-1px);
        }
        .cart-btn.has-items {
            color: #4f46e5;
            background: #eef2ff;
            border-color: #c7d2fe;
        }
        .cart-btn.has-items:hover { background: #e0e7ff; }

        /* ── Avatar ring ── */
        .avatar-ring {
            outline: 2px solid transparent;
            outline-offset: 2px;
            transition: outline-color 0.2s;
        }
        .avatar-ring:hover { outline-color: #c7d2fe; }

        /* ── Dropdown animation ── */
        [x-cloak] { display: none !important; }
        .dropdown-enter { animation: dropIn 0.15s ease forwards; }
        @keyframes dropIn {
            from { opacity: 0; transform: translateY(-6px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0)  scale(1); }
        }

        /* ── Cart shake ── */
        @keyframes cartShake {
            0%,100% { transform: rotate(0); }
            25%      { transform: rotate(-10deg); }
            75%      { transform: rotate(10deg); }
        }
        .cart-shake { animation: cartShake 0.35s ease; }

        /* ── Toast ── */
        @keyframes slideUp {
            from { transform: translateY(16px); opacity: 0; }
            to   { transform: translateY(0); opacity: 1; }
        }
        .toast-in { animation: slideUp 0.25s ease forwards; }

        /* ── Page fade ── */
        .page-fade { animation: fadeIn 0.25s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        /* ── Announcement bar shimmer ── */
        @keyframes shimmer {
            0%   { background-position: -400px 0; }
            100% { background-position: 400px 0; }
        }
    </style>
</head>
<body class="bg-[#f8f7f4] text-gray-900 min-h-screen flex flex-col antialiased">

    <!-- Top announcement bar -->
    <div class="bg-gray-900 text-gray-200 text-center text-xs py-2 px-4 font-medium tracking-wide">
        Free shipping on orders above ₹999 &nbsp;·&nbsp; Use code <span class="font-bold text-white font-mono">WELCOME10</span> for 10% off your first order
    </div>

    <!-- Main Navigation -->
    <header class="navbar-glass sticky top-0 z-40 transition-all duration-300" x-data="{ mobileOpen: false, scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 10)" :class="{'scrolled': scrolled}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group logo-glow">
                    <div class="w-8 h-8 bg-gray-900 rounded-lg flex items-center justify-center logo-icon">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900 tracking-tight">Bazaar</span>
                </a>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex items-center gap-2">
                    <a href="{{ route('user.products.index') }}" class="nav-pill {{ request()->routeIs('user.products.*') ? 'active' : '' }}">Shop</a>
                    <a href="{{ route('user.offers.index') }}" class="nav-pill {{ request()->routeIs('user.offers.*') ? 'active' : '' }}">Offers</a>
                </nav>

                <!-- Right: Cart + Auth -->
                <div class="flex items-center gap-4">
                    @php $cartCount = collect(session()->get('cart', []))->sum('qty'); @endphp

                    <!-- Cart -->
                    <a href="{{ route('user.cart.index') }}" id="cart-nav-btn" class="cart-btn {{ $cartCount > 0 ? 'has-items' : '' }}">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span class="hidden sm:inline">Cart</span>
                        @if($cartCount > 0)
                            <span class="cart-count-badge bg-indigo-600 text-white text-[10px] font-bold w-4.5 h-4.5 rounded-full flex items-center justify-center leading-none">{{ $cartCount }}</span>
                        @else
                            <span class="cart-count-badge bg-indigo-600 text-white text-[10px] font-bold w-4.5 h-4.5 rounded-full items-center justify-center leading-none hidden">0</span>
                        @endif
                    </a>

                    <!-- Auth -->
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 flex items-center justify-center text-indigo-700 text-sm font-bold shadow-sm avatar-ring">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            </button>
                            <div x-show="open" x-transition:enter="dropdown-enter" x-cloak class="absolute right-0 mt-3 w-56 bg-white border border-gray-100 rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] py-2 z-50">
                                <div class="px-5 py-3 border-b border-gray-50 mb-1">
                                    <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate mt-0.5">{{ Auth::user()->email }}</p>
                                </div>
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                        Admin Panel
                                    </a>
                                @endif
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                    Dashboard
                                </a>
                                <a href="{{ route('user.orders.index') }}" class="flex items-center gap-3 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    My Orders
                                </a>
                                <div class="border-t border-gray-50 mt-1 pt-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-3 w-full px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-red-50 hover:text-red-600 transition-colors text-left">
                                            <svg class="w-4 h-4 text-gray-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Sign out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="hidden sm:flex items-center gap-3">
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors px-3 py-2">Sign in</a>
                            <a href="{{ route('register') }}" class="text-sm font-bold bg-gray-900 hover:bg-indigo-600 text-white px-4 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5">Get started</a>
                        </div>
                    @endauth

                    <!-- Mobile hamburger -->
                    <button @click="mobileOpen = !mobileOpen" class="md:hidden p-1.5 rounded-md text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                        <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg x-show="mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileOpen" class="md:hidden border-t border-gray-100 bg-white shadow-xl" style="display:none;">
            <div class="px-4 py-3 space-y-1">
                <a href="{{ route('user.products.index') }}" class="block py-2.5 px-3 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-indigo-600">Shop</a>
                <a href="{{ route('user.offers.index') }}" class="block py-2.5 px-3 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-indigo-600">Offers</a>
                <a href="{{ route('user.cart.index') }}" class="block py-2.5 px-3 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-indigo-600 flex justify-between items-center">
                    Cart
                    <span class="bg-gray-100 text-gray-600 text-xs py-0.5 px-2 rounded-full font-bold">{{ $cartCount }}</span>
                </a>
                @auth
                    <div class="h-px bg-gray-100 my-2"></div>
                    <a href="{{ route('dashboard') }}" class="block py-2.5 px-3 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-indigo-600">Dashboard</a>
                    <a href="{{ route('user.orders.index') }}" class="block py-2.5 px-3 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-indigo-600">My Orders</a>
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-3 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-indigo-600">Admin Panel</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="mt-1">
                        @csrf
                        <button type="submit" class="w-full text-left py-2.5 px-3 rounded-lg text-sm font-semibold text-red-500 hover:bg-red-50 hover:text-red-600">Sign out</button>
                    </form>
                @else
                    <div class="pt-3 pb-2 flex flex-col gap-2 px-3">
                        <a href="{{ route('login') }}" class="block text-center py-2 text-sm font-semibold text-gray-700 border border-gray-200 rounded-lg">Sign in</a>
                        <a href="{{ route('register') }}" class="block text-center py-2 text-sm font-bold bg-gray-900 text-white rounded-lg">Register</a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Flash messages -->
    @if(session('success') || session('error'))
        <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 pt-4">
            @if(session('success'))
                <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm font-medium">
                    <svg class="w-4 h-4 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm font-medium">
                    <svg class="w-4 h-4 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('error') }}
                </div>
            @endif
        </div>
    @endif

    <!-- Content -->
    <main class="flex-grow page-fade">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-7 h-7 bg-white rounded-md flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-900" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <span class="text-white font-bold">Bazaar</span>
                    </div>
                    <p class="text-sm leading-relaxed">A smarter way to shop. Discover verified deals, exclusive coupons, and offers curated just for you.</p>
                </div>
                <div>
                    <h4 class="text-white text-sm font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('user.products.index') }}" class="hover:text-white transition-colors">Shop All</a></li>
                        <li><a href="{{ route('user.offers.index') }}" class="hover:text-white transition-colors">Offers & Deals</a></li>
                        <li><a href="{{ route('user.cart.index') }}" class="hover:text-white transition-colors">Shopping Cart</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white text-sm font-semibold mb-4">Account</h4>
                    <ul class="space-y-2 text-sm">
                        @auth
                            <li><a href="{{ route('user.orders.index') }}" class="hover:text-white transition-colors">My Orders</a></li>
                            <li><a href="{{ route('profile.edit') }}" class="hover:text-white transition-colors">Profile</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Sign In</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Create Account</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-6 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs">
                <span>© {{ date('Y') }} Bazaar. All rights reserved.</span>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-white transition-colors">Privacy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms</a>
                    <a href="#" class="hover:text-white transition-colors">Support</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Toast container -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 pointer-events-none"></div>

    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const t = document.createElement('div');
            const colors = type === 'success' ? 'bg-gray-900 text-white' : 'bg-red-600 text-white';
            t.className = `${colors} px-5 py-3 rounded-xl shadow-2xl text-sm font-medium flex items-center gap-3 toast-in pointer-events-auto`;
            const icon = type === 'success'
                ? `<svg class="w-4 h-4 text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>`
                : `<svg class="w-4 h-4 text-red-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>`;
            t.innerHTML = icon + message;
            container.appendChild(t);
            setTimeout(() => { t.style.opacity = '0'; t.style.transform = 'translateY(8px)'; t.style.transition = 'all 0.3s ease'; setTimeout(() => t.remove(), 300); }, 3000);
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => showToast('Code <strong>' + text + '</strong> copied!'));
        }
    </script>
</body>
</html>
