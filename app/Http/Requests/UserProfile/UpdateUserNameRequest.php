<?php

namespace App\Http\Requests\UserProfile;

use App\Traits\ApiValidation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserNameRequest extends FormRequest
{
    use ApiValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:254'],
            'about' => ['nullable', 'string', 'max:254'],
        ];
    }
}
