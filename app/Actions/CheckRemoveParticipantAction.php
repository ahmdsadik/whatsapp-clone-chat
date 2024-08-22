<?php

namespace App\Actions;

use App\Exceptions\ParticipantNotExistsInConversationException;
use App\Exceptions\UserNotHavePermissionException;
use App\Models\Conversation;

class CheckRemoveParticipantAction
{
    /**
     * @throws UserNotHavePermissionException
     * @throws ParticipantNotExistsInConversationException
     */
    public function execute(Conversation $conversation, int $participant_id): void
    {

        $this->checkIfUserIsParticipant($conversation);

        if (!$conversation->isOwner(auth()->id())) {

            $this->checkIfUserHasPermission($conversation, $participant_id);

            $this->checkIfBothAreAdmins($conversation, $participant_id);
        }
    }

    /**
     * @throws UserNotHavePermissionException
     */
    private function checkIfUserHasPermission(Conversation $conversation, int $participant_id): void
    {
        if (!$conversation->isAdmin(auth()->id()) || $conversation->isOwner($participant_id)) {
            throw new UserNotHavePermissionException();
        }
    }

    /**
     * @throws UserNotHavePermissionException
     */
    private function checkIfBothAreAdmins(Conversation $conversation, int $participant_id): void
    {
        if ($conversation->areAdmins([$participant_id, auth()->id()])) {
            throw new UserNotHavePermissionException();
        }
    }

    /**
     * @throws ParticipantNotExistsInConversationException
     */
    private function checkIfUserIsParticipant(Conversation $conversation): void
    {
        if (!$conversation->isParticipant([auth()->id()])) {
            throw new ParticipantNotExistsInConversationException();
        }
    }
}
