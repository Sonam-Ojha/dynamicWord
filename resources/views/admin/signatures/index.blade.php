@extends('admin.layouts.app')

@section('title', 'Signatures')
@section('page_title', 'Signatures')

@section('page_actions')
    <x-admin.button :href="route('admin.signatures.create')">+ New Signature</x-admin.button>
@endsection

@section('content')
    <x-admin.card>
        <div class="mb-4">
            <x-admin.search-bar :action="route('admin.signatures.index')" placeholder="Search signatures..." />
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Preview</th>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">User</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($signatures as $sig)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3">
                                @if ($sig->signature_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($sig->signature_path))
                                    <img src="{{ asset('storage/'.$sig->signature_path) }}" class="h-10" alt="">
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium">{{ $sig->signature_name }}</td>
                            <td class="px-4 py-3">{{ $sig->user?->name }}</td>
                            <td class="px-4 py-3"><x-admin.status-badge :active="$sig->status" /></td>
                            <td class="px-4 py-3 text-right space-x-3">
                                <a href="{{ route('admin.signatures.edit', $sig) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Edit</a>
                                <x-admin.delete-form :action="route('admin.signatures.destroy', $sig)" />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">No signatures.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $signatures->links() }}</div>
    </x-admin.card>
@endsection
