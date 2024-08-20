<?php

namespace App\Services;

use App\Actions\CheckUpdateParticipantRoleAction;
use App\DTO\ParticipantRoleDTO;
use App\Exceptions\ParticipantNotExistsInConversationException;
use App\Exceptions\UserNotHavePermissionException;
use App\Models\Conversation;
use App\Models\User;

class ConversationParticipantRoleService
{
    /**
     * @throws ParticipantNotExistsInConversationException
     * @throws UserNotHavePermissionException
     */
    public function updateParticipantRole(ParticipantRoleDTO $participantRoleDTO, Conversation $conversation): void
    {
        $participant = User::where('mobile_number', $participantRoleDTO->mobile_number)->get(['id', 'mobile_number'])->first();

        // Check User permission and participants
        (new CheckUpdateParticipantRoleAction())->handle($conversation, $participantRoleDTO->role, [$participant->id]);

        $conversation->participants()->updateExistingPivot($participant->id, ['role' => $participantRoleDTO->role->value]);
    }
}
