<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name ?? '',
            'about' => $this->about ?? '',
            'mobile_number' => $this->mobile_number,
            'avatar' => $this->avatar ?? ''
        ];
    }
}
