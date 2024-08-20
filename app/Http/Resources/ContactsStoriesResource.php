<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Contact */
class ContactsStoriesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'mobile_number' => $this->mobile_number,

            'registeredUser' => UserResource::make($this->whenLoaded('registeredUser')),

            'stories' => StoryResource::collection($this->registeredUser->authorizedStories),
        ];
    }
}
