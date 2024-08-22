<?php

namespace App\Http\Requests\Conversation;

use App\Enums\ConversationPermission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'permissions' => ['required', 'array', 'size:3'],
            'permissions.*' => ['required', Rule::enum(ConversationPermission::class)],
            'permissions.edit_group_settings' => ['required', 'bool'],
            'permissions.send_messages' => ['required', 'bool'],
            'permissions.add_other_members' => ['required', 'bool'],
        ];
    }
}
