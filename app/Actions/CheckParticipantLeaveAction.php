<?php

namespace App\Actions;

use App\Exceptions\ParticipantNotExistsInConversationException;
use App\Models\Conversation;

class CheckParticipantLeaveAction
{
    /**
     * @throws ParticipantNotExistsInConversationException
     */
    public function execute(Conversation $conversation): void
    {
        if (!$conversation->isParticipant([auth()->id()])) {
            throw new ParticipantNotExistsInConversationException();
        }
    }
}
