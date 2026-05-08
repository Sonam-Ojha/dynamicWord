<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage signatures') ?? false;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'signature_name' => ['required', 'string', 'max:255'],
            'signature_path' => [$this->isMethod('post') ? 'required' : 'nullable', 'image', 'mimes:png,jpg,jpeg,svg', 'max:2048'],
            'status' => ['nullable', 'boolean'],
        ];
    }
}
