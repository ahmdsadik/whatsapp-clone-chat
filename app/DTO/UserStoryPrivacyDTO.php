<?php

namespace App\DTO;

use App\Enums\StoryPrivacy;
use Illuminate\Foundation\Http\FormRequest;

class UserStoryPrivacyDTO extends BaseDTO
{
    public function __construct(
        public StoryPrivacy $privacy,
        public array        $contacts
    )
    {
    }

    public static function fromFormRequest(FormRequest $request): self
    {
        return new self(
            StoryPrivacy::from($request->validated('privacy')),
            $request->validated('contacts')
        );
    }
}
