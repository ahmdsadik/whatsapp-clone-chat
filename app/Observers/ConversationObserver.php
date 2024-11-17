<?php

namespace App\Observers;

use App\Models\Conversation;

class ConversationObserver
{
    public function created(Conversation $conversation): void
    {
        // TODO:: Send Conversations notification
    }

    public function updated(Conversation $conversation): void
    {
        // TODO:: Send Conversations notification
    }

    public function deleted(Conversation $conversation): void
    {
        // TODO:: Send Conversations notification
    }
}
