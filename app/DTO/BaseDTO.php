<?php

namespace App\DTO;

abstract class BaseDTO
{
    public function toArray(array $items = []): array
    {
        $vars = get_object_vars($this);

        if (! empty($items)) {
            return array_filter($vars, static fn ($key) => in_array($key, $items, true), ARRAY_FILTER_USE_KEY);
        }

        return $vars;
    }
}
