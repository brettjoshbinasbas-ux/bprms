<?php

namespace App\Http\Requests\Resident;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'premises_id' => ['required', 'exists:premises,premises_id'],
            'intended_business_type' => ['required', 'string', 'max:100'],
            'financial_position' => ['required', 'numeric', 'min:0'],
            // Documents
            'ic_copy' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'applicant_photo' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'spouse_photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'supporting_document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'ic_copy.required' => 'A copy of your IC is required.',
            'applicant_photo.required' => 'An applicant photo is required.',
        ];
    }
}
