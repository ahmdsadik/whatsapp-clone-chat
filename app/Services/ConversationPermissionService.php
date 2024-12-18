<?php

namespace App\Services;

use App\DTO\ConversationPermissionDTO;
use App\Events\Conversation\PermissionUpdatedEvent;
use App\Exceptions\UserNotHavePermissionException;
use App\Models\Conversation;
use App\Models\ConversationPermission;

class ConversationPermissionService
{
    /**
     * Get conversation permissions
     */
    public function permissions(Conversation $conversation): ?ConversationPermission
    {
        return $conversation->permissions;
    }

    /**
     * Update conversation permissions
     *
     * @throws UserNotHavePermissionException
     */
    public function updatePermissions(Conversation $conversation, ConversationPermissionDTO $permissions): void
    {
        if (! $conversation->isAdmin(auth()->id())) {
            throw new UserNotHavePermissionException('Only admins can update this conversation\'s Permissions');
        }

        $conversation->permissions()->update($permissions->toArray());

        broadcast(new PermissionUpdatedEvent($conversation));
    }
}
