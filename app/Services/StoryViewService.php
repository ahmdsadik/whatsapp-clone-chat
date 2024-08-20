<?php

namespace App\Services;

use App\Models\Story;

class StoryViewService
{
    public function viewStory(Story $story): void
    {
        $story->viewers()->attach(auth()->id());
    }
}
