@extends('admin.layouts.app')

@section('title', 'Sync Fields from HTML')
@section('page_title', 'Auto-Detect Fields — '.$template->template_name)

@section('breadcrumbs')
    <a href="{{ route('admin.templates.index') }}" class="hover:underline">Templates</a> /
    <a href="{{ route('admin.templates.edit', $template) }}" class="hover:underline">{{ $template->template_name }}</a> /
    <a href="{{ route('admin.templates.fields.index', $template) }}" class="hover:underline">Fields</a> /
    Sync
@endsection

@section('content')
    @php
        $missing = collect($analysis)->where('exists', false);
        $existing = collect($analysis)->where('exists', true);
    @endphp

    <div class="flex flex-wrap gap-3 mb-4 bg-white rounded-lg border border-slate-200 p-4">
        <div class="flex-1 min-w-[140px] flex items-center gap-3 px-3 py-2 rounded-md border border-slate-200">
            <div class="w-10 h-10 rounded-md bg-slate-100 text-slate-700 flex items-center justify-center font-bold">{{ count($analysis) }}</div>
            <div>
                <div class="text-xs text-slate-500 uppercase">Total</div>
                <div class="text-sm font-semibold text-slate-900">Placeholders</div>
            </div>
        </div>
        <div class="flex-1 min-w-[140px] flex items-center gap-3 px-3 py-2 rounded-md border border-amber-200 bg-amber-50">
            <div class="w-10 h-10 rounded-md bg-amber-600 text-white flex items-center justify-center font-bold">{{ $missing->count() }}</div>
            <div>
                <div class="text-xs text-amber-700 uppercase">Missing</div>
                <div class="text-sm font-semibold text-amber-900">Need to create</div>
            </div>
        </div>
        <div class="flex-1 min-w-[140px] flex items-center gap-3 px-3 py-2 rounded-md border border-emerald-200 bg-emerald-50">
            <div class="w-10 h-10 rounded-md bg-emerald-600 text-white flex items-center justify-center font-bold">{{ $existing->count() }}</div>
            <div>
                <div class="text-xs text-emerald-700 uppercase">Created</div>
                <div class="text-sm font-semibold text-emerald-900">Already exist</div>
            </div>
        </div>
    </div>

    @if (count($analysis) === 0)
        <x-admin.card>
            <div class="text-center py-10 text-slate-500">
                No placeholders detected. Add <code class="bg-slate-100 px-1 rounded">{field_name}</code> or
                <code class="bg-slate-100 px-1 rounded">@{{field_name}}</code> in the template HTML, then come back to this page.
            </div>
            <div class="mt-4 text-center">
                <x-admin.button :href="route('admin.templates.edit', $template)">Edit Template HTML</x-admin.button>
            </div>
        </x-admin.card>
    @else
        @if ($missing->count() > 0)
            <x-admin.card title="Missing Placeholders — Bulk Create">
                <p class="text-sm text-slate-600 mb-4">
                    These placeholders are present in the template HTML but fields have not been created for them yet. Review the Type and Required options below, then create them in bulk.
                </p>

                <form method="POST" action="{{ route('admin.templates.fields.bulk-sync', $template) }}">
                    @csrf

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                                <tr>
                                    <th class="px-3 py-2 text-left w-10">
                                        <input type="checkbox" id="select-all" checked
                                               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                    </th>
                                    <th class="px-3 py-2 text-left">Placeholder</th>
                                    <th class="px-3 py-2 text-left">Suggested Label</th>
                                    <th class="px-3 py-2 text-left">Type</th>
                                    <th class="px-3 py-2 text-center">Required</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach ($missing as $item)
                                    <tr>
                                        <td class="px-3 py-2">
                                            <input type="checkbox" name="placeholders[]" value="{{ $item['name'] }}" checked
                                                   class="row-check rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                        </td>
                                        <td class="px-3 py-2 font-mono text-xs">{{ $item['name'] }}</td>
                                        <td class="px-3 py-2">{{ $item['suggested_label'] }}</td>
                                        <td class="px-3 py-2">
                                            <select name="types[{{ $item['name'] }}]"
                                                    class="rounded-md border-slate-300 text-xs py-1">
                                                @foreach ($fieldTypes as $key => $label)
                                                    <option value="{{ $key }}" @selected($key === $item['suggested_type'])>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <input type="checkbox" name="required[{{ $item['name'] }}]" value="1"
                                                   class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex items-center gap-3">
                        <x-admin.button type="submit">Create Selected Fields</x-admin.button>
                        <x-admin.button :href="route('admin.templates.fields.index', $template)" variant="secondary">Cancel</x-admin.button>
                    </div>
                </form>
            </x-admin.card>
        @endif

        @if ($existing->count() > 0)
            <div class="mt-6">
                <x-admin.card title="Already Created Fields">
                    <div class="flex flex-wrap gap-2">
                        @foreach ($existing as $item)
                            <span class="inline-flex items-center gap-1 rounded-full bg-green-50 text-green-700 text-xs px-2.5 py-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                {{ $item['name'] }}
                            </span>
                        @endforeach
                    </div>
                </x-admin.card>
            </div>
        @endif

        @if (count($orphans) > 0)
            <div class="mt-6">
                <x-admin.card title="⚠ Orphan Fields (no placeholder in HTML)">
                    <p class="text-sm text-slate-600 mb-3">
                        These fields exist but their placeholder is no longer present in the template HTML. You can manually delete them if needed.
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($orphans as $name)
                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 text-amber-700 text-xs px-2.5 py-1">{{ $name }}</span>
                        @endforeach
                    </div>
                </x-admin.card>
            </div>
        @endif
    @endif

    @push('scripts')
        <script>
            document.getElementById('select-all')?.addEventListener('change', (e) => {
                document.querySelectorAll('.row-check').forEach(cb => cb.checked = e.target.checked);
            });
        </script>
    @endpush
@endsection
