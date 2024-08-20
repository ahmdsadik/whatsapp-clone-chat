<?php

namespace App\Http\Requests\Conversation;

use App\Enums\ConversationPermission;
use App\Traits\ApiValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateConversationsRequest extends FormRequest
{
    use ApiValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:254'],
            'avatar' => ['nullable', 'image'],
            'permissions' => ['required', 'array', 'size:3'],
            'permissions.*' => ['required', Rule::enum(ConversationPermission::class)],
            'permissions.edit_group_settings' => ['required', 'bool'],
            'permissions.send_messages' => ['required', 'bool'],
            'permissions.add_other_members' => ['required', 'bool'],
            'participants' => ['required', 'array', 'min:1'],
            'participants.*' => ['required', 'exists:users,mobile_number'],
        ];
    }
}
