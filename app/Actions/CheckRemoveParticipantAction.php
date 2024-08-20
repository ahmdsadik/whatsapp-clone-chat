<?php

namespace App\Actions;

use App\Exceptions\UserNotHavePermissionException;
use App\Models\Conversation;

class CheckRemoveParticipantAction
{
    /**
     * @throws UserNotHavePermissionException
     */
    public function execute(Conversation $conversation, int $participant_id): void
    {
        if ($conversation->created_by !== auth()->id()) {
            $this->checkIfUserHasPermission($conversation, $participant_id);

            $this->checkIfBothAreAdmins($conversation, $participant_id);
        }
    }

    /**
     * @throws UserNotHavePermissionException
     */
    private function checkIfUserHasPermission(Conversation $conversation, int $participant_id): void
    {
        if (!$conversation->isAdmin(auth()->id()) || $conversation->created_by === $participant_id) {
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
}
