<?php

namespace App\Services;

use App\Events\Story\StoryViewedEvent;
use App\Models\Story;

class StoryViewService
{
    /**
     * View Story
     *
     * @param Story $story
     * @return void
     */
    public function viewStory(Story $story): void
    {
        $story->viewers()->attach(auth()->id());

        broadcast(new StoryViewedEvent($story, auth()->user()));
    }
}
