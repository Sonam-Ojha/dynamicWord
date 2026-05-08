@props(['action', 'placeholder' => 'Search...'])

<form method="GET" action="{{ $action }}" class="flex gap-2 items-center">
    <div class="relative">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0a7 7 0 10-9.9 0 7 7 0 009.9 0z"/></svg>
        </span>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ $placeholder }}"
               class="pl-9 pr-3 py-2 text-sm rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-64">
    </div>
    {{ $slot ?? '' }}
    <button type="submit" class="inline-flex items-center px-3 py-2 rounded-md bg-slate-800 text-white text-sm hover:bg-slate-900">
        Search
    </button>
</form>
