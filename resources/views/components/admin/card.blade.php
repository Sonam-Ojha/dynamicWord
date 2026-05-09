@props(['title' => null, 'actions' => null])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-sm border border-slate-200']) }}>
    @if ($title || $actions)
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
            @if ($title)
                <h3 class="text-base font-semibold text-slate-800">{{ $title }}</h3>
            @endif
            @if ($actions)
                <div>{{ $actions }}</div>
            @endif
        </div>
    @endif
    <div class="p-5">
        {{ $slot }}
    </div>
</div>
