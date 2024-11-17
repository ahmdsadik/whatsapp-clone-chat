<?php

namespace App\Http\Requests\OTP;

use App\Traits\ApiValidation;
use Illuminate\Foundation\Http\FormRequest;

class ResendOTPRequest extends FormRequest
{
    use ApiValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mobile_number' => ['required', 'string'],
        ];
    }
}
