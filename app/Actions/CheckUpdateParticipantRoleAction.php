<?php

namespace App\Actions;

use App\Enums\ParticipantRole;
use App\Exceptions\ParticipantNotExistsInConversationException;
use App\Exceptions\UserNotHavePermissionException;
use App\Models\Conversation;

class CheckUpdateParticipantRoleAction
{
    /**
     * @throws ParticipantNotExistsInConversationException
     * @throws UserNotHavePermissionException
     */
    public function handle(Conversation $conversation, ParticipantRole $role, array $participants_ids): void
    {
        $this->checkUserPermissions($conversation);

        $this->checkParticipantsInConversation($conversation, $participants_ids);

        $this->checkIfUserHasRoleToAssignThisRole($conversation, $role);
    }

    /**
     * @throws UserNotHavePermissionException
     */
    private function checkUserPermissions(Conversation $conversation): void
    {
        if (!$conversation->isAdmin(auth()->id())) {
            throw new UserNotHavePermissionException();
        }
    }

    /**
     * @throws ParticipantNotExistsInConversationException
     */
    private function checkParticipantsInConversation(Conversation $conversation, array $participants_Ids): void
    {
        if (!$conversation->isParticipant($participants_Ids)) {
            throw new ParticipantNotExistsInConversationException();
        }
    }

    /**
     * @throws ParticipantNotExistsInConversationException
     */
    private function checkIfUserHasRoleToAssignThisRole(Conversation $conversation, ParticipantRole $role): void
    {
        if (!$conversation->userCanAssignRole(auth()->id(), $role)) {
            throw new ParticipantNotExistsInConversationException();
        }
    }
}
