<?php

namespace App\Events\Message;

use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Conversation $conversation, public Message $message, public User $user)
    {
        $this->conversation->loadMissing('hasParticipants');
    }

    public function broadcastOn(): array
    {
        $participants_channels = [];

        foreach ($this->conversation->hasParticipants as $participant) {
            $participants_channels[] = new PrivateChannel("Messages-{$participant->user_id}");
        }

        return $participants_channels;
    }

    public function broadcastWith(): array
    {
        return [
            'conversation' => $this->conversation->id,
            'message' => MessageResource::make($this->message),
            'sender' => MessageResource::make($this->user),
        ];
    }

    public function broadcastAs(): string
    {
        return 'NewMessageEvent';
    }
}
