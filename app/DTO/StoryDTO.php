<?php

namespace App\DTO;

use App\Enums\StoryPrivacy;
use App\Enums\StoryType;
use Illuminate\Foundation\Http\FormRequest;

class StoryDTO extends BaseDTO
{
    public function __construct(
        public StoryType    $type,
        public ?string       $text,
        public ?string       $duration,
        public StoryPrivacy $privacy,
        public              $media,
    )
    {
    }

    public static function fromFormRequest(FormRequest $request): self
    {
        return new self(
            StoryType::from($request->validated('type')),
            $request->validated('text'),
            $request->validated('duration'),
            StoryPrivacy::from($request->validated('privacy')),
            $request->validated('media'),
        );
    }
}
