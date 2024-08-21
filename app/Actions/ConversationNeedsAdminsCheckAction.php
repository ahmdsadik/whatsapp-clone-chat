<?php

namespace App\Actions;

use App\Models\Conversation;

class ConversationNeedsAdminsCheckAction
{
    public function execute(Conversation $conversation): void
    {
        if ($conversation->created_by === auth()->id()) {

            if (!$conversation->hasAdmins()) {

                $this->makeOldestParticipantAdmin($conversation);

            }
        }
    }

    private function makeOldestParticipantAdmin(Conversation $conversation): void
    {
        $oldParticipants = $conversation->oldestParticipant;

        if ($oldParticipants) {
            $conversation->makeAdmin($oldParticipants->user_id);
        }
    }
}
