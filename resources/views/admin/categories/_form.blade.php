@csrf

<div class="grid grid-cols-1 gap-4">
    <x-admin.input label="Category Name" name="category_name" :value="$category->category_name ?? ''" required />
    <x-admin.textarea label="Description" name="description" :value="$category->description ?? ''" />

    <div class="flex items-center gap-2">
        <input type="hidden" name="status" value="0">
        <input type="checkbox" id="status" name="status" value="1" @checked(old('status', $category->status ?? true))
               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
        <label for="status" class="text-sm text-slate-700">Active</label>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-admin.button type="submit">{{ $submitLabel ?? 'Save' }}</x-admin.button>
    <x-admin.button :href="route('admin.categories.index')" variant="secondary">Cancel</x-admin.button>
</div>
