<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResidentRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'resident_first_name' => ['required', 'string', 'max:50'],
            'resident_middle_name' => ['nullable', 'string', 'max:50'],
            'resident_last_name' => ['required', 'string', 'max:50'],
            'resident_ic_number' => ['required', 'string', 'size:12', 'regex:/^[0-9]+$/', 'unique:residents,resident_ic_number'],
            'resident_phone' => ['required', 'string', 'min:10', 'max:11', 'regex:/^01[0-9]{8,9}$/'],
            'resident_address' => ['required', 'string', 'max:255'],
            'resident_email' => ['required', 'email', 'max:100', 'unique:residents,resident_email'],
            'residency_duration' => ['required', 'integer', 'min:0', 'max:255'],
            'marital_status' => ['required', 'in:single,married,widowed,divorced'],
            'mdch_license_holder' => ['nullable', 'boolean'],
            'business_experience' => ['nullable', 'boolean'],
            'business_type' => ['nullable', 'string', 'max:100'],
            'resident_password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'resident_ic_number.size' => 'IC number must be exactly 12 digits.',
            'resident_ic_number.regex' => 'IC number must contain digits only (no hyphens).',
        ];
    }
}
