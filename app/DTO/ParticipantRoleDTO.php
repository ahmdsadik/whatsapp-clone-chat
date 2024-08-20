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
    )
    {
    }


    public static function fromFormRequest(FormRequest $request): self
    {
        return new self(
            $request->validated('participant'),
            $request->validated('participant')['mobile_number'],
            ParticipantRole::from($request->validated('participant')['role']),
        );
    }
}
