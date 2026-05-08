@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-admin.input label="Permission Name" name="name" :value="$permission->name ?? ''" required help="lowercase, e.g. 'manage reports'" />
    <x-admin.input label="Guard" name="guard_name" :value="$permission->guard_name ?? 'web'" help="usually 'web'" />

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-2">Assign to Roles</label>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
            @foreach ($roles as $role)
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                           @checked(in_array($role->name, old('roles', $permissionRoles ?? [])))
                           class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span>{{ $role->name }}</span>
                </label>
            @endforeach
        </div>
        @error('roles') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-admin.button type="submit">{{ $submitLabel ?? 'Save' }}</x-admin.button>
    <x-admin.button :href="route('admin.permissions.index')" variant="secondary">Cancel</x-admin.button>
</div>
