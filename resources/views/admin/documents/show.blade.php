@extends('admin.layouts.app')

@section('title', 'Document #'.$document->document_number)
@section('page_title', 'Document #'.$document->document_number)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <x-admin.card title="Details" class="lg:col-span-2">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div><dt class="text-slate-500">User</dt><dd class="font-medium">{{ $document->user?->name }}</dd></div>
                <div><dt class="text-slate-500">Bank</dt><dd class="font-medium">{{ $document->bank?->bank_name }}</dd></div>
                <div><dt class="text-slate-500">Template</dt><dd class="font-medium">{{ $document->template?->template_name }}</dd></div>
                <div><dt class="text-slate-500">Status</dt><dd class="font-medium">{{ ucfirst($document->status) }}</dd></div>
                <div><dt class="text-slate-500">Created</dt><dd>{{ $document->created_at->format('Y-m-d H:i') }}</dd></div>
                <div>
                    <dt class="text-slate-500">File</dt>
                    <dd>
                        @if ($document->generated_file)
                            <a href="{{ asset('storage/'.$document->generated_file) }}" target="_blank" class="text-indigo-600 hover:underline">Download</a>
                        @else — @endif
                    </dd>
                </div>
            </dl>
        </x-admin.card>

        <x-admin.card title="Form Data">
            <pre class="text-xs bg-slate-50 p-3 rounded overflow-x-auto">{{ json_encode($document->form_data, JSON_PRETTY_PRINT) }}</pre>
        </x-admin.card>
    </div>
@endsection
