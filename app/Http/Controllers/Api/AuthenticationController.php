<?php

namespace App\Http\Controllers\Api;

use App\DTO\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AuthenticationController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    /**
     * Login or create a user
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {

            $this->userService->loginOrCreate(
                UserDTO::fromFormRequest(
                    mobile_number: $request->validated('mobile_number')
                )
            );

            return $this->successResponse(
                message: 'OTP sent to your mobile number'
            );
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());

            return $this->errorResponse('Error happened while trying to log in.');
        }
    }

    /**
     * Login user
     */
    public function logout(): JsonResponse
    {
        try {

            $this->userService->logout();

            return $this->successResponse(message: 'You have been successfully logged out!');
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());

            return $this->errorResponse('Error happened while trying to log out.');
        }
    }
}
