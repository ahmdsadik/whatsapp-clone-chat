<?php

namespace App\Actions;

use App\Enums\StoryPrivacy;
use App\Models\Story;

class SetStoryPrivacyContactsAction
{
    public function execute(Story $story, StoryPrivacy $privacy): void
    {
        $contacts_ids = $this->getUserStoryPrivacyContacts($privacy);

        $this->saveStoryPrivacyContacts($story, $contacts_ids);
    }

    private function getUserStoryPrivacyContacts(StoryPrivacy $privacy): array
    {
        $typePrivacy = auth()->user()->storiesPrivacy()->firstWhere(['privacy' => $privacy]);

        return $typePrivacy?->contacts()?->pluck('id')->toArray() ?? [];
    }

    private function saveStoryPrivacyContacts(Story $story, array $contacts_ids): void
    {
        $story->privacyContacts()->attach($contacts_ids);
    }
}
