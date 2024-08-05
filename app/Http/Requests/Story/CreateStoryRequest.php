<?php

namespace App\Http\Requests\Story;

use App\Enums\StoryPrivacy;
use App\Enums\StoryType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateStoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(StoryType::class)],
            'text' => ['required_without:media', 'string'],
            'duration' => ['nullable'],
            'privacy' => ['required', Rule::enum(StoryPrivacy::class)],
            'media' => ['required_without:text', 'file'],
        ];
    }
}
