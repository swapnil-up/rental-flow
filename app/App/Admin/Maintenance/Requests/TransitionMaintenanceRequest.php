<?php

namespace App\Admin\Maintenance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransitionMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:reported,assigned,in_progress,resolved'],
        ];
    }
}
