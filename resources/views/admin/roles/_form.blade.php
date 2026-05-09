@csrf

<div class="grid grid-cols-1 gap-4">
    <x-admin.input label="Role Name" name="name" :value="$role->name ?? ''" required />

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-2">Permissions</label>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
            @foreach ($permissions as $perm)
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                           @checked(in_array($perm->name, old('permissions', $rolePermissions ?? [])))
                           class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span>{{ $perm->name }}</span>
                </label>
            @endforeach
        </div>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-admin.button type="submit">{{ $submitLabel ?? 'Save' }}</x-admin.button>
    <x-admin.button :href="route('admin.roles.index')" variant="secondary">Cancel</x-admin.button>
</div>
