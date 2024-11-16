<?php

namespace App\Http\Controllers\Api;

use App\DTO\ConversationDTO;
use App\Exceptions\ParticipantNotExistsInConversationException;
use App\Exceptions\UserNotHavePermissionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConversationParticipant\AddParticipantRequest;
use App\Http\Requests\ConversationParticipant\RemoveParticipantRequest;
use App\Http\Resources\ParticipantResource;
use App\Models\Conversation;
use App\Services\ConversationParticipantService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ConversationParticipantController extends Controller
{
    public function __construct(
        private readonly ConversationParticipantService $conversationParticipantService
    ) {
    }

    /**
     * Get conversation participants
     *
     * @param Conversation $conversation
     * @return JsonResponse
     */
    public function participants(Conversation $conversation): JsonResponse
    {
        try {
            $participants = $this->conversationParticipantService->conversationParticipants($conversation);

            return $this->successResponse([
                'participants' => ParticipantResource::collection($participants)
            ],
                'Conversation Participants List'
            );
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
            return $this->errorResponse('Error happened While trying to retrieve conversation participants.');
        }
    }

    /**
     * Add participant to conversation
     *
     * @param AddParticipantRequest $request
     * @param Conversation $conversation
     * @return JsonResponse
     */
    public function addParticipant(AddParticipantRequest $request, Conversation $conversation): JsonResponse
    {
        try {
            // TODO :: CHECK PERMISSION

            $this->conversationParticipantService->addParticipant(
                ConversationDTO::fromFormRequest(participants: $request->validated('participants')),
                $conversation
            );

            $participants = $this->conversationParticipantService->conversationParticipants($conversation);

            return $this->successResponse([
                'participants' => ParticipantResource::collection($participants)
            ],
                'Participants was added successfully.'
            );
        } catch (UniqueConstraintViolationException) {
            return $this->errorResponse('Can\'t add the same participant twice.');
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
            return $this->errorResponse('Error happened While trying to add participant.');
        }
    }

    /**
     * Remove participant from conversation
     *
     * @param RemoveParticipantRequest $request
     * @param Conversation $conversation
     * @return JsonResponse
     */
    public function removeParticipant(RemoveParticipantRequest $request, Conversation $conversation): JsonResponse
    {
        try {
            // TODO :: CHECK PERMISSION
            $this->conversationParticipantService->removeParticipant(
                ConversationDTO::fromFormRequest(participants: $request->validated('participants')),
                $conversation
            );

            $participants = $this->conversationParticipantService->conversationParticipants($conversation);

            return $this->successResponse([
                'participants' => ParticipantResource::collection($participants)
            ],
                'Participant was removed successfully.'
            );
        } catch (UserNotHavePermissionException $exception) {
            return $this->errorResponse($exception->getMessage());
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
            return $this->errorResponse('Error happened While trying to remove participant.');
        }
    }

    /**
     * Participant leave conversation
     *
     * @param Conversation $conversation
     * @return JsonResponse
     */
    public function participantLeave(Conversation $conversation): JsonResponse
    {
        try {
            $this->conversationParticipantService->participantLeave($conversation);

            return $this->successResponse(
                message: 'Participant left Conversation successfully.'
            );
        } catch (ParticipantNotExistsInConversationException $exception) {
            return $this->errorResponse($exception->getMessage());
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
            return $this->errorResponse('Error happened While trying to remove participant.');
        }
    }
}
