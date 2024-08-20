<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidChannelRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $timestamp = explode('_', $value)[1] ?? '';

        if (!Carbon::now()->lessThanOrEqualTo(Carbon::createFromTimestamp($timestamp))) {
            $fail('Invalid channel');
        }
    }
}
