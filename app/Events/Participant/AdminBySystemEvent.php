<?php

namespace App\Events\Participant;

use App\Http\Resources\UserResource;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminBySystemEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Conversation $conversation, public User $participant)
    {
        $this->conversation->loadMissing('hasParticipants');
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
            'conversation' => $this->conversation->id,
            'participant' => UserResource::make($this->participant),
            'role' => 'Admin',
        ];
    }

    public function broadcastAs(): string
    {
        return 'ParticipantNewRoleEvent';
    }
}
