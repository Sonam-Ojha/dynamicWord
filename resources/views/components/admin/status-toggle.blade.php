@props(['action', 'active' => false])

<form method="POST" action="{{ $action }}" class="inline-block">
    @csrf
    @method('PATCH')
    <button type="submit" class="inline-flex items-center" title="Toggle status">
        <span class="relative inline-flex h-5 w-9 items-center rounded-full transition {{ $active ? 'bg-green-500' : 'bg-slate-300' }}">
            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition {{ $active ? 'translate-x-4' : 'translate-x-1' }}"></span>
        </span>
    </button>
</form>
