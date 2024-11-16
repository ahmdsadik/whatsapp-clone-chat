<?php

namespace App\DTO;

use App\DTO\BaseDTO;

class UserDTO extends BaseDTO
{
    public function __construct(
        public ?string $name,
        public ?string $mobile_number,
        public ?string $about,
    ) {}

    public static function fromFormRequest(string $name = '', string $mobile_number = '', string $about = ''): self
    {
        return new self(
            $name,
            $mobile_number,
            $about
        );
    }
}
