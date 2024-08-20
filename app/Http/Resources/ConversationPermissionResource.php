<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ConversationPermission */
class ConversationPermissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'edit_group_settings' => $this->edit_group_settings,
            'send_messages' => $this->send_messages,
            'add_other_members' => $this->add_other_members,

            'conversation_id' => $this->conversation_id,

            'conversation' => ConversationResource::make($this->whenLoaded('conversation')),
        ];
    }
}
