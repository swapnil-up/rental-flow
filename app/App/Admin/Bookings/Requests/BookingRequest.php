<?php

namespace App\Admin\Bookings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'property_id' => ['required', 'integer', 'exists:properties,id'],
            'tenant_id' => ['nullable', 'integer', 'exists:tenants,id'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
        ];
    }

    public function messages(): array
    {
        return [
            'check_in.after_or_equal' => 'Check-in must be today or later.',
            'check_out.after' => 'Check-out must be after check-in.',
            'property_id.exists' => 'The selected property does not exist.',
        ];
    }
}
