<?php

namespace App\Services;

use App\Actions\ProcessConversationAvatarAction;
use App\DTO\ConversationDTO;
use App\Enums\ConversationPermission;
use App\Enums\ConversationType;
use App\Enums\ParticipantRole;
use App\Events\Conversation\ConversationInfoUpdatedEvent;
use App\Events\Conversation\NewConversationEvent;
use App\Events\Conversation\PermissionUpdatedEvent;
use App\Exceptions\ParticipantNotExistsInConversationException;
use App\Exceptions\UserNotHavePermissionException;
use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ConversationService
{

    public function userConversations(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ConversationResource::collection(auth()->user()->conversations()->with(['participants.media', 'media'])->get());
    }

    public function createConversation(ConversationDTO $conversationDTO)
    {
        $conversation = DB::transaction(function () use ($conversationDTO) {
            // Create a conversation
            $conversation = auth()->user()->conversations()->create($conversationDTO->toArray() + ['type' => ConversationType::ONE_TO_MANY]);

            // Set Permissions
            $conversation->permissions()->create($conversationDTO->permissions);

            if ($conversationDTO->avatar) {
                (new ProcessConversationAvatarAction())->handle($conversation);
                $conversation->loadMissing('media');
            }

            // Retrieve participants ids
            $users_ids = User::whereIn('mobile_number', $conversationDTO->participants)->pluck('id');

            // Format participants to be inserted
            $participants = $users_ids->map(fn($user_id) => ['user_id' => $user_id, 'role' => ParticipantRole::MEMBER->value, 'conversation_id' => $conversation->id]);
            $participants->push(['user_id' => auth()->user()->id, 'role' => ParticipantRole::OWNER->value, 'conversation_id' => $conversation->id]);

            $conversation->hasParticipants()->insert($participants->toArray());

            // TODO:: Broadcast Conversation creation
            broadcast(new NewConversationEvent($conversation));

            return $conversation;
        });


//        (new BroadcastConversationAction())->execute($conversation);

        return ConversationResource::make($conversation->loadMissing(['participants.media', 'permissions']));
    }

    public function updateConversation(ConversationDTO $conversationDTO, Conversation $conversation)
    {
        // TODO :: CHECK PERMISSION
        $conversation = DB::transaction(function () use ($conversationDTO, $conversation) {

            if (!$conversation->isAllowing(ConversationPermission::EDIT_GROUP_SETTINGS) || !$conversation->isAdmin(auth()->id())) {
                throw new UserNotHavePermissionException('Only admins can update this conversation');
            }

            $conversation->update($conversationDTO->toArray());

            if ($conversationDTO->avatar) {
                (new ProcessConversationAvatarAction())->handle($conversation);
            }

            // TODO:: Broadcast Conversation updates
            broadcast(new ConversationInfoUpdatedEvent($conversation));

            return $conversation;
        });


//        (new BroadcastConversationAction())->update($conversation);

        return ConversationResource::make($conversation);
    }

    public function updatePermissions(Conversation $conversation): void
    {
        if (!$conversation->isAdmin(auth()->id())) {
            throw new UserNotHavePermissionException('Only admins can update this conversation\'s Permissions');
        }

        $conversation->permissions()->update($conversation->permissions);

        broadcast(new PermissionUpdatedEvent($conversation));
    }

    public function deleteConversation(Conversation $conversation): void
    {
        if (!$conversation->isParticipant([auth()->id()])) {
            throw new ParticipantNotExistsInConversationException();
        }

        $conversation->forceDelete();

        // TODO:: Broadcast
    }
}
