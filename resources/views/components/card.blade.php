@props(['title' => null, 'icon' => null])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-sm border border-gray-200']) }}>
    @if($title)
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center">
            @if($icon)
            <div class="mr-3 text-gray-500">
                {!! $icon !!}
            </div>
            @endif
            <h3 class="text-lg font-semibold text-gray-800">{{ $title }}</h3>
        </div>
    </div>
    @endif

    <div class="p-6">
        {{ $slot }}
    </div>
</div>
