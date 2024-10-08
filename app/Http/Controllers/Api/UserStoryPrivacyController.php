<?php

namespace App\Http\Controllers\Api;

use App\DTO\UserStoryPrivacyDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Story\UpdateUserStoryPrivacyRequest;
use App\Services\UserStoryPrivacyService;
use Illuminate\Support\Facades\Log;

class UserStoryPrivacyController extends Controller
{

    public function __construct(
        private readonly UserStoryPrivacyService $userStoryPrivacyService
    )
    {
    }

    public function __invoke(UpdateUserStoryPrivacyRequest $request)
    {
        try {
            $this->userStoryPrivacyService->updateStoryPrivacy(UserStoryPrivacyDTO::fromFormRequest($request));

            return $this->successResponse(message: 'Story Privacy Contacts updated successfully.');

        } catch (\Throwable $throwable) {
            Log::error('Story Privacy Contacts update failed [' . $throwable->getMessage() . ']', ['trace' => $throwable->getTraceAsString()]);
            return $this->errorResponse('Error happened While updating Story Privacy Contacts.');
        }
    }
}
