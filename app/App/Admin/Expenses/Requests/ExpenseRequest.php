<?php

namespace App\Admin\Expenses\Requests;

use Domain\Expenses\States\ExpenseCategory;
use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'property_id' => ['required', 'exists:properties,id'],
            'category' => ['required', 'string', 'in:' . implode(',', array_map(fn($c) => $c->value, ExpenseCategory::cases()))],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'date' => ['required', 'date'],
        ];
    }
}
