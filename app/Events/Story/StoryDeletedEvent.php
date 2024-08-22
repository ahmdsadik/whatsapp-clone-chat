<?php

namespace App\Events\Story;

use App\Models\Story;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StoryDeletedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Story $story, public User $user)
    {
        $this->user->load('registeredContacts.registeredUser.registeredContacts');
    }

    public function broadcastOn(): array
    {
        $contacts_channels = [];

        foreach ($this->user->registeredContacts as $contact) {
            if ($contact->registeredUser->registeredContacts->contains('id', $this->user->id)) {
                $contacts_channels[] = new PrivateChannel("stories-{$contact->registeredStory->id}");
            }
        }

        return $contacts_channels;
    }

    public function broadcastWith(): array
    {
        return [
            'owner_id' => $this->story->user_id,
            'story_id' => $this->story->id,
        ];
    }

    public function broadcastAs(): string
    {
        return 'StoryDeletedEvent';
    }
}
