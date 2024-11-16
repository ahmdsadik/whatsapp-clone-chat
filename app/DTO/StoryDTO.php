<?php

namespace App\DTO;

use App\Enums\StoryPrivacy;
use App\Enums\StoryType;
use Illuminate\Foundation\Http\FormRequest;

class StoryDTO extends BaseDTO
{
    public function __construct(
        public StoryType $type,
        public ?string $text,
        public ?string $duration,
        public StoryPrivacy $privacy,
        public $media,
    ) {
    }

    public static function fromFormRequest(
        StoryType $type,
        ?string $text,
        ?string $duration,
        StoryPrivacy $privacy,
        $media,
    ): self {
        return new self(
            $type,
            $text,
            $duration,
            $privacy,
            $media
        );
    }
}
