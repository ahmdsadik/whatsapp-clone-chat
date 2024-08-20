<?php

namespace App\DTO;

use Illuminate\Foundation\Http\FormRequest;

class UserContactsDTO extends BaseDTO
{
    public function __construct(
        public array $contacts = [],
    )
    {
    }

    public static function fromFormRequest(FormRequest $request): self
    {
        return new self($request->validated('contacts'));
    }
}
