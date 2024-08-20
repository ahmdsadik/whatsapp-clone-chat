<?php

namespace App\Services;

use App\DTO\UserStoryPrivacyDTO;
use App\Enums\StoryPrivacy;
use App\Models\User;

class UserStoryPrivacyService
{
    public function updateStoryPrivacy(UserStoryPrivacyDTO $storyPrivacyDTO): void
    {
        $storyPrivacy = auth()->user()->storiesPrivacy()->firstOrCreate(['privacy' => $storyPrivacyDTO->privacy]);

        if ($storyPrivacyDTO->privacy !== StoryPrivacy::ALL_CONTACTS) {

            $users_ids = User::whereIn('mobile_number', $storyPrivacyDTO->contacts)->pluck('id')->toArray();

            $storyPrivacy->contacts()->sync($users_ids);
        }
    }
}
