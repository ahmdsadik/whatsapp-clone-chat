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
    public function execute(Conversation $conversation, ParticipantRole $role, $participants_id): void
    {
        $this->checkUserPermissions($conversation);

        $this->checkParticipantsInConversation($conversation, $participants_id);

        $this->checkIfUserHasRoleToAssignThisRole($conversation, $role);
    }

    /**
     * @throws UserNotHavePermissionException
     */
    private function checkUserPermissions(Conversation $conversation): void
    {
        if (! $conversation->isAdmin(auth()->id())) {
            throw new UserNotHavePermissionException;
        }
    }

    /**
     * @throws ParticipantNotExistsInConversationException
     */
    private function checkParticipantsInConversation(Conversation $conversation, $participants_id): void
    {
        if (! $conversation->isParticipant([$participants_id])) {
            throw new ParticipantNotExistsInConversationException;
        }
    }

    /**
     * @throws ParticipantNotExistsInConversationException
     */
    private function checkIfUserHasRoleToAssignThisRole(Conversation $conversation, ParticipantRole $role): void
    {
        if (! $conversation->userCanAssignRole(auth()->id(), $role)) {
            throw new ParticipantNotExistsInConversationException;
        }
    }
}
