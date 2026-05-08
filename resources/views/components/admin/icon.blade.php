@props(['name' => 'home'])

@php
    $icons = [
        'home' => 'M3 12l9-9 9 9M5 10v10h4v-6h6v6h4V10',
        'building' => 'M3 21h18M5 21V7l7-4 7 4v14M9 9h.01M9 13h.01M9 17h.01M15 9h.01M15 13h.01M15 17h.01',
        'tag' => 'M7 7h.01M7 3h5l9 9-9 9-9-9V3a4 4 0 014-4z',
        'document' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z',
        'document-text' => 'M9 12h6m-6 4h6m-6-8h6m2 13H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z',
        'pencil' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
        'users' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-5.13a4 4 0 11-8 0 4 4 0 018 0zM21 8a4 4 0 11-8 0 4 4 0 018 0z',
        'shield' => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z',
    ];
    $path = $icons[$name] ?? $icons['home'];
@endphp

<svg {{ $attributes->merge(['class' => 'w-5 h-5', 'fill' => 'none', 'stroke' => 'currentColor', 'viewBox' => '0 0 24 24']) }}>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"/>
</svg>
