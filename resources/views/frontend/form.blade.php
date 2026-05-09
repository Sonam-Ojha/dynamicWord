@extends('frontend.layouts.app')

@section('title', 'Fill Document')

@section('steps')
    @include('frontend.partials.steps', ['current' => 4])
@endsection

@php
    $document = $document ?? null;
    $existingData = $document?->form_data ?? [];
    $formAction = $document
        ? route('generate.updateDraft', $document)
        : route('generate.store', $template);
    $formMethod = $document ? 'PUT' : 'POST';
@endphp

@push('styles')
<style>
    .form-grid-1 {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    .form-grid-4 {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    @media (min-width: 640px) {
        .form-grid-4 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (min-width: 768px) {
        .form-grid-4 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    }
    @media (min-width: 1024px) {
        .form-grid-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    }
    .form-grid-4 > .col-full,
    .form-grid-1 > .col-full { grid-column: 1 / -1; }
</style>
@endpush

@section('content')
<div x-data="{ showPreview: true }">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">{{ $template->template_name }}</h1>
            <p class="text-slate-500">
                <span class="font-medium text-slate-700">{{ $template->bank?->bank_name }}</span>
                @if ($document)
                    · <span class="rounded-full bg-amber-100 text-amber-700 text-xs px-2 py-0.5">Resuming draft #{{ $document->document_number }}</span>
                @endif
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" @click="showPreview = !showPreview"
                    class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-white border border-slate-300 text-slate-700 text-sm hover:bg-slate-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <span x-text="showPreview ? 'Hide Preview' : 'Show Live Preview'"></span>
            </button>
            <a href="{{ route('generate.branches', $template->bank_id) }}" class="text-sm text-slate-600 hover:text-indigo-600">← Change branch/template</a>
        </div>
    </div>

    @if ($template->fields->isEmpty())
        <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-lg p-5">
            This template has no input fields configured. Contact your administrator.
        </div>
    @else
        <div class="grid grid-cols-12 gap-6">
            {{-- Form section --}}
            <div :class="showPreview ? 'col-span-12 md:col-span-4' : 'col-span-12'">
                <form id="docForm" method="POST" action="{{ $formAction }}" enctype="multipart/form-data"
                      class="bg-white border border-slate-200 rounded-lg p-5">
                    @csrf
                    @if ($formMethod === 'PUT') @method('PUT') @endif

                    @php
                        $currentBranchId = old('branch_id', $document?->branch_id ?? request('branch') ?? $template->branch_id ?? null);
                    @endphp
                    <div class="mb-4 pb-4 border-b border-slate-200">
                        <label for="branch_id" class="block text-sm font-medium text-slate-700 mb-1">
                            Bank Branch <span class="text-red-500">*</span>
                        </label>
                        <select id="branch_id" name="branch_id"
                                class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">— Select Branch —</option>
                            @foreach (($branches ?? []) as $br)
                                <option value="{{ $br->id }}" @selected($currentBranchId == $br->id)>
                                    {{ $br->branch_name }}@if ($br->branch_code) ({{ $br->branch_code }})@endif
                                    @if ($br->city) — {{ $br->city }} @endif
                                </option>
                            @endforeach
                        </select>
                        @if (($branches ?? collect())->isEmpty())
                            <p class="mt-1 text-xs text-amber-600">Is bank ki koi active branch nahi hai. Admin se contact karein.</p>
                        @endif
                        @error('branch_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div :class="showPreview ? 'form-grid-1' : 'form-grid-4'">
                        @foreach ($template->fields as $field)
                            @php
                                $name = 'fields['.$field->field_name.']';
                                $id = 'field_'.$field->field_name;
                                $errKey = 'fields.'.$field->field_name;
                                $existingVal = $existingData[$field->field_name] ?? $field->default_value;
                                $value = old($errKey, $existingVal);
                                $needsFullRow = in_array($field->field_type, ['textarea', 'image', 'signature', 'checkbox', 'radio'], true);
                            @endphp

                            <div data-field-name="{{ $field->field_name }}"
                                 class="{{ $needsFullRow ? 'col-full' : '' }}">
                                <label for="{{ $id }}" class="block text-sm font-medium text-slate-700 mb-1">
                                    {{ $field->label }}
                                    @if ($field->is_required) <span class="text-red-500">*</span> @endif
                                </label>

                                @switch($field->field_type)
                                    @case('textarea')
                                        <textarea id="{{ $id }}" name="{{ $name }}" rows="3"
                                                  data-fname="{{ $field->field_name }}" data-ftype="textarea"
                                                  placeholder="{{ $field->placeholder }}"
                                                  @if ($field->is_required) required @endif
                                                  class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ $value }}</textarea>
                                        @break

                                    @case('select')
                                        <select id="{{ $id }}" name="{{ $name }}"
                                                data-fname="{{ $field->field_name }}" data-ftype="select"
                                                @if ($field->is_required) required @endif
                                                class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            <option value="">— Select —</option>
                                            @foreach (($field->options ?? []) as $opt)
                                                <option value="{{ $opt }}" @selected($value == $opt)>{{ $opt }}</option>
                                            @endforeach
                                        </select>
                                        @break

                                    @case('radio')
                                        <div class="flex flex-wrap gap-4">
                                            @foreach (($field->options ?? []) as $opt)
                                                <label class="inline-flex items-center gap-2 text-sm">
                                                    <input type="radio" name="{{ $name }}" value="{{ $opt }}"
                                                           data-fname="{{ $field->field_name }}" data-ftype="radio"
                                                           @checked($value == $opt)
                                                           @if ($field->is_required) required @endif
                                                           class="border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                                    <span>{{ $opt }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        @break

                                    @case('checkbox')
                                        @php
                                            $existingCheck = $existingData[$field->field_name] ?? [];
                                            if (! is_array($existingCheck)) $existingCheck = [$existingCheck];
                                            $checkedValues = (array) old($errKey, $existingCheck);
                                        @endphp
                                        <div class="flex flex-wrap gap-4">
                                            @foreach (($field->options ?? []) as $opt)
                                                <label class="inline-flex items-center gap-2 text-sm">
                                                    <input type="checkbox" name="{{ $name }}[]" value="{{ $opt }}"
                                                           data-fname="{{ $field->field_name }}" data-ftype="checkbox"
                                                           @checked(in_array($opt, $checkedValues))
                                                           class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                                    <span>{{ $opt }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        @break

                                    @case('image')
                                    @case('signature')
                                        <input type="file" id="{{ $id }}" name="{{ $name }}" accept="image/*"
                                               data-fname="{{ $field->field_name }}" data-ftype="{{ $field->field_type }}"
                                               @if ($field->is_required && empty($existingVal)) required @endif
                                               class="block w-full text-sm">
                                        @if (! empty($existingVal))
                                            <div class="mt-2 flex items-center gap-2">
                                                <img src="{{ asset('storage/'.$existingVal) }}" class="h-12 rounded border" alt="">
                                                <span class="text-xs text-slate-500">Existing — upload new only if changing</span>
                                            </div>
                                        @endif
                                        @break

                                    @case('date')
                                        <input type="date" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}"
                                               data-fname="{{ $field->field_name }}" data-ftype="date"
                                               @if ($field->is_required) required @endif
                                               class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        @break

                                    @case('email')
                                        <input type="email" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}"
                                               data-fname="{{ $field->field_name }}" data-ftype="email"
                                               placeholder="{{ $field->placeholder }}"
                                               @if ($field->is_required) required @endif
                                               class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        @break

                                    @case('number')
                                        <input type="number" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}"
                                               data-fname="{{ $field->field_name }}" data-ftype="number"
                                               placeholder="{{ $field->placeholder }}"
                                               @if ($field->is_required) required @endif
                                               class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        @break

                                    @default
                                        <input type="text" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}"
                                               data-fname="{{ $field->field_name }}" data-ftype="text"
                                               placeholder="{{ $field->placeholder }}"
                                               @if ($field->is_required) required @endif
                                               class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                @endswitch

                                @error($errKey)
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 pt-5 border-t border-slate-200 flex flex-wrap items-center gap-3">
                        <button type="submit" name="action" value="generate"
                                class="inline-flex items-center px-5 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                            Generate Preview →
                        </button>

                        <button type="submit" name="action" value="draft" formnovalidate
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-amber-500 text-white text-sm font-medium hover:bg-amber-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Draft
                        </button>

                        <a href="{{ route('generate.index') }}"
                           class="inline-flex items-center px-4 py-2 rounded-md bg-white border border-slate-300 text-slate-700 text-sm hover:bg-slate-50">
                            Cancel
                        </a>

                        @if ($document)
                            <span class="text-xs text-slate-500 ml-auto">Last saved: {{ $document->updated_at->diffForHumans() }}</span>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Live preview --}}
            <div x-show="showPreview" x-cloak class="col-span-12 md:col-span-8">
                <div class="md:sticky md:top-4">
                    <div class="bg-slate-900 text-white text-xs uppercase tracking-wider px-4 py-2 rounded-t-lg flex items-center justify-between">
                        <span>Live Preview</span>
                        <span class="text-slate-400 normal-case">updates as you type</span>
                    </div>
                    <div class="bg-white border border-slate-200 border-t-0 rounded-b-lg shadow-sm overflow-auto"
                         style="max-height: calc(100vh - 200px);">
                        <div id="livePreview" class="p-6 prose max-w-none text-sm">
                            <div class="text-center text-slate-400 py-12">Form bharo, yaha live document banta jayega…</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
