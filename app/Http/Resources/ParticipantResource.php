<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Participant */
class ParticipantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user' => UserResource::make($this),
            'role' => $this->info->role->label(),
            'join_at' => $this->info->join_at->diffForHumans(),
        ];
    }
}
