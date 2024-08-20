<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \Spatie\MediaLibrary\MediaCollections\Models\Media */
class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'file_name' => $this->file_name,
            'mime_type' => $this->mime_type,
            'type' => $this->type,
            'extension' => $this->extension,
            'url' => $this->original_url,
            'size' => number_format($this->size / 1000_000, 2) . ' MB',
        ];
    }
}
