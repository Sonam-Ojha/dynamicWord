<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TemplateFieldRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $fieldId = $this->route('field')?->id;
        $templateId = $this->route('template')?->id ?? $this->input('template_id');

        return [
            'template_id' => ['required', 'exists:templates,id'],
            'field_name' => [
                'required', 'string', 'max:100',
                Rule::unique('template_fields', 'field_name')
                    ->where(fn ($q) => $q->where('template_id', $templateId))
                    ->ignore($fieldId),
            ],
            'label' => ['required', 'string', 'max:255'],
            'field_type' => ['required', Rule::in(array_keys(\App\Models\TemplateField::fieldTypes()))],
            'placeholder' => ['nullable', 'string', 'max:255'],
            'default_value' => ['nullable', 'string', 'max:500'],
            'options' => ['nullable', 'array'],
            'options.*' => ['string', 'max:255'],
            'validation_rules' => ['nullable', 'string', 'max:500'],
            'is_required' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ];
    }
}
