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
            'user_id' => ['required', 'exists:users'],
            'viewed_at' => ['required', 'date'],
        ];
    }
}
