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
<body class="font-sans text-gray-900 antialiased bg-gray-50 min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
    
    <!-- Logo -->
    <div class="mb-6">
        <a href="/">
            <div class="text-3xl font-extrabold text-indigo-600 tracking-tight flex items-center gap-2">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                DiscountStore
            </div>
        </a>
    </div>

    <!-- Centered Card -->
    <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-xl overflow-hidden sm:rounded-2xl border border-gray-100">
        <!-- Support both Yields and Slots -->
        @yield('content')
        {{ $slot ?? '' }}
    </div>

</body>
</html>
