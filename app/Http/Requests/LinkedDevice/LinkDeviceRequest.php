<?php

namespace App\Http\Requests\LinkedDevice;

use App\Rules\ValidChannelRule;
use App\Traits\ApiValidation;
use Illuminate\Foundation\Http\FormRequest;

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
            'channel_name' => ['required', 'string', 'max:48', new ValidChannelRule],
        ];
    }
}
