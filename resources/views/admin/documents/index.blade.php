@extends('admin.layouts.app')

@section('title', 'Documents')
@section('page_title', 'Generated Documents')

@section('page_actions')
    <x-admin.button :href="route('admin.documents.create')">+ New Document</x-admin.button>
@endsection

@section('content')
    <x-admin.card>
        <div class="mb-4">
            <x-admin.search-bar :action="route('admin.documents.index')" placeholder="Search by document #...">
                <select name="bank_id" class="rounded-md border-slate-300 text-sm">
                    <option value="">All Banks</option>
                    @foreach ($banks as $b)
                        <option value="{{ $b->id }}" @selected(request('bank_id') == $b->id)>{{ $b->bank_name }}</option>
                    @endforeach
                </select>
                <select name="status" class="rounded-md border-slate-300 text-sm">
                    <option value="">All Status</option>
                    @foreach (['draft', 'generated', 'archived'] as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </x-admin.search-bar>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Doc #</th>
                        <th class="px-4 py-3 text-left">User</th>
                        <th class="px-4 py-3 text-left">Bank</th>
                        <th class="px-4 py-3 text-left">Template</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Created</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($documents as $doc)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium">{{ $doc->document_number }}</td>
                            <td class="px-4 py-3">{{ $doc->user?->name }}</td>
                            <td class="px-4 py-3">{{ $doc->bank?->bank_name }}</td>
                            <td class="px-4 py-3">{{ $doc->template?->template_name }}</td>
                            <td class="px-4 py-3"><span class="rounded bg-slate-100 text-slate-700 px-2 py-0.5 text-xs">{{ ucfirst($doc->status) }}</span></td>
                            <td class="px-4 py-3 text-slate-500">{{ $doc->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3 text-right space-x-3">
                                <a href="{{ route('admin.documents.show', $doc) }}" class="text-slate-600 hover:text-slate-900 text-sm">View</a>
                                <a href="{{ route('admin.documents.edit', $doc) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Edit</a>
                                <x-admin.delete-form :action="route('admin.documents.destroy', $doc)" />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-slate-500">No documents.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $documents->links() }}</div>
    </x-admin.card>
@endsection
