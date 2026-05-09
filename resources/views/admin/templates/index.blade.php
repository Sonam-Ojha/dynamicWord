@extends('admin.layouts.app')

@section('title', 'Templates')
@section('page_title', 'Templates')

@section('page_actions')
    <x-admin.button :href="route('admin.templates.create')">+ New Template</x-admin.button>
@endsection

@section('content')
    <x-admin.card>
        <div class="mb-4">
            <x-admin.search-bar :action="route('admin.templates.index')" placeholder="Search templates...">
                <select name="bank_id" class="rounded-md border-slate-300 text-sm">
                    <option value="">All Banks</option>
                    @foreach ($banks as $b)
                        <option value="{{ $b->id }}" @selected(request('bank_id') == $b->id)>{{ $b->bank_name }}</option>
                    @endforeach
                </select>
            </x-admin.search-bar>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Code</th>
                        <th class="px-4 py-3 text-left">Bank</th>
                        <th class="px-4 py-3 text-left">Branch</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($templates as $template)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium">{{ $template->template_name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $template->template_code }}</td>
                            <td class="px-4 py-3">{{ $template->bank?->bank_name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $template->branch?->branch_name ?? 'Any' }}</td>
                            <td class="px-4 py-3">
                                <x-admin.status-toggle :action="route('admin.templates.toggle-status', $template)" :active="$template->status" />
                            </td>
                            <td class="px-4 py-3 text-right space-x-3">
                                <a href="{{ route('admin.templates.fields.index', $template) }}" class="text-emerald-600 hover:text-emerald-800 text-sm">Fields</a>
                                <a href="{{ route('admin.templates.edit', $template) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Edit</a>
                                <x-admin.delete-form :action="route('admin.templates.destroy', $template)" />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">No templates.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $templates->links() }}</div>
    </x-admin.card>
@endsection
