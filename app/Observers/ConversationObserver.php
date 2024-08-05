<?php

namespace App\Observers;

use App\Enums\ConversationType;
use App\Models\Conversation;
use Illuminate\Support\Str;

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
