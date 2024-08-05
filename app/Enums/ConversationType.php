<?php

namespace App\Enums;

use App\Traits\EnumHelpers;

enum ConversationType: int
{
    use EnumHelpers;

    case ONE_TO_ONE = 1;
    case  ONE_TO_MANY = 2;
}
