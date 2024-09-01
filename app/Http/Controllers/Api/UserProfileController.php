<?php

namespace App\Http\Controllers\Api;

use App\DTO\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfile\UpdateUserAvatarRequest;
use App\Http\Requests\UserProfile\UpdateUserInfoRequest;
use App\Http\Requests\UserProfile\UpdateUserNameRequest;
use App\Http\Resources\UserResource;
use App\Services\UserProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserProfileController extends Controller
{
    public function __construct(
        private readonly UserProfileService $profileService
    )
    {
    }

    /**
     * Get currently authenticated user
     *
     * @return JsonResponse
     */
    public function user()
    {
        try {
            return $this->successResponse([
                'user' => UserResource::make(auth()->user())
            ]);
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return $this->errorResponse('Error happened while trying to log in.');
        }
    }

    /**
     * Update user's name
     *
     * @param UpdateUserNameRequest $request
     * @return JsonResponse
     */
    public function updateName(UpdateUserNameRequest $request)
    {

        try {

            $this->profileService->updateName(
                UserDTO::fromFormRequest($request->validated('name'), $request->validated('about')
                )
            );

            // TODO:: Broadcast event to contact users

            return $this->successResponse(message: 'User info Updated successfully!');

        } catch (\Throwable $throwable) {
            return $this->errorResponse('Failed to update user info!');
        }

    }


    /**
     * Update user's avatar
     *
     * @param UpdateUserAvatarRequest $request
     * @return JsonResponse
     */
    public function updateAvatar(UpdateUserAvatarRequest $request)
    {
        try {

            $this->profileService->updateAvatar('avatar');

            // TODO:: Broadcast event to contact users

            return $this->successResponse(message: 'Avatar Updated successfully!');
        } catch (\Throwable $throwable) {
            return $this->errorResponse('Avatar Failed to Update!');
        }

    }

    /**
     * Update name, about and avatar
     *
     * @param UpdateUserInfoRequest $request
     * @return JsonResponse
     */
    public function updateInfo(UpdateUserInfoRequest $request)
    {
        try {

            $this->profileService->updateAllInfo(
                UserDTO::fromFormRequest(
                    $request->validated('name'),
                    $request->validated('about')
                ),
                'avatar'
            );

            // TODO:: Broadcast event to contact users


            return $this->successResponse(message: 'Updated successfully!');

        } catch (\Throwable $throwable) {
            return $this->errorResponse('Failed to Update!');
        }
    }
}
