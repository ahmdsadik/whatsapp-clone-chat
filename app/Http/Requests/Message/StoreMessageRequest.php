<?php

namespace App\Http\Requests\Message;

use App\Enums\MessageType;
use App\Rules\ValidConversation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'to' => ['required', 'string', new ValidConversation],
            'text' => ['required_unless:media,null', 'string'],
            'type' => ['required', Rule::enum(MessageType::class)],
            'media' => ['nullable', 'array', 'min:1'],
            'media.*' => ['file']
        ];
    }
}
