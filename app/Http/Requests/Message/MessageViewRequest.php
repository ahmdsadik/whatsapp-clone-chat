<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class MessageViewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message_id' => ['required', 'exists:messages'],
            'user_id' => ['required', 'exists:users'],
            'viewed_at' => ['required', 'date'],
        ];
    }
}
