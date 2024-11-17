<?php

namespace App\Services;

use App\DTO\UserDTO;

class UserProfileService
{
    /**
     * Update User Name
     */
    public function updateName(UserDTO $user): void
    {
        auth()->user()->update($user->toArray(['name', 'about']));
    }

    /**
     * Update User Avatar
     */
    public function updateAvatar(string $file_key): void
    {
        auth()->user()->addMediaFromRequest($file_key)
            ->toMediaCollection('avatar');
    }

    /**
     * Update User Account Information
     */
    public function updateAllInfo(UserDTO $userDTO, string $file_key): void
    {
        $user = auth()->user();

        $user->update($userDTO->toArray(['name', 'about']));

        $user->addMediaFromRequest($file_key)
            ->toMediaCollection('avatar');
    }
}
