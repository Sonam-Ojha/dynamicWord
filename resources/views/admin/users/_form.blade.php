@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-admin.input label="Name" name="name" :value="$user->name ?? ''" required />
    <x-admin.input label="Email" name="email" type="email" :value="$user->email ?? ''" required />
    <x-admin.input label="Phone" name="phone" :value="$user->phone ?? ''" />

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Profile Image</label>
        <input type="file" name="profile_image" accept="image/*" class="block w-full text-sm">
        @if (! empty($user->profile_image))
            <img src="{{ asset('storage/'.$user->profile_image) }}" class="mt-2 w-12 h-12 rounded-full object-cover border" alt="">
        @endif
    </div>

    <x-admin.input label="Password" name="password" type="password" :required="! isset($user)" help="{{ isset($user) ? 'Leave blank to keep current password' : '' }}" />
    <x-admin.input label="Confirm Password" name="password_confirmation" type="password" :required="! isset($user)" />

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-2">Roles <span class="text-red-500">*</span></label>
        <div class="flex flex-wrap gap-3">
            @foreach ($roles as $role)
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" name="roles[]" value="{{ $role }}"
                           @checked(in_array($role, old('roles', $userRoles ?? [])))
                           class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span>{{ $role }}</span>
                </label>
            @endforeach
        </div>
        @error('roles') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center gap-2">
        <input type="hidden" name="status" value="0">
        <input type="checkbox" id="status" name="status" value="1" @checked(old('status', $user->status ?? true))
               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
        <label for="status" class="text-sm text-slate-700">Active</label>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-admin.button type="submit">{{ $submitLabel ?? 'Save' }}</x-admin.button>
    <x-admin.button :href="route('admin.users.index')" variant="secondary">Cancel</x-admin.button>
</div>
