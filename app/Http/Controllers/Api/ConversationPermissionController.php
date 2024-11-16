<?php

namespace App\Http\Controllers\Api;

use App\DTO\ConversationPermissionDTO;
use App\Exceptions\UserNotHavePermissionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Conversation\UpdatePermissionRequest;
use App\Http\Resources\ConversationPermissionResource;
use App\Models\Conversation;
use App\Services\ConversationPermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ConversationPermissionController extends Controller
{
    public function __construct(
        private readonly ConversationPermissionService $service
    ) {}

    /**
     * Get conversation permissions
     *
     * @param Conversation $conversation
     * @return JsonResponse
     */
    public function permissions(Conversation $conversation): JsonResponse
    {
        try {

            $permissions = $this->service->permissions($conversation);

            return $this->successResponse(
                [
                    'permissions' => ConversationPermissionResource::make($permissions)
                ],
                'Conversation Permission Retrieved Successfully.');

        } catch (UserNotHavePermissionException $exception) {
            return $this->errorResponse($exception->getMessage());
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
            return $this->errorResponse('Error happened retrieving conversation permissions.');
        }
    }

    /**
     * Update conversation permissions
     *
     * @param UpdatePermissionRequest $request
     * @param Conversation $conversation
     * @return JsonResponse
     */
    public function update(UpdatePermissionRequest $request, Conversation $conversation): JsonResponse
    {
        try {

            $this->service->updatePermissions(
                $conversation,
                ConversationPermissionDTO::fromFormRequest(
                    $request->validated('edit_group_settings'),
                    $request->validated('send_messages'),
                    $request->validated('add_other_members'),
                )
            );

            return $this->successResponse(message: 'Conversation Permission Updated Successfully.');

        } catch (UserNotHavePermissionException $exception) {
            return $this->errorResponse($exception->getMessage());
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
            return $this->errorResponse('Error happened while updating conversation permissions.');
        }
    }
}
