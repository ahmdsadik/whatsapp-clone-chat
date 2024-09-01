<?php

namespace App\Services;

use App\Actions\ProcessStoryMediaAction;
use App\Actions\SetStoryPrivacyContactsAction;
use App\DTO\StoryDTO;
use App\Enums\StoryPrivacy;
use App\Events\Story\NewStoryEvent;
use App\Events\Story\StoryDeletedEvent;
use App\Models\Story;
use Illuminate\Support\Facades\DB;

class StoryService
{
    /**
     * Retrieve User's authorized stories
     *
     */
    public function authorizedStories()
    {
        // TODO:: Check the order by story created at again
        return auth()->user()->registeredContacts()->with(['registeredUser' => ['authorizedStories', 'media']])->get();
    }

    public function createStory(StoryDTO $storyDTO): void
    {
        DB::transaction(function () use ($storyDTO) {
            $story = auth()->user()->stories()->create($storyDTO->toArray());

            if ($storyDTO->media) {
                (new ProcessStoryMediaAction())->execute($story);
            }

            if ($storyDTO->privacy !== StoryPrivacy::ALL_CONTACTS) {
                (new SetStoryPrivacyContactsAction())->execute($story, $storyDTO->privacy);
            }

            // TODO:: Broadcast the story
            broadcast(new NewStoryEvent($story, auth()->user()));
        });
    }

    public function deleteStory(Story $story): void
    {
        $story->delete();

        // TODO::Broadcast the deleted story
        broadcast(new StoryDeletedEvent($story, auth()->user()));
    }
}
