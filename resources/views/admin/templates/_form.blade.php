@csrf

@php
    $currentBankId = old('bank_id', $template->bank_id ?? '');
    $currentBranchId = old('branch_id', $template->branch_id ?? '');
    $branchesJson = ($branches ?? collect())->map(fn ($b) => [
        'id' => $b->id, 'bank_id' => $b->bank_id,
        'label' => $b->branch_name . ($b->branch_code ? ' ('.$b->branch_code.')' : ''),
    ])->values();
@endphp

<div x-data='{
        bankId: @json((string) $currentBankId),
        branchId: @json((string) $currentBranchId),
        branches: @json($branchesJson),
        get filtered() { return this.branches.filter(b => String(b.bank_id) === String(this.bankId)); }
     }'
     class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Bank <span class="text-red-500">*</span></label>
        <select name="bank_id" required x-model="bankId" @change="branchId = ''"
                class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            <option value="">— Select —</option>
            @foreach ($banks as $b)
                <option value="{{ $b->id }}">{{ $b->bank_name }}</option>
            @endforeach
        </select>
        @error('bank_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Branch <span class="text-slate-400 text-xs">(optional)</span></label>
        <select name="branch_id" x-model="branchId" :disabled="!bankId"
                class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm disabled:bg-slate-100">
            <option value="">— Any branch —</option>
            <template x-for="b in filtered" :key="b.id">
                <option :value="b.id" x-text="b.label" :selected="String(b.id) === String(branchId)"></option>
            </template>
        </select>
        <p class="mt-1 text-xs text-slate-500">Leave empty if template applies to all branches of the bank.</p>
        @error('branch_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

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
