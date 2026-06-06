<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $templateId = $this->route('template')?->id;

        return [
            'bank_id' => ['required', 'exists:banks,id'],
            'branch_id' => [
                'nullable',
                Rule::exists('bank_branches', 'id')->where(fn ($q) => $q->where('bank_id', $this->input('bank_id'))),
            ],
            'template_name' => ['required', 'string', 'max:255'],
            'template_code' => ['required', 'string', 'max:100', Rule::unique('templates', 'template_code')->ignore($templateId)],
            'template_preview' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'html_content' => ['nullable', 'string'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['nullable', 'boolean'],
        ];
    }
}
