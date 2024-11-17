<?php

namespace App\Observers;

use App\Models\Story;

class StoryObserver
{
    public function created(Story $story): void
    {
        // TODO:: Broadcast
    }

    public function deleted(Story $story): void
    {
        // TODO:: Broadcast
    }
}
