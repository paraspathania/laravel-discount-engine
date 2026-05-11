<div {{ $attributes->merge(['class' => 'bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col group']) }}>
    
    @if(isset($image))
        <div class="w-full h-48 bg-gray-200 relative overflow-hidden">
            {{ $image }}
        </div>
    @endif
    
    <div class="p-5 flex-grow">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="px-5 py-4 bg-gray-50 border-t border-gray-100">
            {{ $footer }}
        </div>
    @endif
</div>
