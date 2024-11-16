<?php

namespace App\DTO;

class ConversationDTO extends BaseDTO
{
    public function __construct(
        public ?string $label,
        public ?string $description,
        public $avatar,
        public ?array $participants,
        public ?array $permissions
    ) {}

    public static function fromFormRequest(
        ?string $label = null,
        ?string $description = null,
        $avatar = null,
        ?array $participants = null,
        ?array $permissions = null
    ): self {
        return new self(
            $label,
            $description,
            $avatar,
            $participants,
            $permissions
        );
    }

}
