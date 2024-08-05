<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthenticationController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $user = User::createOrFirst($request->only('mobile_number'));

            $access_token = $user->createToken('token')->plainTextToken;

            return $this->successResponse([
                'user' => UserResource::make($user),
                'access_token' => $access_token
            ]);
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return $this->errorResponse('Error happened while trying to log in.');
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            $user->currentAccessToken()->delete();
            $user->update([
                'fcm_token' => null
            ]);

            return $this->successResponse(message: 'You have been successfully logged out!');
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return $this->errorResponse('Error happened while trying to log out.');
        }
    }
}
