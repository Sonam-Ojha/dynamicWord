@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-admin.stat-card title="Total Banks" :value="$stats['banks']" icon="building" color="indigo" />
        <x-admin.stat-card title="Total Templates" :value="$stats['templates']" icon="document" color="emerald" />
        <x-admin.stat-card title="Total Documents" :value="$stats['documents']" icon="document-text" color="amber" />
        <x-admin.stat-card title="Total Users" :value="$stats['users']" icon="users" color="rose" />
    </div>

    <x-admin.card title="Recent Documents">
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
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($recentDocuments as $doc)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $doc->document_number }}</td>
                            <td class="px-4 py-3">{{ $doc->user?->name }}</td>
                            <td class="px-4 py-3">{{ $doc->bank?->bank_name }}</td>
                            <td class="px-4 py-3">{{ $doc->template?->template_name }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full bg-blue-50 text-blue-700 text-xs px-2 py-0.5">{{ ucfirst($doc->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-slate-500">{{ $doc->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">No documents yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin.card>
@endsection
