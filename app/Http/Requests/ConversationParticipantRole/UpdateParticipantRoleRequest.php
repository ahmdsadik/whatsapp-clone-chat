<?php

namespace App\Http\Requests\ConversationParticipantRole;

use App\Enums\ParticipantRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateParticipantRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'participant' => ['required', 'array', 'min:1'],
            'participant.mobile_number' => ['required', 'exists:users,mobile_number'],
            'participant.role' => ['required', Rule::in([ParticipantRole::ADMIN->value, ParticipantRole::MEMBER->value])],
        ];
    }
}
