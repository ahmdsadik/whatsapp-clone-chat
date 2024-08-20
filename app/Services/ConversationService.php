<?php

namespace App\Services;

use App\Actions\BroadcastConversationAction;
use App\Actions\ProcessConversationAvatarAction;
use App\DTO\ConversationDTO;
use App\Enums\ConversationType;
use App\Enums\ParticipantRole;
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
//            $conversation = auth()->user()->conversations()->create($request->validated() + ['type' => ConversationType::ONE_TO_MANY]);

            // Create a conversation
            $conversation = Conversation::create(
                [
                    'label' => $conversationDTO->label,
                    'type' => ConversationType::ONE_TO_MANY,
                    'created_by' => auth()->id()
                ]
            );

            $conversation->permissions()->create($conversationDTO->permissions);

            // Retrieve participants ids
            $users_ids = User::whereIn('mobile_number', $conversationDTO->participants)->pluck('id');

            // Format participants to be inserted
            $participants = $users_ids->map(fn($user_id) => ['user_id' => $user_id, 'role' => ParticipantRole::MEMBER->value, 'conversation_id' => $conversation->id]);
            $participants->push(['user_id' => auth()->user()->id, 'role' => ParticipantRole::OWNER->value, 'conversation_id' => $conversation->id]);

            $conversation->hasParticipants()->insert($participants->toArray());


            if ($conversationDTO->avatar) {
                (new ProcessConversationAvatarAction())->handle($conversation);
                $conversation->loadMissing('media');
            }

            return $conversation;
        });

        // TODO:: Broadcast Conversation creation
//        (new BroadcastConversationAction())->execute($conversation);

        return ConversationResource::make($conversation->loadMissing(['participants.media','permissions']));
    }

    public function updateConversation(ConversationDTO $conversationDTO, Conversation $conversation)
    {
        // TODO :: CHECK PERMISSION
        $conversation = DB::transaction(function () use ($conversationDTO, $conversation) {

            $conversation->update($conversationDTO->toArray());

            if ($conversationDTO->avatar) {
                (new ProcessConversationAvatarAction())->handle($conversation);
            }

            return $conversation;
        });

        // TODO:: Broadcast Conversation updates
        (new BroadcastConversationAction())->update($conversation);

        return ConversationResource::make($conversation);
    }

    public function deleteConversation(Conversation $conversation): void
    {
        $conversation->forceDelete();

        // TODO:: Broadcast
    }
}
