<?php

namespace App\Http\Requests\ConversationParticipant;

use App\Traits\ApiValidation;
use Illuminate\Foundation\Http\FormRequest;

class RemoveParticipantRequest extends FormRequest
{
    use  ApiValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'participant' => ['required', 'exists:users,mobile_number']
        ];
    }
}
