<?php

namespace App\Enums;

use App\Traits\EnumHelpers;

enum ConversationType: int
{
    use EnumHelpers;

    case ONE_TO_ONE = 1;
    case ONE_TO_MANY = 2;

    public function label(): string
    {
        return match ($this) {
            self::ONE_TO_MANY => 'One to many',
            self::ONE_TO_ONE => 'One to one',
        };
    }
}
