<?php

namespace App\Http\Requests\Contact;

use App\Traits\ApiValidation;
use Illuminate\Foundation\Http\FormRequest;

class InsertUserContactRequest extends FormRequest
{
    use ApiValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'contacts' => ['required', 'array'],
            'contacts.*.name' => ['nullable', 'string'],
            'contacts.*.mobile_number' => ['required', 'string'],
        ];
    }
}
