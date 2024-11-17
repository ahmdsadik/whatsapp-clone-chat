<?php

namespace App\Http\Requests\UserProfile;

use App\Traits\ApiValidation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserAvatarRequest extends FormRequest
{
    use ApiValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'avatar' => ['required', 'image', 'mimes:jpg,png,jpeg,gif,svg'],
        ];
    }
}
