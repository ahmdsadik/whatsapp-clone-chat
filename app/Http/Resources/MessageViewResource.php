<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\MessageView */
class MessageViewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'viewed_at' => $this->viewd_at,

            'message_id' => $this->message_id,
            'user_id' => $this->user_id,

            'message' => new MessageResource($this->whenLoaded('message')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
