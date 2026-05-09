@props(['current' => 1])

@php
    $steps = [
        1 => 'Bank',
        2 => 'Template',
        3 => 'Form',
        4 => 'Preview',
    ];
@endphp

<ol class="flex items-center gap-2 text-xs sm:text-sm overflow-x-auto">
    @foreach ($steps as $num => $label)
        @php
            $state = $num < $current ? 'done' : ($num === $current ? 'active' : 'todo');
        @endphp
        <li class="flex items-center gap-2 shrink-0">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-semibold
                {{ $state === 'done' ? 'bg-green-500 text-white' : ($state === 'active' ? 'bg-indigo-600 text-white' : 'bg-slate-200 text-slate-500') }}">
                {{ $num }}
            </span>
            <span class="{{ $state === 'todo' ? 'text-slate-400' : 'text-slate-800 font-medium' }}">{{ $label }}</span>
            @if (! $loop->last)
                <span class="w-6 h-px bg-slate-300 mx-1"></span>
            @endif
        </li>
    @endforeach
</ol>
