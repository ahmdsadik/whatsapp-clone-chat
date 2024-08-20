<?php

namespace App\Http\Requests\Story;

use Illuminate\Foundation\Http\FormRequest;

class StoryViewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'story_id' => ['required', 'exists:stories'],
            'mobile_number' => ['required', 'exists:users,mobile_number'],
        ];
    }
}
