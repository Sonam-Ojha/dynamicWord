@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-admin.select label="Bank" name="bank_id" required
        :options="$banks->pluck('bank_name', 'id')->all()"
        :value="$template->bank_id ?? null" />

    <x-admin.select label="Category" name="category_id" required
        :options="$categories->pluck('category_name', 'id')->all()"
        :value="$template->category_id ?? null" />

    <x-admin.input label="Template Name" name="template_name" :value="$template->template_name ?? ''" required />
    <x-admin.input label="Template Code" name="template_code" :value="$template->template_code ?? ''" required />

    <div class="md:col-span-2">
        <x-admin.textarea label="Description" name="description" :value="$template->description ?? ''" />
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Preview Image</label>
        <input type="file" name="template_preview" accept="image/*" class="block w-full text-sm">
        @if (! empty($template->template_preview))
            <img src="{{ asset('storage/'.$template->template_preview) }}" class="mt-2 w-32 rounded border" alt="">
        @endif
    </div>

    <div class="flex items-center gap-2 self-end">
        <input type="hidden" name="status" value="0">
        <input type="checkbox" id="status" name="status" value="1" @checked(old('status', $template->status ?? true))
               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
        <label for="status" class="text-sm text-slate-700">Active</label>
    </div>

    <div class="md:col-span-2">
        <x-admin.textarea label="HTML Content" name="html_content" :value="$template->html_content ?? ''" rows="12"
                          help="HTML with placeholders like {field_name} that map to template fields." />
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-admin.button type="submit">{{ $submitLabel ?? 'Save' }}</x-admin.button>
    <x-admin.button :href="route('admin.templates.index')" variant="secondary">Cancel</x-admin.button>
</div>
