<?php

namespace App\Services;

use App\Actions\ProcessStoryMediaAction;
use App\Actions\SetStoryPrivacyContactsAction;
use App\DTO\StoryDTO;
use App\Enums\StoryPrivacy;
use App\Events\Story\NewStoryEvent;
use App\Events\Story\StoryDeletedEvent;
use App\Http\Resources\ContactsStoriesResource;
use App\Models\Story;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class StoryService
{
    /**
     * Retrieve User's authorized stories
     *
     * @return AnonymousResourceCollection
     */
    public function authorizedStories(): AnonymousResourceCollection
    {
        // TODO:: Check the order by story created at again
        $authorizedStories = auth()->user()->registeredContacts()->with(['registeredUser' => ['authorizedStories', 'media']])->get();

        return ContactsStoriesResource::collection($authorizedStories);
    }

    public function createStory(StoryDTO $storyDTO): void
    {
        $story = DB::transaction(function () use ($storyDTO) {
            $story = auth()->user()->stories()->create($storyDTO->toArray());

            if ($storyDTO->media) {
                (new ProcessStoryMediaAction())->handle($story);
            }

            if ($storyDTO->privacy !== StoryPrivacy::ALL_CONTACTS) {
                (new SetStoryPrivacyContactsAction())->execute($story, $storyDTO->privacy);
            }

            // TODO:: Broadcast the story
            broadcast(new NewStoryEvent($story, auth()->user()));

            return $story;
        });

//        (new BroadcastStoryAction())->execute($story);

    }

    public function deleteStory(Story $story): void
    {
        $story->delete();

        // TODO::Broadcast the deleted story
        broadcast(new StoryDeletedEvent($story, auth()->user()));
    }
}
