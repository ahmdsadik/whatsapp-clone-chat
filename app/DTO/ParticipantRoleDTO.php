<?php

namespace App\DTO;

use App\Enums\ParticipantRole;
use Illuminate\Foundation\Http\FormRequest;

class ParticipantRoleDTO extends BaseDTO
{
    public function __construct(
        public array $participant,
        public string $mobile_number,
        public ParticipantRole $role,
    ) {}


    public static function fromFormRequest(
        array $participant,
        string $mobile_number,
        ParticipantRole $role,
    ): self {
        return new self(
            $participant,
            $mobile_number,
            $role,
        );
    }
}
