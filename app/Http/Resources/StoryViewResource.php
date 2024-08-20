<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\StoryView */
class StoryViewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'viewed_at' => $this->viewed_at,

            'story_id' => $this->story_id,
            'user_id' => $this->user_id,

            'story' => new StoryResource($this->whenLoaded('story')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
