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
    <x-admin.card>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Order</th>
                        <th class="px-4 py-3 text-left">Field Name</th>
                        <th class="px-4 py-3 text-left">Label</th>
                        <th class="px-4 py-3 text-left">Type</th>
                        <th class="px-4 py-3 text-left">Required</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($fields as $field)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3">{{ $field->sort_order }}</td>
                            <td class="px-4 py-3 font-mono text-xs">{{ $field->field_name }}</td>
                            <td class="px-4 py-3">{{ $field->label }}</td>
                            <td class="px-4 py-3"><span class="rounded bg-slate-100 text-slate-700 px-2 py-0.5 text-xs">{{ $field->field_type }}</span></td>
                            <td class="px-4 py-3">{!! $field->is_required ? '<span class="text-red-600">Yes</span>' : '<span class="text-slate-400">No</span>' !!}</td>
                            <td class="px-4 py-3">
                                <x-admin.status-toggle :action="route('admin.fields.toggle-status', $field)" :active="$field->status" />
                            </td>
                            <td class="px-4 py-3 text-right space-x-3">
                                <a href="{{ route('admin.fields.edit', $field) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Edit</a>
                                <x-admin.delete-form :action="route('admin.fields.destroy', $field)" />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-slate-500">No fields configured.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $fields->links() }}</div>
    </x-admin.card>
@endsection
