<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Models\User;
use App\Services\OTP\OTP;
use App\Services\SMS\SMS;

class UserService
{
    public function __construct(
        private readonly OTPService $otpService,
    )
    {
    }

    public function loginOrCreate(UserDTO $userDTO): void
    {
        $user = User::createOrFirst($userDTO->toArray(['mobile_number']));

        $this->otpService->sendOTP($user->mobile_number);
    }

    public function logout(): void
    {
        $user = auth()->user();
        $user->currentAccessToken()->delete();
    }
}
