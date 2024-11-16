<?php

namespace App\Traits;

trait EnumHelpers
{
    /**
     * Transform the enum to array
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Transform the enum to string comma separated
     *
     * @return string
     */
    public static function comment(): string
    {
        $comment = '';
        foreach (self::cases() as $case) {
            $comment .= "{$case->value} => {$case->label()}, ";
        }

        return rtrim($comment, ', ');
    }
}
