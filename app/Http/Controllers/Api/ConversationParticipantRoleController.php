<?php

namespace App\Http\Controllers\Api;

use App\DTO\ParticipantRoleDTO;
use App\Enums\ParticipantRole;
use App\Exceptions\ParticipantNotExistsInConversationException;
use App\Exceptions\UserNotHavePermissionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConversationParticipantRole\UpdateParticipantRoleRequest;
use App\Models\Conversation;
use App\Services\ConversationParticipantRoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ConversationParticipantRoleController extends Controller
{
    public function __construct(
        private readonly ConversationParticipantRoleService $conversationParticipantRoleService
    ) {}

    /**
     * Update participant role
     */
    public function __invoke(UpdateParticipantRoleRequest $request, Conversation $conversation): JsonResponse
    {
        try {

            $this->conversationParticipantRoleService->updateParticipantRole(ParticipantRoleDTO::fromFormRequest(
                $request->validated('participant'),
                $request->validated('participant')['mobile_number'],
                ParticipantRole::from($request->validated('participant')['role'])
            ),
                $conversation
            );

            return $this->successResponse(message: 'Roles Updated Successfully');

        } catch (ParticipantNotExistsInConversationException|UserNotHavePermissionException $throwable) {
            return $this->errorResponse($throwable->getMessage());
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);

            return $this->errorResponse('Error happened. while trying to update participant role');
        }
    }
}
