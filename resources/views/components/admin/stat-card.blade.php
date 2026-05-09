@props(['title', 'value', 'icon' => 'home', 'color' => 'indigo'])

<div class="bg-white rounded-lg shadow-sm border border-slate-200 p-5 flex items-center gap-4">
    <div class="w-12 h-12 rounded-lg bg-{{ $color }}-100 text-{{ $color }}-600 flex items-center justify-center">
        <x-admin.icon :name="$icon" class="w-6 h-6" />
    </div>
    <div>
        <div class="text-sm text-slate-500">{{ $title }}</div>
        <div class="text-2xl font-semibold text-slate-900">{{ $value }}</div>
    </div>
</div>
