<?php

namespace App\Events\Message;

use App\Http\Resources\UserResource;
use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageViewedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message, public User $viewer) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("Messages-{$this->message->user_id}"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message->id,
            'viewer' => UserResource::make($this->viewer),
        ];
    }

    public function broadcastAs(): string
    {
        return 'MessageViewedEvent';
    }
}
