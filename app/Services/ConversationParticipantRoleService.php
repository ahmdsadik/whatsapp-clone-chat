<?php

namespace App\Services;

use App\Actions\CheckUpdateParticipantRoleAction;
use App\DTO\ParticipantRoleDTO;
use App\Events\Participant\ParticipantNewRoleEvent;
use App\Exceptions\ParticipantNotExistsInConversationException;
use App\Exceptions\UserNotHavePermissionException;
use App\Models\Conversation;
use App\Models\User;

readonly class ConversationParticipantRoleService
{
    public function __construct(
        private CheckUpdateParticipantRoleAction $action,
    ) {}

    /**
     * Update participant role in conversation
     *
     * @throws ParticipantNotExistsInConversationException
     * @throws UserNotHavePermissionException
     */
    public function updateParticipantRole(ParticipantRoleDTO $participantRoleDTO, Conversation $conversation): void
    {
        $participant = User::firstWhere('mobile_number', $participantRoleDTO->mobile_number);

        // Check User permission and participant
        $this->action->execute($conversation, $participantRoleDTO->role, $participant->id);

        // Update participant role
        $conversation->participants()->updateExistingPivot($participant->id, ['role' => $participantRoleDTO->role->value]);

        broadcast(new ParticipantNewRoleEvent($conversation, $participant, auth()->user(), $participantRoleDTO->role->label()));
    }
}
