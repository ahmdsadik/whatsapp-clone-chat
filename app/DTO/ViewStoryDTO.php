<?php

namespace App\DTO;

use App\Models\Story;
use Illuminate\Foundation\Http\FormRequest;

class ViewStoryDTO extends BaseDTO
{
    public function __construct(
        public Story  $story,
        public string $mobile_number
    )
    {
    }

    public static function fromFormRequest(FormRequest $formRequest): self
    {
        return new self(
            $formRequest->validated('story_id'),
            $formRequest->validated('mobile_number')
        );
    }
}