(() => {
    const tplHtml = @json($template->html_content ?? '');
    const fieldsMeta = @json($template->fields->pluck('field_type', 'field_name'));
    const previewEl = document.getElementById('livePreview');
    const formEl = document.getElementById('docForm');
    if (!previewEl || !formEl) return;

    const escapeHtml = (s) => String(s ?? '')
        .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;').replace(/'/g, '&#39;');

    const escapeRegex = (s) => s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

    const fileDataUrls = {};

    const renderPreview = () => {
        const values = {};

        formEl.querySelectorAll('[data-fname]').forEach((el) => {
            const name = el.dataset.fname;
            const type = el.dataset.ftype;

            if (type === 'image' || type === 'signature') {
                if (fileDataUrls[name]) {
                    values[name] = `<img src="${fileDataUrls[name]}" alt="" style="max-height:80px;border:1px solid #ddd;">`;
                }
                return;
            }
            if (type === 'checkbox') {
                if (!values[name]) values[name] = [];
                if (el.checked) values[name].push(el.value);
                return;
            }
            if (type === 'radio') {
                if (el.checked) values[name] = el.value;
                return;
            }
            values[name] = el.value;
        });

        let html = tplHtml;
        if (!html) {
            previewEl.innerHTML = '<div class="text-center text-slate-400 py-12">Template HTML empty hai.</div>';
            return;
        }

        Object.keys(fieldsMeta).forEach((name) => {
            const type = fieldsMeta[name];
            let val = values[name];

            const isEmpty = val === undefined || val === null || val === '' ||
                            (Array.isArray(val) && val.length === 0);

            const re = new RegExp(`\\{{1,2}\\s*${escapeRegex(name)}\\s*\\}{1,2}`, 'g');

            if (isEmpty) {
                const placeholder = `<span style="background:#fef3c7;color:#92400e;padding:0 4px;border-radius:3px;font-size:90%;">${escapeHtml('{' + name + '}')}</span>`;
                html = html.replace(re, placeholder);
                return;
            }

            let replacement;
            if (type === 'image' || type === 'signature') {
                replacement = val;
            } else if (Array.isArray(val)) {
                replacement = escapeHtml(val.join(', '));
            } else {
                replacement = escapeHtml(val);
            }
            html = html.replace(re, replacement);
        });

        previewEl.innerHTML = html;
    };

    formEl.addEventListener('change', (e) => {
        const t = e.target;
        if (t.type === 'file' && t.dataset.fname) {
            const name = t.dataset.fname;
            if (!t.files || !t.files[0]) {
                delete fileDataUrls[name];
                renderPreview();
                return;
            }
            const reader = new FileReader();
            reader.onload = (ev) => {
                fileDataUrls[name] = ev.target.result;
                renderPreview();
            };
            reader.readAsDataURL(t.files[0]);
        } else {
            renderPreview();
        }
    });

    formEl.addEventListener('input', renderPreview);

    renderPreview();
})();
</script>
@endpush
@endsection
