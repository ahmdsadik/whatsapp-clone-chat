<?php

namespace App\Http\Requests\Story;

use App\Enums\StoryPrivacy;
use App\Models\User;
use App\Traits\ApiValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserStoryPrivacyRequest extends FormRequest
{
    use ApiValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'privacy' => ['required', Rule::enum(StoryPrivacy::class)],
            'contacts' => ['required_unless:privacy,' . StoryPrivacy::ALL_CONTACTS->value, 'array'],
            'contacts.*' => ['required', Rule::exists(User::class, 'mobile_number')],
        ];
    }

    public function messages(): array
    {
        return [
            'required_unless' => 'The :attribute field is required.',
        ];
    }
}
