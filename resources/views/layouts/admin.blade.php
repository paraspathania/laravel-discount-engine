<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50 flex h-screen overflow-hidden text-gray-900">

    <!-- Fixed Left Sidebar -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col h-full shadow-2xl z-20 shrink-0">
        <!-- Logo -->
        <div class="h-20 flex items-center px-6 border-b border-slate-800 bg-slate-950">
            <svg class="w-8 h-8 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            <span class="text-xl font-black tracking-wider text-white uppercase">Admin<span class="text-indigo-500">Pro</span></span>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all font-bold {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.discounts.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all font-bold {{ request()->routeIs('admin.discounts.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                Discounts
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all font-bold {{ request()->routeIs('admin.coupons.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                Coupons
            </a>
            <a href="#" class="flex items-center px-4 py-3 rounded-xl transition-all font-bold text-slate-400 hover:bg-slate-800 hover:text-white">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Products
            </a>
            <a href="#" class="flex items-center px-4 py-3 rounded-xl transition-all font-bold text-slate-400 hover:bg-slate-800 hover:text-white">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                Orders
            </a>
            <a href="{{ route('admin.analytics.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all font-bold {{ request()->routeIs('admin.analytics.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Analytics
            </a>
        </nav>

        <!-- User Section / Logout -->
        <div class="p-4 border-t border-slate-800 bg-slate-950">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full px-4 py-3 text-slate-400 font-bold hover:text-white hover:bg-slate-800 rounded-xl transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Sign Out
                </button>
            </form>
            <a href="{{ route('home') }}" class="mt-2 flex items-center justify-center w-full px-4 py-2 text-xs font-bold text-slate-500 hover:text-indigo-400 transition-colors uppercase tracking-widest">
                Return to Store &rarr;
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col h-full overflow-hidden relative">
        
        <!-- Top Bar -->
        <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-8 z-10 shrink-0">
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">
                @yield('header', 'Dashboard')
            </h2>
            
            <div class="flex items-center gap-6">
                <!-- Notifications Bell -->
                <button class="text-gray-400 hover:text-indigo-600 transition-colors relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-red-500 border-2 border-white"></span>
                </button>
                
                <!-- Admin Profile -->
                <div class="flex items-center border-l border-gray-200 pl-6">
                    <img class="h-10 w-10 rounded-full object-cover shadow-sm border border-gray-200" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=E0E7FF&color=4338CA" alt="Admin Avatar">
                    <div class="ml-3">
                        <p class="text-sm font-bold text-gray-900">{{ auth()->user()->name ?? 'Administrator' }}</p>
                        <p class="text-xs font-medium text-gray-500">Super Admin</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success') || session('error'))
            <div class="absolute top-24 right-8 z-50">
                @if(session('success'))
                    <div class="bg-green-600 text-white px-6 py-4 rounded-xl shadow-2xl font-bold flex items-center mb-2 animate-bounce-short">
                        <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-600 text-white px-6 py-4 rounded-xl shadow-2xl font-bold flex items-center animate-bounce-short">
                        <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        @endif

        <!-- Scrollable Page Content -->
        <main class="flex-1 overflow-y-auto bg-gray-50 p-8">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
        
    </div>

    <style>
        .animate-bounce-short {
            animation: slide-up 0.3s ease-out forwards;
        }
        @keyframes slide-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</body>
</html>
