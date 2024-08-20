<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConversationParticipant\AddParticipantRequest;
use App\Http\Requests\ConversationParticipant\RemoveParticipantRequest;
use App\Models\Conversation;
use App\Services\ConversationParticipantService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Log;

class ConversationParticipantController extends Controller
{
    public function __construct(
        private readonly ConversationParticipantService $conversationParticipantService
    )
    {}

    public function participants(Conversation $conversation)
    {
        try {
            return $this->successResponse([
                'participants' => $this->conversationParticipantService->conversationParticipants($conversation)
            ],
                'Conversation Participants List'
            );
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
            return $this->errorResponse('Error happened While trying to retrieve conversation participants.');
        }
    }

    public function addParticipant(AddParticipantRequest $request, Conversation $conversation)
    {
        try {
            // TODO :: CHECK PERMISSION

            $this->conversationParticipantService->addParticipant($request, $conversation);

            return $this->successResponse([
                'participants' => $this->conversationParticipantService->conversationParticipants($conversation)
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

    public function removeParticipant(RemoveParticipantRequest $request, Conversation $conversation)
    {
        try {
            // TODO :: CHECK PERMISSION
            $this->conversationParticipantService->removeParticipant($request, $conversation);

            return $this->successResponse([
                'participants' => $this->conversationParticipantService->conversationParticipants($conversation)
            ],
                'Participant was removed successfully.'
            );
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
            return $this->errorResponse('Error happened While trying to remove participant.');
        }
    }
}
