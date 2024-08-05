<?php

namespace App\Enums;

use App\Traits\EnumHelpers;

enum ParticipantRole: int
{
    use EnumHelpers;

    case OWNER = 1;
    case ADMIN = 2;
    case MEMBER = 3;

    public function label(): string
    {
        return match ($this) {
            self::OWNER => 'Owner',
            self::ADMIN => 'Admin',
            self::MEMBER => 'Member',
        };
    }
}
