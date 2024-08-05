<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Conversation */
class ConversationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label ?? '',
            'type' => $this->type ?? '',
            'createdBy' => new UserResource($this->whenLoaded('owner')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
