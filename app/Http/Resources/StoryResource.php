<?php

namespace App\Http\Resources;

use App\Enums\StoryType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Story */
class StoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => strtolower($this->type->name),
            'duration' => $this->when($this->type === StoryType::VIDEO, $this->duration ?? ''),
            'text' => $this->text ?? '',
            'media' => MediaResource::collection($this->whenLoaded('media')),
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
