<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePremisesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'location_id' => ['required', 'exists:locations,location_id'],
            'premises_name' => ['required', 'string', 'max:100'],
            'premises_type' => ['required', 'in:business_premises,market_table,market_stall,food_stall,handicraft,workshop,various'],
            'premises_description' => ['nullable', 'string', 'max:255'],
            'rental_fee' => ['required', 'numeric', 'min:0'],
            'premises_status' => ['required', 'in:available,occupied,unavailable'],
        ];
    }
}
