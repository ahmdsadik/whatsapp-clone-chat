<?php

namespace App\DTO;

use App\Enums\MessageType;
use Illuminate\Foundation\Http\FormRequest;

class NewMessageDTO extends BaseDTO
{
    public function __construct(
        public string      $to,
        public ?string     $text,
        public MessageType $Type,
        public ?array      $media,
    )
    {
    }

    public static function fromFormRequest(FormRequest $request): self
    {
        return new self(
            $request->validated('to'),
            $request->validated('text'),
            MessageType::from($request->validated('type')),
            $request->validated('media'),
        );
    }
}
