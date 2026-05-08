@extends('frontend.layouts.app')

@section('title', 'My Dashboard')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Welcome, {{ auth()->user()->name }}</h1>
            <p class="text-slate-500">Generate, manage and download your bank documents.</p>
        </div>
        <a href="{{ route('generate.banks') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
            + Generate New Document
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-5">
            <div class="text-sm text-slate-500">Total Documents</div>
            <div class="text-3xl font-semibold text-slate-900">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-5">
            <div class="text-sm text-slate-500">Drafts</div>
            <div class="text-3xl font-semibold text-amber-600">{{ $stats['drafts'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-5">
            <div class="text-sm text-slate-500">Generated</div>
            <div class="text-3xl font-semibold text-green-600">{{ $stats['generated'] }}</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-slate-200">
        <div class="px-5 py-4 border-b border-slate-200">
            <h3 class="font-semibold text-slate-800">Recent Documents</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Document #</th>
                        <th class="px-4 py-3 text-left">Bank</th>
                        <th class="px-4 py-3 text-left">Template</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Created</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($myDocs as $doc)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium">{{ $doc->document_number }}</td>
                            <td class="px-4 py-3">{{ $doc->bank?->bank_name }}</td>
                            <td class="px-4 py-3">{{ $doc->template?->template_name }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2 py-0.5 text-xs
                                    {{ $doc->status === 'generated' ? 'bg-green-100 text-green-700' : ($doc->status === 'archived' ? 'bg-slate-100 text-slate-600' : 'bg-amber-100 text-amber-700') }}">
                                    {{ ucfirst($doc->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-500">{{ $doc->created_at->diffForHumans() }}</td>
                            <td class="px-4 py-3 text-right space-x-3">
                                <a href="{{ route('generate.preview', $doc) }}" class="text-indigo-600 hover:text-indigo-800">View</a>
                                @if ($doc->status === 'generated')
                                    <a href="{{ route('generate.download', $doc) }}" class="text-emerald-600 hover:text-emerald-800">Download</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">No documents yet. Click "Generate New Document" to start.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
