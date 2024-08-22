<?php

namespace App\Actions;

use App\Enums\ConversationPermission;
use App\Exceptions\UserNotHavePermissionException;
use App\Models\Conversation;

class CheckAddParticipantPermissionAction
{
    /**
     * @throws UserNotHavePermissionException
     */
    public function execute(Conversation $conversation): void
    {
        if (!$conversation->isAllowing(ConversationPermission::ADD_OTHER_PARTICIPANTS) || !$conversation->isAdmin(auth()->id())) {
            throw new UserNotHavePermissionException('Admins only can add others participants.');
        }
    }
}
