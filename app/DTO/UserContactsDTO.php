<?php

namespace App\DTO;

class UserContactsDTO extends BaseDTO
{
    public function __construct(
        public array $contacts = [],
    ) {}

    public static function fromFormRequest(array $contacts = []): self
    {
        return new self(
            $contacts
        );
    }
}
