<?php

namespace App\DTO;

use Illuminate\Foundation\Http\FormRequest;

class ConversationDTO extends BaseDTO
{
    public function __construct(
        public string $label,
        public ?string $description,
        public  $avatar,
        public array  $participants,
        public array  $permissions
    )
    {
    }

    public static function fromFormRequest(FormRequest $formRequest): self
    {
        return new self(
            $formRequest->validated('label'),
            $formRequest->validated('description'),
            $formRequest->validated('avatar'),
            $formRequest->validated('participants'),
            $formRequest->validated('permissions')
        );
    }
}
