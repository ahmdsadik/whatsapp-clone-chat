<?php

namespace App\Actions;

use App\Events\Participant\AdminBySystemEvent;
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
        $oldParticipant = $conversation->oldestParticipant;

        if ($oldParticipant) {
            $conversation->makeAdmin($oldParticipant->user_id);

            // TODO:: Broadcast
            broadcast(new AdminBySystemEvent($conversation, $oldParticipant->user));
        }
    }
}
