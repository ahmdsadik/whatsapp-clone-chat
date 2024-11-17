<?php

namespace App\Rules;

use App\Models\Conversation;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class ValidConversation implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (str_starts_with($value, 'user-')) {
            $mobile_number = Str::after($value, 'user-');

            if (! User::where('mobile_number', $mobile_number)->exists()) {
                $fail($attribute.' is not a valid User.');
            }

            return;
        }

        $conversation = Conversation::find($value);

        if (! $conversation) {
            $fail($attribute.' is not a valid conversation.');

            return;
        }

        if (! $conversation->isParticipant([auth()->id()])) {
            $fail('User is not a participant in this conversation.');
        }
    }
}
