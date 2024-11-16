<?php

namespace App\Http\Controllers\Api;

use App\DTO\ConversationDTO;
use App\Exceptions\UserNotHavePermissionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Conversation\CreateConversationsRequest;
use App\Http\Requests\Conversation\UpdateConversationRequest;
use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use App\Services\ConversationService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ConversationController extends Controller
{
    public function __construct(
        private readonly ConversationService $conversationService
    ) {}

    public function index(): JsonResponse
    {
        try {
            $conversations = $this->conversationService->userConversations();

            return $this->successResponse([
                'conversations' => ConversationResource::collection($conversations)
            ],
                'conversations retrieved successfully'
            );
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
            return $this->errorResponse('Error happened While trying to retrieve conversations.');
        }
    }

    public function store(CreateConversationsRequest $request): JsonResponse
    {
        try {

            $conversation = $this->conversationService->createConversation(ConversationDTO::fromFormRequest($request));

            return $this->successResponse([
                'conversation' => ConversationResource::make($conversation),
            ],
                'Conversation Created Successfully'
            );

        } catch (UniqueConstraintViolationException) {
            return $this->errorResponse('Can\'t add the same participant twice.');
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
            return $this->errorResponse('Error happened While trying to create conversation.');
        }
    }

    public function update(UpdateConversationRequest $request, Conversation $conversation): JsonResponse
    {
        try {

            $updatedConversation = $this->conversationService->updateConversation(ConversationDTO::fromFormRequest($request),
                $conversation);

            return $this->successResponse([
                'conversation' => ConversationResource::make($updatedConversation),
            ],
                'Conversation Updated Successfully'
            );

        } catch (UserNotHavePermissionException $exception) {
            return $this->errorResponse($exception->getMessage());
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
            return $this->errorResponse('Error happened While trying to create conversation.');
        }
    }

    public function destroy(Conversation $conversation): JsonResponse
    {
        try {
            $this->conversationService->deleteConversation($conversation);

            return $this->successResponse(message: 'Conversation Deleted Successfully');

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
            return $this->errorResponse('Error happened While trying to delete conversation.');
        }
    }
}
