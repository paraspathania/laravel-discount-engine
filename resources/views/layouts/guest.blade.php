<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SavvyCart') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased min-h-screen bg-white">
    <div class="flex min-h-screen">
        <!-- Left Side: Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center px-6 py-12 sm:px-12 relative z-10">
            <div class="w-full max-w-sm">
                <!-- Logo -->
                <div class="mb-10">
                    <a href="/" class="flex items-center gap-3 group w-max">
                        <div class="w-10 h-10 bg-gray-900 rounded-xl flex items-center justify-center group-hover:bg-indigo-600 transition-colors duration-200 shadow-md">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 tracking-tight">{{ config('app.name', 'SavvyCart') }}</span>
                    </a>
                </div>

                <!-- Content -->
                @yield('content')
                {{ $slot ?? '' }}
                
            </div>
        </div>

        <!-- Right Side: Graphic/Image -->
        <div class="hidden lg:flex w-1/2 bg-gray-50 relative overflow-hidden items-center justify-center">
            <!-- Decorative background elements -->
            <div class="absolute inset-0 bg-indigo-50/50"></div>
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-indigo-200 rounded-full blur-[100px] opacity-60"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-purple-200 rounded-full blur-[100px] opacity-60"></div>
            
            <div class="relative z-10 p-12 max-w-lg text-center">
                <h2 class="font-serif text-4xl text-gray-900 leading-tight mb-4">Never overpay again.</h2>
                <p class="text-lg text-gray-600">Join thousands of smart shoppers using {{ config('app.name', 'SavvyCart') }} to unlock exclusive deals and automatic coupon stacking.</p>
                <div class="mt-8 flex justify-center">
                    <!-- Stylized mockup graphic -->
                    <div class="w-64 bg-white/60 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/50 flex flex-col p-6 items-start gap-4 transform rotate-[-2deg]">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                        </div>
                        <div class="w-full h-4 bg-gray-200/80 rounded-full"></div>
                        <div class="w-3/4 h-4 bg-gray-200/80 rounded-full"></div>
                        <div class="w-1/2 h-4 bg-gray-200/80 rounded-full"></div>
                        <div class="w-full mt-2 pt-4 border-t border-gray-200/50 flex justify-between items-center">
                            <div class="text-xl font-bold text-gray-900">₹999</div>
                            <div class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">-20%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
