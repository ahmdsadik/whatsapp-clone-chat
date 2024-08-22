<?php

namespace App\Events\Conversation;

use App\Models\Conversation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PermissionUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Conversation $conversation)
    {
        $this->conversation->loadMissing('permissions');
    }

    public function broadcastOn(): array
    {
        $participants_channels = [];

        foreach ($this->conversation->hasParticipants as $participant) {
            $participants_channels[] = new PrivateChannel("Conversations-{$participant->user_id}");
        }

        return $participants_channels;
    }

    public function broadcastWith(): array
    {
        return [
            'permissions' => $this->conversation->permissions,
        ];
    }

    public function broadcastAs(): string
    {
        return 'PermissionUpdatedEvent';
    }
}
