<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GeneratedDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage documents') ?? false;
    }

    public function rules(): array
    {
        $documentId = $this->route('document')?->id;

        return [
            'user_id' => ['required', 'exists:users,id'],
            'bank_id' => ['required', 'exists:banks,id'],
            'template_id' => ['required', 'exists:templates,id'],
            'document_number' => ['required', 'string', 'max:100', Rule::unique('generated_documents', 'document_number')->ignore($documentId)],
            'form_data' => ['nullable', 'array'],
            'generated_file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'status' => ['required', Rule::in(['draft', 'generated', 'archived'])],
        ];
    }
}
