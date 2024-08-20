<?php

namespace App\Enums;

use App\Traits\EnumHelpers;

enum StoryType: int
{
    use EnumHelpers;

    case TEXT = 1;
    case IMAGE = 2;
    case VIDEO = 3;
    case AUDIO = 4;

    public function label(): string
    {
        return match ($this) {
            self::TEXT => 'TEXT',
            self::IMAGE => 'IMAGE',
            self::VIDEO => 'VIDEO',
            self::AUDIO => 'AUDIO',
        };
    }
}
