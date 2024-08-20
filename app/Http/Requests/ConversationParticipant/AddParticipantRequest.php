<?php

namespace App\Http\Requests\ConversationParticipant;

use App\Traits\ApiValidation;
use Illuminate\Foundation\Http\FormRequest;

class AddParticipantRequest extends FormRequest
{
    use  ApiValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'participants' => ['required', 'array', 'min:1'],
            'participants.*' => ['required', 'exists:users,mobile_number']
        ];
    }
}
