@extends('frontend.layouts.app')

@section('title', 'Preview Document')

@section('steps')
    @include('frontend.partials.steps', ['current' => 4])
@endsection

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Preview Document</h1>
            <p class="text-slate-500">
                Document #<span class="font-medium text-slate-700">{{ $document->document_number }}</span>
                · {{ $document->bank?->bank_name }} · {{ $document->template?->template_name }}
                ·
                <span class="rounded-full px-2 py-0.5 text-xs
                    {{ $document->status === 'generated' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ ucfirst($document->status) }}
                </span>
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            @if ($document->status === 'draft')
                <a href="{{ route('generate.editDraft', $document) }}"
                   class="inline-flex items-center px-3 py-2 rounded-md bg-amber-500 text-white text-sm font-medium hover:bg-amber-600">
                    ← Continue Editing
                </a>
            @else
                <a href="{{ route('generate.form', $document->template_id) }}"
                   class="inline-flex items-center px-3 py-2 rounded-md bg-white border border-slate-300 text-slate-700 text-sm hover:bg-slate-50">
                    New from this template
                </a>
            @endif

            @if ($document->status !== 'generated')
                <form method="POST" action="{{ route('generate.finalize', $document) }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-3 py-2 rounded-md bg-green-600 text-white text-sm font-medium hover:bg-green-700">
                        Finalize Document
                    </button>
                </form>
            @endif

            <a href="{{ route('generate.print', $document) }}" target="_blank"
               class="inline-flex items-center px-3 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                Print
            </a>

            @if ($document->status === 'generated')
                <x-download-menu :document="$document" />
            @endif
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
        <div class="bg-slate-50 border-b border-slate-200 px-5 py-3 text-xs text-slate-500">
            Live preview — placeholders replaced with your input.
        </div>
        <div class="p-8 prose max-w-none">
            {!! $rendered !!}
        </div>
    </div>
@endsection
