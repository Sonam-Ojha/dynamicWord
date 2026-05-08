@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-admin.select label="User" name="user_id" required
        :options="$users->pluck('name', 'id')->all()"
        :value="$document->user_id ?? null" />

    <x-admin.select label="Bank" name="bank_id" required
        :options="$banks->pluck('bank_name', 'id')->all()"
        :value="$document->bank_id ?? null" />

    <x-admin.select label="Template" name="template_id" required
        :options="$templates->pluck('template_name', 'id')->all()"
        :value="$document->template_id ?? null" />

    <x-admin.input label="Document Number" name="document_number" :value="$document->document_number ?? ''" required />

    <x-admin.select label="Status" name="status" required
        :options="['draft' => 'Draft', 'generated' => 'Generated', 'archived' => 'Archived']"
        :value="$document->status ?? 'draft'" :placeholder="null" />

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Generated File</label>
        <input type="file" name="generated_file" class="block w-full text-sm">
        @if (! empty($document->generated_file))
            <a href="{{ asset('storage/'.$document->generated_file) }}" target="_blank" class="mt-2 inline-block text-sm text-indigo-600 hover:underline">View current file</a>
        @endif
    </div>

    <div class="md:col-span-2">
        <x-admin.textarea label="Form Data (JSON)" name="form_data_json" rows="6"
            :value="isset($document) && $document->form_data ? json_encode($document->form_data, JSON_PRETTY_PRINT) : ''"
            help="Optional. Edit only if you know JSON syntax." />
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-admin.button type="submit">{{ $submitLabel ?? 'Save' }}</x-admin.button>
    <x-admin.button :href="route('admin.documents.index')" variant="secondary">Cancel</x-admin.button>
</div>
