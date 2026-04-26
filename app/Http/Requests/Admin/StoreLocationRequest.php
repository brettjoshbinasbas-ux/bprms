<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $locationId = $this->route('location');
        return [
            'location_name' => ['required', 'string', 'max:50', 'unique:locations,location_name,' . ($locationId ? $locationId->location_id : 'NULL') . ',location_id'],
            'location_description' => ['nullable', 'string', 'max:200'],
        ];
    }
}
