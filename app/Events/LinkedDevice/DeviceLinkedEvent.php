<?php

namespace App\Events\LinkedDevice;

use App\Http\Resources\UserResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeviceLinkedEvent implements ShouldBroadcast, ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public string $linkChannel, public string $token) {}

    public function broadcastOn(): array
    {
        return [
            new Channel($this->linkChannel),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'user' => UserResource::make(auth()->user()),
            'token' => $this->token,
        ];
    }

    public function broadcastAs(): string
    {
        return 'loginEvent';
    }
}
