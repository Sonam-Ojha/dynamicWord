@props(['document', 'size' => 'md', 'label' => 'Download'])

@php
    $printUrl = route('generate.print', $document);
    $base = route('generate.download', $document);
    $sizeClasses = match ($size) {
        'sm' => 'text-xs px-2.5 py-1',
        default => 'text-sm px-3 py-2',
    };
@endphp

<div x-data="{ open: false }" class="relative inline-block" @click.outside="open = false">
    <button type="button" @click="open = !open"
            class="inline-flex items-center gap-1 rounded-md bg-emerald-600 text-white font-medium hover:bg-emerald-700 {{ $sizeClasses }}">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/>
        </svg>
        <span>{{ $label }}</span>
        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div x-show="open" x-cloak x-transition.opacity
         class="absolute right-0 mt-1 w-44 rounded-md bg-white shadow-lg border border-slate-200 py-1 z-30 text-sm">

        <a href="{{ $base }}?format=word" download
           class="flex items-center gap-3 px-4 py-2 text-slate-700 hover:bg-slate-50">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded bg-blue-100 text-blue-700 text-xs font-bold">W</span>
            <div class="font-medium">Word</div>
        </a>

        <a href="{{ $printUrl }}?save=1" target="_blank"
           class="flex items-center gap-3 px-4 py-2 text-slate-700 hover:bg-slate-50">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded bg-red-100 text-red-700 text-xs font-bold">P</span>
            <div class="font-medium">PDF</div>
        </a>

        <a href="{{ $printUrl }}" target="_blank"
           class="flex items-center gap-3 px-4 py-2 text-slate-700 hover:bg-slate-50">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded bg-slate-100 text-slate-700 text-xs font-bold">🖨</span>
            <div class="font-medium">Print</div>
        </a>
    </div>
</div>
