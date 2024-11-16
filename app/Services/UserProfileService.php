<?php

namespace App\Services;

use App\DTO\UserDTO;

class UserProfileService
{
    /**
     * Update User Name
     *
     * @param UserDTO $user
     * @return void
     */
    public function updateName(UserDTO $user): void
    {
        auth()->user()->update($user->toArray(['name', 'about']));
    }

    /**
     * Update User Avatar
     *
     * @param string $file_key
     * @return void
     */
    public function updateAvatar(string $file_key): void
    {
        auth()->user()->addMediaFromRequest($file_key)
            ->toMediaCollection('avatar');
    }

    /**
     * Update User Account Information
     *
     * @param UserDTO $userDTO
     * @param string $file_key
     * @return void
     */
    public function updateAllInfo(UserDTO $userDTO, string $file_key): void
    {
        $user = auth()->user();

        $user->update($userDTO->toArray(['name', 'about']));

        $user->addMediaFromRequest($file_key)
            ->toMediaCollection('avatar');
    }
}
