@props(['color' => 'indigo', 'text'])

@php
    $classes = [
        'indigo' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
        'green'  => 'bg-green-100 text-green-800 border-green-200',
        'red'    => 'bg-red-100 text-red-800 border-red-200',
        'yellow' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
        'gray'   => 'bg-gray-100 text-gray-800 border-gray-200',
    ][$color] ?? 'bg-indigo-100 text-indigo-800 border-indigo-200';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full border text-xs font-bold uppercase tracking-wider shadow-sm $classes"]) }}>
    {{ $text }}
</span>
