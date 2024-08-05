<?php

namespace App\Http\Requests\LinkedDevice;

use App\Traits\ApiValidation;
use Illuminate\Foundation\Http\FormRequest;

class UnlinkDeviceRequest extends FormRequest
{
    use ApiValidation;

    public function authorize(): bool
    {
        return $this->linked_device->user_id === $this->user()->id;
    }

    public function rules(): array
    {
        return [];
    }
}
