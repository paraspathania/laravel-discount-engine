@props(['type' => 'success', 'message'])

@php
    $classes = [
        'success' => 'bg-green-50 border-green-500 text-green-700',
        'error' => 'bg-red-50 border-red-500 text-red-700',
        'warning' => 'bg-yellow-50 border-yellow-500 text-yellow-700',
        'info' => 'bg-blue-50 border-blue-500 text-blue-700',
    ][$type] ?? 'bg-gray-50 border-gray-500 text-gray-700';

    $icons = [
        'success' => '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
        'error' => '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
        'info' => '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
    ];
@endphp

<div {{ $attributes->merge(['class' => "border-l-4 p-4 shadow-sm rounded-r-md flex items-center $classes"]) }} role="alert">
    {!! $icons[$type] ?? $icons['info'] !!}
    <p class="font-bold tracking-wide">{{ $message }}</p>
</div>
