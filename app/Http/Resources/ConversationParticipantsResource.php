<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Participant */
class ConversationParticipantsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name ?? '',
            'about' => $this->about ?? '',
            'mobile_number' => $this->mobile_number,
            'avatar' => MediaResource::collection($this->whenLoaded('media')),

            'role' => $this->info?->role->label() ?? '',
            'join_at' => $this->info?->join_at->format('d-m-Y H:i:s') ?? '',
        ];
    }
}
