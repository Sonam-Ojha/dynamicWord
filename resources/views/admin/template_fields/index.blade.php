@extends('admin.layouts.app')

@section('title', 'Template Fields')
@section('page_title', 'Fields — '.$template->template_name)

@section('breadcrumbs')
    <a href="{{ route('admin.templates.index') }}" class="hover:underline">Templates</a> /
    <a href="{{ route('admin.templates.edit', $template) }}" class="hover:underline">{{ $template->template_name }}</a> /
    Fields
@endsection

@section('page_actions')
    <div class="flex flex-wrap gap-2">
        <x-admin.button :href="route('admin.templates.fields.sync', $template)" variant="secondary">⚡ Auto-Detect from HTML</x-admin.button>
        <x-admin.button :href="route('admin.templates.fields.create', $template)">+ New Field</x-admin.button>
    </div>
@endsection

@section('content')
    @php
        $totalFields = $fields->total();
        $visibleCount = $template->fields()->where('status', true)->count();
    @endphp

    <div class="flex flex-wrap gap-3 mb-4 bg-white rounded-lg border border-slate-200 p-4">
        <div class="flex-1 min-w-[140px] flex items-center gap-3 px-3 py-2 rounded-md border border-slate-200">
            <div class="w-9 h-9 rounded-md bg-slate-100 text-slate-700 flex items-center justify-center font-bold">{{ $totalFields }}</div>
            <div>
                <div class="text-xs text-slate-500 uppercase">Total</div>
                <div class="text-sm font-semibold text-slate-900">All fields</div>
            </div>
        </div>
        <div class="flex-1 min-w-[140px] flex items-center gap-3 px-3 py-2 rounded-md border border-emerald-200 bg-emerald-50">
            <div class="w-9 h-9 rounded-md bg-emerald-600 text-white flex items-center justify-center font-bold">{{ $visibleCount }}</div>
            <div>
                <div class="text-xs text-emerald-700 uppercase">Visible</div>
                <div class="text-sm font-semibold text-emerald-900">Shown to operator</div>
            </div>
        </div>
        <div class="flex-1 min-w-[140px] flex items-center gap-3 px-3 py-2 rounded-md border border-slate-200">
            <div class="w-9 h-9 rounded-md bg-slate-200 text-slate-500 flex items-center justify-center font-bold">{{ $totalFields - $visibleCount }}</div>
            <div>
                <div class="text-xs text-slate-500 uppercase">Hidden</div>
                <div class="text-sm font-semibold text-slate-700">Not in form</div>
            </div>
        </div>
    </div>

    <x-admin.card>
        <p class="text-sm text-slate-600 mb-4">
            Operator form me sirf <span class="font-semibold text-emerald-700">visible (✅)</span> fields hi dikhenge.
            Selection bulk me toggle karne ke liye row checkboxes use karo.
        </p>

        <form method="POST" action="{{ route('admin.templates.fields.bulk-toggle', $template) }}"
              x-data="{ selected: [] }"
              onsubmit="return confirm('Apply this action to selected fields?');">
            @csrf

            {{-- Bulk action bar --}}
            <div class="bg-slate-50 border border-slate-200 rounded-md px-4 py-3 mb-3 flex flex-wrap items-center gap-3">
                <div class="text-sm font-medium text-slate-700">
                    <span x-text="selected.length"></span> selected
                </div>
                <div class="flex flex-wrap gap-2 ml-auto">
                    <button type="submit" name="bulk_action" value="enable"
                            :disabled="selected.length === 0"
                            class="inline-flex items-center gap-1 px-4 py-2 rounded-md bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 shadow-sm disabled:opacity-40 disabled:cursor-not-allowed">
                        ✓ Show in Form
                    </button>
                    <button type="submit" name="bulk_action" value="disable"
                            :disabled="selected.length === 0"
                            class="inline-flex items-center gap-1 px-4 py-2 rounded-md bg-slate-700 text-white text-sm font-semibold hover:bg-slate-800 shadow-sm disabled:opacity-40 disabled:cursor-not-allowed">
                        ✕ Hide
                    </button>
                    <button type="submit" name="bulk_action" value="require"
                            :disabled="selected.length === 0"
                            class="inline-flex items-center gap-1 px-4 py-2 rounded-md bg-amber-600 text-white text-sm font-semibold hover:bg-amber-700 shadow-sm disabled:opacity-40 disabled:cursor-not-allowed">
                        Required
                    </button>
                    <button type="submit" name="bulk_action" value="unrequire"
                            :disabled="selected.length === 0"
                            class="inline-flex items-center gap-1 px-4 py-2 rounded-md bg-white border border-slate-300 text-slate-700 text-sm font-semibold hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed">
                        Optional
                    </button>
                    <button type="submit" name="bulk_action" value="delete"
                            :disabled="selected.length === 0"
                            onclick="return confirm('Delete selected fields permanently?');"
                            class="inline-flex items-center gap-1 px-4 py-2 rounded-md bg-red-600 text-white text-sm font-semibold hover:bg-red-700 shadow-sm disabled:opacity-40 disabled:cursor-not-allowed">
                        Delete
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left w-10">
                                <input type="checkbox"
                                       @click="selected = $event.target.checked
                                               ? Array.from(document.querySelectorAll('.field-row-check')).map(c => c.value)
                                               : []"
                                       class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            </th>
                            <th class="px-4 py-3 text-left">Order</th>
                            <th class="px-4 py-3 text-left">Field Name</th>
                            <th class="px-4 py-3 text-left">Label</th>
                            <th class="px-4 py-3 text-left">Type</th>
                            <th class="px-4 py-3 text-left">Required</th>
                            <th class="px-4 py-3 text-left">Show in Form</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($fields as $field)
                            <tr class="hover:bg-slate-50" :class="selected.includes('{{ $field->id }}') ? 'bg-indigo-50' : ''">
                                <td class="px-4 py-3">
                                    <input type="checkbox" name="field_ids[]" value="{{ $field->id }}"
                                           x-model="selected"
                                           class="field-row-check rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                </td>
                                <td class="px-4 py-3 text-slate-500">{{ $field->sort_order }}</td>
                                <td class="px-4 py-3 font-mono text-xs">{{ $field->field_name }}</td>
                                <td class="px-4 py-3">{{ $field->label }}</td>
                                <td class="px-4 py-3"><span class="rounded bg-slate-100 text-slate-700 px-2 py-0.5 text-xs">{{ $field->field_type }}</span></td>
                                <td class="px-4 py-3">
                                    @if ($field->is_required)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 text-amber-700 text-xs px-2 py-0.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Required
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs">Optional</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        @if ($field->status)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 text-emerald-700 text-xs px-2 py-0.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Visible
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 text-slate-500 text-xs px-2 py-0.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Hidden
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right space-x-3">
                                    <a href="{{ route('admin.fields.edit', $field) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="px-4 py-8 text-center text-slate-500">No fields configured.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        <div class="mt-4">{{ $fields->links() }}</div>
    </x-admin.card>
@endsection
