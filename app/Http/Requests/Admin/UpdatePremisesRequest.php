<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePremisesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'premises_name' => ['required', 'string', 'max:100'],
            'premises_description' => ['nullable', 'string', 'max:255'],
            'unit_count' => ['required', 'integer', 'min:1', 'max:255'],
            'rental_fee' => ['required', 'numeric', 'min:0'],
            'premises_status' => ['required', 'in:available,occupied,unavailable'],
            // location_id, premises_type, applicant_quota are NOT validated here
            // because they come from hidden inputs and shouldn't change
        ];
    }
}
