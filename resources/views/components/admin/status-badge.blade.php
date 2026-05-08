@props(['active' => false, 'labels' => ['active' => 'Active', 'inactive' => 'Inactive']])

@if ($active)
    <span class="inline-flex items-center gap-1 rounded-full bg-green-100 text-green-700 text-xs font-medium px-2.5 py-0.5">
        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> {{ $labels['active'] }}
    </span>
@else
    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 text-slate-600 text-xs font-medium px-2.5 py-0.5">
        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> {{ $labels['inactive'] }}
    </span>
@endif
