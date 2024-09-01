<?php

namespace App\Http\Requests\Conversation;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'edit_group_settings' => ['required', 'bool'],
            'send_messages' => ['required', 'bool'],
            'add_other_members' => ['required', 'bool'],
        ];
    }
}
