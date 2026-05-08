<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TemplateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage categories') ?? false;
    }

    public function rules(): array
    {
        return [
            'category_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['nullable', 'boolean'],
        ];
    }
}
