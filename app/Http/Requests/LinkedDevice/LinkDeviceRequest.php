<?php

namespace App\Http\Requests\LinkedDevice;

use App\Traits\ApiValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class LinkDeviceRequest extends FormRequest
{
    use ApiValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_name' => ['required', 'string', 'max:254'],
            'channel_name' => ['required', 'string', 'max:48'],
        ];
    }
}
