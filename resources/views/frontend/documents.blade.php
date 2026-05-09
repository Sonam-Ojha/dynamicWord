@extends('frontend.layouts.app')

@section('title', 'My Documents')

@section('content')
    <div class="flex items-center justify-between mb-5">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">My Documents</h1>
            <p class="text-sm text-slate-500">All documents you've created — drafts and generated.</p>
        </div>
        <a href="{{ route('generate.banks') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
            + New Document
        </a>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('generate.myDocuments') }}"
          class="bg-white border border-slate-200 rounded-lg p-4 mb-4">
        <div class="flex flex-wrap gap-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by Document #..."
                   class="flex-1 min-w-[180px] rounded-md border-slate-300 text-sm">

            <select name="status" class="rounded-md border-slate-300 text-sm">
                <option value="">All Status</option>
                @foreach (['draft' => 'Draft', 'generated' => 'Generated', 'archived' => 'Archived'] as $key => $label)
                    <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                @endforeach
            </select>

            <select name="bank_id" class="rounded-md border-slate-300 text-sm">
                <option value="">All Banks</option>
                @foreach ($banks as $b)
                    <option value="{{ $b->id }}" @selected(request('bank_id') == $b->id)>{{ $b->bank_name }}</option>
                @endforeach
            </select>

            <input type="date" name="from" value="{{ request('from') }}"
                   class="rounded-md border-slate-300 text-sm" title="From date">
            <input type="date" name="to" value="{{ request('to') }}"
                   class="rounded-md border-slate-300 text-sm" title="To date">

            <button type="submit"
                    class="px-4 py-2 rounded-md bg-slate-800 text-white text-sm font-medium hover:bg-slate-900">
                Filter
            </button>
            <a href="{{ route('generate.myDocuments') }}"
               class="px-4 py-2 rounded-md bg-white border border-slate-300 text-slate-700 text-sm">
                Reset
            </a>
        </div>
    </form>

    {{-- Documents table --}}
    <div class="bg-white rounded-lg shadow-sm border border-slate-200">
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
                    @forelse ($documents as $doc)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium font-mono text-xs">{{ $doc->document_number }}</td>
                            <td class="px-4 py-3">{{ $doc->bank?->bank_name }}</td>
                            <td class="px-4 py-3">{{ $doc->template?->template_name }}</td>
                            <td class="px-4 py-3">
                                @if ($doc->status === 'generated')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 text-emerald-700 px-2 py-0.5 text-xs font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Generated
                                    </span>
                                @elseif ($doc->status === 'archived')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 text-slate-600 px-2 py-0.5 text-xs font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Archived
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 text-amber-700 px-2 py-0.5 text-xs font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-500">{{ $doc->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3 text-right space-x-3">
                                @if ($doc->status === 'draft')
                                    <a href="{{ route('generate.editDraft', $doc) }}" class="text-amber-600 hover:text-amber-800 font-medium">Resume</a>
                                @endif
                                <a href="{{ route('generate.preview', $doc) }}" class="text-indigo-600 hover:text-indigo-800">View</a>
                                @if ($doc->status === 'generated')
                                    <x-download-menu :document="$doc" size="sm" />
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <p class="text-slate-500 mb-3">No documents match your filters.</p>
                                <a href="{{ route('generate.banks') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                    Generate a new document →
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($documents->hasPages())
            <div class="px-4 py-3 border-t border-slate-200">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
@endsection
