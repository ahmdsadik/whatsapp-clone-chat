<?php

namespace App\DTO;

use App\Enums\MessageType;

class NewMessageDTO extends BaseDTO
{
    public function __construct(
        public string $to,
        public ?string $text,
        public MessageType $Type,
        public ?array $media,
    ) {}

    public static function fromFormRequest(
        string $to,
        ?string $text,
        MessageType $ype,
        ?array $media,
    ): self {
        return new self(
            $to,
            $text,
            $ype,
            $media
        );
    }
}
