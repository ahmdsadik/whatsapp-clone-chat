<?php

namespace App\Http\Requests\Conversation;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConversationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'label' => ['nullable', 'string'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,jpg,png'],
        ];
    }
}
