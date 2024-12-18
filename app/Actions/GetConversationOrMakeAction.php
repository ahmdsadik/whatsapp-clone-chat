<?php

namespace App\Actions;

use App\Enums\ConversationType;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Str;

class GetConversationOrMakeAction
{
    public function execute(string $to): Conversation
    {
        if (str_starts_with($to, 'user-')) {

            $other_user_mobile = Str::after($to, 'user-');
            $other_participant = User::where('mobile_number', $other_user_mobile)->get(['id'])->first();

            return $this->oneToOneConversation($other_participant);
        }

        return Conversation::findOrFail($to);
    }

    private function oneToOneConversation(User $other_participant): Conversation
    {
        $conversation = Conversation::Where('type', ConversationType::ONE_TO_ONE)
            ->whereHas('hasParticipants', function ($query) use ($other_participant) {
                $query->where('user_id', $other_participant->id);
            })
            ->whereHas('hasParticipants', function ($query) {
                $query->where('user_id', auth()->id());
            })->first();

        if ($conversation) {
            return $conversation;
        }

        $conversation = Conversation::create([
            'type' => ConversationType::ONE_TO_ONE,
        ]);

        $conversation->participants()->attach([$other_participant->id, auth()->id()]);

        return $conversation;
    }
}
