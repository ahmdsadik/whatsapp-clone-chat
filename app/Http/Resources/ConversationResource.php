<?php

namespace App\Http\Resources;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Conversation */
class ConversationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label ?? '',
            'description' => $this->description ?? '',
            'type' => $this->type->label() ?? '',
            'createdBy' => $this->when($this->created_by, UserResource::make($this->whenLoaded('owner'))),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'avatar' => MediaResource::collection($this->whenLoaded('media')),
            'participants' => ConversationParticipantsResource::collection($this->whenLoaded('participants')),
        ];
    }
}
