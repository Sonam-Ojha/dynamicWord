@csrf

@php
    $b = $branch ?? null;
    $bankOptions = $banks->pluck('bank_name', 'id')->toArray();
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-admin.select
        label="Bank"
        name="bank_id"
        :options="$bankOptions"
        :value="$b->bank_id ?? ($selectedBankId ?? null)"
        required
    />
    <x-admin.input label="Branch Name" name="branch_name" :value="$b->branch_name ?? ''" required />
    <x-admin.input label="Branch Code" name="branch_code" :value="$b->branch_code ?? ''" required />
    <x-admin.input label="IFSC Code" name="ifsc_code" :value="$b->ifsc_code ?? ''" />

    <div class="md:col-span-2">
        <x-admin.textarea label="Address" name="address" :value="$b->address ?? ''" />
    </div>

    <x-admin.input label="City" name="city" :value="$b->city ?? ''" />
    <x-admin.input label="State" name="state" :value="$b->state ?? ''" />
    <x-admin.input label="Pincode" name="pincode" :value="$b->pincode ?? ''" />
    <x-admin.input label="Phone" name="phone" :value="$b->phone ?? ''" />
    <x-admin.input label="Email" name="email" type="email" :value="$b->email ?? ''" />

    <div class="flex items-center gap-2">
        <input type="hidden" name="status" value="0">
        <input type="checkbox" id="status" name="status" value="1" @checked(old('status', $b->status ?? true))
               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
        <label for="status" class="text-sm text-slate-700">Active</label>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-admin.button type="submit" variant="primary">{{ $submitLabel ?? 'Save' }}</x-admin.button>
    <x-admin.button :href="route('admin.bank-branches.index')" variant="secondary">Cancel</x-admin.button>
</div>
