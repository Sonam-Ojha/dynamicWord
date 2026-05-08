@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-admin.select label="User" name="user_id" required
        :options="$users->pluck('name', 'id')->all()"
        :value="$signature->user_id ?? null" />

    <x-admin.input label="Signature Name" name="signature_name" :value="$signature->signature_name ?? ''" required />

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1">Signature Image</label>
        <input type="file" name="signature_path" accept="image/*" class="block w-full text-sm">
        @if (! empty($signature->signature_path))
            <img src="{{ asset('storage/'.$signature->signature_path) }}" class="mt-2 h-16" alt="">
        @endif
        @error('signature_path') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center gap-2">
        <input type="hidden" name="status" value="0">
        <input type="checkbox" id="status" name="status" value="1" @checked(old('status', $signature->status ?? true))
               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
        <label for="status" class="text-sm text-slate-700">Active</label>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-admin.button type="submit">{{ $submitLabel ?? 'Save' }}</x-admin.button>
    <x-admin.button :href="route('admin.signatures.index')" variant="secondary">Cancel</x-admin.button>
</div>
