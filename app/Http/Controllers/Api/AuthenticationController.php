<?php

namespace App\Http\Controllers\Api;

use App\DTO\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthenticationController extends Controller
{

    public function __construct(
        private readonly UserService $userService
    )
    {
    }

    public function login(LoginRequest $request)
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

    public function logout(Request $request)
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
