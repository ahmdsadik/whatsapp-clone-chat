<?php

namespace App\Services;

use App\Actions\GetConversationOrMakeAction;
use App\Actions\ProcessMessageMediaAction;
use App\DTO\NewMessageDTO;
use App\Enums\ConversationPermission;
use App\Events\Message\MessageDeletedEvent;
use App\Events\Message\MessageViewedEvent;
use App\Events\Message\NewMessageEvent;
use App\Exceptions\UserNotHavePermissionException;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class MessageService
{
    public function conversationMessages(Conversation $conversation): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $conversationMessages = $conversation->load(['messages.media', 'messages.user.media']);

        return MessageResource::collection($conversationMessages->messages);
    }

    public function saveMessage(NewMessageDTO $messageDTO)
    {

        $message = DB::transaction(function () use ($messageDTO) {
            $conversation = (new GetConversationOrMakeAction())->execute($messageDTO->to);

            if (!$conversation->isAllowing(ConversationPermission::SEND_MESSAGES)) {
                throw new UserNotHavePermissionException("Sending Messages is not allowed");
            }

            $message = $conversation->messages()->create([
                'text' => $messageDTO->text,
                'type' => $messageDTO->Type,
                'user_id' => auth()->id()
            ]);

            if ($messageDTO->media) {
                (new ProcessMessageMediaAction())->execute($message);
                $message->load('media');
            }

            $conversation->update(['last_message_id' => $message->id]);

            broadcast(new NewMessageEvent($conversation, $message, auth()->user()));

            return $message;
        });

        return MessageResource::make($message);
    }

    public function deleteMessage(Message $message): void
    {
        if ($message->user_id !== auth()->id()) {
            throw new UserNotHavePermissionException('User Cannot Delete this Message');
        }

        DB::transaction(function () use ($message) {
            $message->delete();

            broadcast(new MessageDeletedEvent($message->conversation, $message));

        });
    }

    public function viewMessage(Message $message): void
    {
        if ($message->user_id !== auth()->id()) {
            throw new UserNotHavePermissionException('User Cannot view his Message');
        }

        $message->views()->attach(auth()->id());

        broadcast(new MessageViewedEvent($message, auth()->user()));
    }
}
