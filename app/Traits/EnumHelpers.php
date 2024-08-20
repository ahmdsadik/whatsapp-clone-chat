<?php

namespace App\Traits;

trait EnumHelpers
{
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function comment(): string
    {
        $comment = '';
        foreach (self::cases() as $case) {
            $comment .= "{$case->value} => {$case->label()}, ";
        }

        return rtrim($comment, ', ');
    }
}
