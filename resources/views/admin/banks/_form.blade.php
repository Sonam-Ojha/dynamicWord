@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-admin.input label="Bank Name" name="bank_name" :value="$bank->bank_name ?? ''" required />
    <x-admin.input label="Bank Code" name="bank_code" :value="$bank->bank_code ?? ''" required />

    <div class="md:col-span-2">
        <x-admin.textarea label="Description" name="description" :value="$bank->description ?? ''" />
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Logo</label>
        <input type="file" name="logo" accept="image/*" class="block w-full text-sm">
        @if (! empty($bank->logo) && \Illuminate\Support\Facades\Storage::disk('public')->exists($bank->logo))
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($bank->logo) }}" class="mt-2 w-16 h-16 rounded object-cover border" alt="">
        @endif
        @error('logo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center gap-2">
        <input type="hidden" name="status" value="0">
        <input type="checkbox" id="status" name="status" value="1" @checked(old('status', $bank->status ?? true))
               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
        <label for="status" class="text-sm text-slate-700">Active</label>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-admin.button type="submit" variant="primary">{{ $submitLabel ?? 'Save' }}</x-admin.button>
    <x-admin.button :href="route('admin.banks.index')" variant="secondary">Cancel</x-admin.button>
</div>
