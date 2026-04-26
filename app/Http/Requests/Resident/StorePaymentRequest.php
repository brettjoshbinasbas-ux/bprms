<?php

namespace App\Http\Requests\Resident;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'application_id' => ['required', 'exists:applications,application_id'],
            'card_number' => ['required', 'string', 'regex:/^[0-9\s]{16,19}$/'],
            'expiry_month' => ['required', 'numeric', 'between:1,12'],
            'expiry_year' => ['required', 'numeric', 'min:' . date('Y'), 'max:' . (date('Y') + 10)],
        ];
    }

    public function messages(): array
    {
        return [
            'card_number.regex' => 'Card number must be 16 digits.',
        ];
    }
}
