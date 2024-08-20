<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\LinkedDevice */
class LinkedDeviceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'device_name' => $this->device_name,
            'last_active' => $this->token->last_used_at?->diffForHumans() ?? '',
            'linked_at' => $this->linked_at->format('Y-m-d H:i:s'),
        ];
    }
}
