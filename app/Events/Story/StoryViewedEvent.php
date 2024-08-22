<?php

namespace App\Events\Story;

use App\Http\Resources\UserResource;
use App\Models\Story;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StoryViewedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Story $story, public User $viewer)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("stories-{$this->story->user_id}")
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'story_id' => $this->story->id,
            'viewer' => UserResource::make($this->viewer),
        ];
    }

    public function broadcastAs(): string
    {
        return 'StoryViewedEvent';
    }
}
