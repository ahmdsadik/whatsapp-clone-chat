<?php

namespace App\DTO;

use App\Enums\StoryPrivacy;
use Illuminate\Foundation\Http\FormRequest;

class UserStoryPrivacyDTO extends BaseDTO
{
    public function __construct(
        public StoryPrivacy $privacy,
        public array $contacts
    ) {}

    public static function fromFormRequest(
        StoryPrivacy $privacy,
        array $contacts
    ): self {
        return new self(
            $privacy,
            $contacts
        );
    }
}
