<?php

namespace App\Enums;

use App\Traits\EnumHelpers;

enum StoryPrivacy: int
{
    use EnumHelpers;

    case ALL_CONTACTS = 1;
    case ALL_CONTACTS_EXCEPT = 2;
    case ONLY_CONTACTS = 3;

    public function label(): string
    {
        return match ($this) {
            self::ALL_CONTACTS => 'All Contacts',
            self::ALL_CONTACTS_EXCEPT => 'All Contacts Except',
            self::ONLY_CONTACTS => 'Only Contacts',
        };
    }
}
