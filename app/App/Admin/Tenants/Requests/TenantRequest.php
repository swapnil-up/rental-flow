<?php

namespace App\Admin\Tenants\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tenantId = $this->route('tenant');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('tenants', 'email')->ignore($tenantId),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
        ];
    }
}
