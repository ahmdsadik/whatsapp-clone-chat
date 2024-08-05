<?php

namespace App\Http\Requests\OTP;

use App\Traits\ApiValidation;
use Illuminate\Foundation\Http\FormRequest;

class VerifyOTPRequest extends FormRequest
{
    use ApiValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'otp' => ['required', 'string'],
            'mobile_number' => ['required', 'string'],
            'fcm_token' => ['required', 'string']
        ];
    }
}
