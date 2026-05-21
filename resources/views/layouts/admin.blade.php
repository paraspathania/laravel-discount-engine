<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Portal</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Base typography overrides */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #fafbfc;
        }
        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.02em;
        }

        /* Premium custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(99, 102, 241, 0.15);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(99, 102, 241, 0.35);
        }

        /* Glassmorphic card styling utilities */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        /* Sidebar glowing effect */
        .sidebar-glow {
            box-shadow: inset -1px 0 0 0 rgba(255, 255, 255, 0.05);
        }

        /* Micro-interactions */
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px -8px rgba(99, 102, 241, 0.15);
        }

        .active-nav-indicator {
            position: relative;
        }
        .active-nav-indicator::after {
            content: '';
            position: absolute;
            left: 0;
            top: 25%;
            height: 50%;
            width: 4px;
            background: #6366f1;
            border-radius: 0 4px 4px 0;
        }

        /* Animate slide up for views */
        .animate-fade-in-up {
            animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="font-sans antialiased bg-[#f8fafc] flex h-screen overflow-hidden text-slate-800">

    <!-- Fixed Left Sidebar: Obsidian Dark -->
    <aside class="w-64 bg-slate-950 text-slate-200 flex flex-col h-full sidebar-glow z-20 shrink-0 relative overflow-hidden">
        <!-- Background subtle purple glow -->
        <div class="absolute -top-40 -left-40 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-40 -right-40 w-80 h-80 bg-purple-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Logo -->
        <div class="h-20 flex items-center px-6 border-b border-slate-900/60 bg-slate-950/80 backdrop-blur-md relative z-10">
            <div class="w-9 h-9 rounded-xl bg-indigo-500 bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30 mr-3" style="background: linear-gradient(45deg, #6366f1 0%, #9333ea 100%);">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <span class="text-lg font-extrabold tracking-wider text-white uppercase font-heading">Admin<span class="text-indigo-400 font-black">Pro</span></span>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-8 space-y-1.5 overflow-y-auto relative z-10">
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all font-bold text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/35 active-nav-indicator' : 'text-slate-400 hover:bg-slate-900/70 hover:text-slate-100 hover:translate-x-0.5' }}">
                <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.discounts.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all font-bold text-sm {{ request()->routeIs('admin.discounts.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/35 active-nav-indicator' : 'text-slate-400 hover:bg-slate-900/70 hover:text-slate-100 hover:translate-x-0.5' }}">
                <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('admin.discounts.*') ? 'text-white' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                Discounts
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all font-bold text-sm {{ request()->routeIs('admin.coupons.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/35 active-nav-indicator' : 'text-slate-400 hover:bg-slate-900/70 hover:text-slate-100 hover:translate-x-0.5' }}">
                <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('admin.coupons.*') ? 'text-white' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                Coupons
            </a>
            <a href="{{ route('admin.products.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all font-bold text-sm {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/35 active-nav-indicator' : 'text-slate-400 hover:bg-slate-900/70 hover:text-slate-100 hover:translate-x-0.5' }}">
                <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('admin.products.*') ? 'text-white' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Products
            </a>
            <a href="{{ route('admin.orders.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all font-bold text-sm {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/35 active-nav-indicator' : 'text-slate-400 hover:bg-slate-900/70 hover:text-slate-100 hover:translate-x-0.5' }}">
                <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('admin.orders.*') ? 'text-white' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                Orders
            </a>
            <a href="{{ route('admin.analytics.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all font-bold text-sm {{ request()->routeIs('admin.analytics.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/35 active-nav-indicator' : 'text-slate-400 hover:bg-slate-900/70 hover:text-slate-100 hover:translate-x-0.5' }}">
                <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 {{ request()->routeIs('admin.analytics.*') ? 'text-white' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Analytics
            </a>
        </nav>

        <!-- User Section / Logout -->
        <div class="p-4 border-t border-slate-900/80 bg-slate-950 relative z-10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="group flex items-center w-full px-4 py-2.5 text-slate-400 font-bold text-sm hover:text-slate-100 hover:bg-slate-900/75 rounded-xl transition-colors">
                    <svg class="w-5 h-5 mr-3 text-slate-500 group-hover:text-slate-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Sign Out
                </button>
            </form>
            <a href="{{ route('home') }}" class="mt-2 flex items-center justify-center w-full px-4 py-2 text-[10px] font-extrabold text-slate-500 hover:text-indigo-400 transition-colors uppercase tracking-widest">
                Return to Store &rarr;
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col h-full overflow-hidden relative">
        @php
            $lowStockProducts = \App\Models\Product::where('stock', '<=', 10)->take(3)->get();
            $recentOrders = \App\Models\Order::latest()->take(3)->get();
            $recentUsages = \App\Models\DiscountUsage::with(['discount', 'order', 'user'])->latest()->take(3)->get();

            $notifications = collect();

            foreach ($lowStockProducts as $p) {
                $notifications->push([
                    'type' => 'warning',
                    'title' => 'Low Stock Alert',
                    'body' => "{$p->name} (SKU: {$p->sku}) has only {$p->stock} items remaining.",
                    'time' => 'Inventory',
                    'link' => route('admin.products.edit', $p),
                ]);
            }

            foreach ($recentOrders as $o) {
                $name = $o->user->name ?? 'Guest';
                $total = number_format($o->grand_total / 100, 2);
                $notifications->push([
                    'type' => 'success',
                    'title' => 'New Order Placed',
                    'body' => "Order #{$o->id} of ₹{$total} by {$name}.",
                    'time' => $o->created_at->diffForHumans(),
                    'link' => route('admin.orders.show', $o),
                ]);
            }

            foreach ($recentUsages as $u) {
                $uName = $u->user->name ?? 'Guest';
                $dName = $u->discount->name ?? 'Discount';
                $saved = number_format($u->saved_amount / 100, 2);
                $notifications->push([
                    'type' => 'info',
                    'title' => 'Discount Redeemed',
                    'body' => "{$uName} saved ₹{$saved} using '{$dName}' on Order #{$u->order_id}.",
                    'time' => $u->created_at->diffForHumans(),
                    'link' => route('admin.analytics.index'),
                ]);
            }
        @endphp
        
        <!-- Top Bar -->
        <header class="h-20 bg-white/70 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-8 z-10 shrink-0">
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight font-heading">
                @yield('header', 'Dashboard')
            </h2>
            
            <div class="flex items-center gap-6">
                <!-- Notifications Bell -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="text-slate-400 hover:text-indigo-600 transition-colors relative p-1.5 rounded-xl hover:bg-slate-50 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        @if($notifications->count() > 0)
                            <span class="absolute top-1.5 right-1.5 block h-2.5 w-2.5 rounded-full bg-indigo-600 ring-2 ring-white"></span>
                        @endif
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         @click.outside="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 transform -translate-y-2"
                         class="absolute right-0 mt-3 w-80 sm:w-96 bg-white/95 backdrop-blur-md rounded-2xl border border-slate-100 shadow-2xl z-50 overflow-hidden"
                         style="display: none;">
                        
                        <!-- Header -->
                        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                            <span class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Notifications</span>
                            @if($notifications->count() > 0)
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-indigo-50 text-indigo-600 rounded-full">
                                    {{ $notifications->count() }} active
                                </span>
                            @endif
                        </div>
                        
                        <!-- List -->
                        <div class="max-h-80 overflow-y-auto divide-y divide-slate-100">
                            @forelse($notifications as $notif)
                                <a href="{{ $notif['link'] }}" class="flex p-4 hover:bg-slate-50/70 transition-colors group">
                                    <!-- Icon Wrapper -->
                                    <div class="mr-3 mt-0.5 shrink-0">
                                        @if($notif['type'] === 'warning')
                                            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100/30">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            </div>
                                        @elseif($notif['type'] === 'success')
                                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100/30">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100/30">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-xs font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $notif['title'] }}</p>
                                            <span class="text-[9px] font-bold text-slate-405 shrink-0 ml-2">{{ $notif['time'] }}</span>
                                        </div>
                                        <p class="text-xs font-medium text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                            {{ $notif['body'] }}
                                        </p>
                                    </div>
                                </a>
                            @empty
                                <div class="px-5 py-8 text-center">
                                    <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center mx-auto mb-2.5 border border-slate-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                    </div>
                                    <p class="text-xs font-bold text-slate-700">All caught up!</p>
                                    <p class="text-[10px] text-slate-405 font-semibold mt-0.5">No administrative alerts at this time.</p>
                                </div>
                            @endforelse
                        </div>
                        
                        <!-- Footer -->
                        @if($notifications->count() > 0)
                            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50 text-center">
                                <button @click="open = false" class="text-[10px] font-extrabold text-slate-500 hover:text-indigo-600 transition-colors uppercase tracking-wider">
                                    Dismiss Panel
                                </button>
                            </div>
                        @endif
                        
                    </div>
                </div>
                
                <!-- Admin Profile -->
                <div class="flex items-center border-l border-slate-100 pl-6">
                    <div class="relative group cursor-pointer">
                        <img class="h-10 w-10 rounded-xl object-cover shadow-sm border border-slate-100 group-hover:border-indigo-500/30 transition-all duration-300" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=E0E7FF&color=4338CA" alt="Admin Avatar">
                        <span class="absolute -bottom-0.5 -right-0.5 block h-2.5 w-2.5 rounded-full bg-green-500 ring-2 ring-white"></span>
                    </div>
                    <div class="ml-3 hidden sm:block">
                        <p class="text-sm font-extrabold text-slate-800 leading-tight">{{ auth()->user()->name ?? 'Administrator' }}</p>
                        <p class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase tracking-wider">Super Admin</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success') || session('error'))
            <div class="absolute top-24 right-8 z-50">
                @if(session('success'))
                    <div class="bg-indigo-600 text-white px-5 py-3.5 rounded-xl shadow-xl font-bold text-sm flex items-center mb-2 animate-fade-in-up">
                        <svg class="w-5 h-5 mr-2.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-rose-600 text-white px-5 py-3.5 rounded-xl shadow-xl font-bold text-sm flex items-center animate-fade-in-up">
                        <svg class="w-5 h-5 mr-2.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        @endif

        <!-- Scrollable Page Content -->
        <main class="flex-1 overflow-y-auto bg-slate-50/50 p-8">
            <div class="max-w-7xl mx-auto animate-fade-in-up">
                @yield('content')
            </div>
        </main>
        
    </div>

    @stack('scripts')
</body>
</html>
