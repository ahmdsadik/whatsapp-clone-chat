<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Contact */
class ContactResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'mobile_number' => $this->mobile_number,

            'user' => $this->when($this->registeredUser !== null, UserResource::make($this->whenLoaded('registeredUser'))),
            //            'registeredUser' => UserResource::make($this->whenLoaded('registeredUser')),
        ];
    }
}
