<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Models\User;

class UserService
{
    public function __construct(
        private readonly OTPService $otpService,
    ) {}

    /**
     * Login or Create User
     *
     * @param UserDTO $userDTO
     * @return void
     */
    public function loginOrCreate(UserDTO $userDTO): void
    {
        $user = User::createOrFirst($userDTO->toArray(['mobile_number']));

        $this->otpService->sendOTP($user->mobile_number);
    }

    /**
     * Logout User
     *
     * @return void
     */
    public function logout(): void
    {
        $user = auth()->user();
        $user->currentAccessToken()->delete();
    }
}
