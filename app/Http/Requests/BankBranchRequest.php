<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BankBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $branchId = $this->route('bank_branch')?->id;
        $bankId = $this->input('bank_id');

        return [
            'bank_id' => ['required', 'exists:banks,id'],
            'branch_name' => ['required', 'string', 'max:255'],
            'branch_code' => [
                'required', 'string', 'max:50',
                Rule::unique('bank_branches', 'branch_code')
                    ->where(fn ($q) => $q->where('bank_id', $bankId))
                    ->ignore($branchId),
            ],
            'ifsc_code' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'pincode' => ['nullable', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'status' => ['nullable', 'boolean'],
        ];
    }
}
