@csrf

<input type="hidden" name="template_id" value="{{ $template->id }}">

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-admin.input label="Field Name (key)" name="field_name" :value="$field->field_name ?? ''" required help="lowercase_snake_case" />
    <x-admin.input label="Label" name="label" :value="$field->label ?? ''" required />

    <x-admin.select label="Field Type" name="field_type" required
        :options="$fieldTypes"
        :value="$field->field_type ?? 'text'" />

    <x-admin.input label="Placeholder" name="placeholder" :value="$field->placeholder ?? ''" />
    <x-admin.input label="Default Value" name="default_value" :value="$field->default_value ?? ''" />
    <x-admin.input label="Validation Rules" name="validation_rules" :value="$field->validation_rules ?? ''" help='Laravel rules e.g. "required|max:255"' />

    <x-admin.input label="Sort Order" type="number" name="sort_order" :value="$field->sort_order ?? 0" />

    <div class="md:col-span-2">
        <x-admin.input label="Options (comma-separated, for select/radio/checkbox)" name="options"
            :value="isset($field) && $field->options ? implode(', ', $field->options) : ''" />
    </div>

    <div class="flex items-center gap-2">
        <input type="hidden" name="is_required" value="0">
        <input type="checkbox" id="is_required" name="is_required" value="1" @checked(old('is_required', $field->is_required ?? false))
               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
        <label for="is_required" class="text-sm text-slate-700">Required</label>
    </div>

    <div class="flex items-center gap-2">
        <input type="hidden" name="status" value="0">
        <input type="checkbox" id="status" name="status" value="1" @checked(old('status', $field->status ?? true))
               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
        <label for="status" class="text-sm text-slate-700">Active</label>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-admin.button type="submit">{{ $submitLabel ?? 'Save' }}</x-admin.button>
    <x-admin.button :href="route('admin.templates.fields.index', $template)" variant="secondary">Cancel</x-admin.button>
</div>
