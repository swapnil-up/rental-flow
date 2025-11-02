<?php

namespace App\Admin\Properties\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // add auth later
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:apartment,house,condo,studio'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'size:2'],
            'zip_code' => ['required', 'string', 'max:10'],
            'bedrooms' => ['required', 'integer', 'min:0', 'max:20'],
            'bathrooms' => ['required', 'numeric', 'min:0', 'max:20'],
            'square_feet' => ['required', 'integer', 'min:1'],
            'monthly_rent' => ['required', 'numeric', 'min:0'],
            'utilities_cost' => ['sometimes', 'numeric', 'min:0'],
            'management_fee' => ['sometimes', 'numeric', 'min:0'],
            'status' => ['sometimes', 'string', 'in:available,occupied,maintenance'],
        ];
    }
}