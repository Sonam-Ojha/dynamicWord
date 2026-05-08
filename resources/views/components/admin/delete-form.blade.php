@props(['action', 'confirm' => 'Are you sure you want to delete this record?'])

<form method="POST" action="{{ $action }}" class="inline" onsubmit="return confirm('{{ $confirm }}');">
    @csrf
    @method('DELETE')
    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
</form>
