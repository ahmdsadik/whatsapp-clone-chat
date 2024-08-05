<?php

namespace App\Enums;

use App\Traits\EnumHelpers;

enum StoryPrivacy: int
{
    use EnumHelpers;

    case ALL_CONTACTS = 1;
    case ALL_CONTACTS_EXCEPT = 2;
    case ONLY_CONTACTS = 3;
}
