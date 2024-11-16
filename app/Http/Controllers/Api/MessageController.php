<?php

namespace App\Http\Controllers\Api;

use App\DTO\NewMessageDTO;
use App\Enums\MessageType;
use App\Exceptions\UserNotHavePermissionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\MessageService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function __construct(
        private readonly MessageService $messageService
    ) {
    }

    /**
     * Get conversation messages
     *
     * @param Conversation $conversation
     * @return JsonResponse
     */
    public function index(Conversation $conversation): JsonResponse
    {
        try {

            $messages = $this->messageService->conversationMessages($conversation);

            return $this->successResponse([
                'messages' => MessageResource::collection($messages)
            ],
                'Message received successfully'
            );

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
            return $this->errorResponse('Error happened while receiving messages.');
        }
    }

    /**
     * Save a new message
     *
     * @param StoreMessageRequest $request
     * @return JsonResponse
     */
    public function store(StoreMessageRequest $request): JsonResponse
    {
        try {
            $message = $this->messageService->saveMessage(NewMessageDTO::fromFormRequest(
                $request->validated('to'),
                $request->validated('text'),
                MessageType::from($request->validated('type')),
                $request->validated('media'),
            ));

            return $this->successResponse([
                'message' => MessageResource::make($message)
            ],
                'Message has been saved'
            );
        } catch (UserNotHavePermissionException $exception) {
            return $this->errorResponse($exception->getMessage());
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
            return $this->errorResponse('Error happened while saving message.');
        }
    }

    /**
     * View a message
     *
     * @param Message $message
     * @return JsonResponse
     */
    public function view(Message $message): JsonResponse
    {
        try {
            $this->messageService->viewMessage($message);

            return $this->successResponse(
                message: 'Message viewed successfully',
            );

        } catch (UniqueConstraintViolationException) {
            return $this->errorResponse('User Cannot see a message twice');
        } catch (UserNotHavePermissionException $exception) {
            return $this->errorResponse($exception->getMessage());
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
            return $this->errorResponse('Error happened while viewing a message.');
        }
    }

    /**
     * Delete a message
     *
     * @param Message $message
     * @return JsonResponse
     */
    public function destroy(Message $message): JsonResponse
    {
        try {
            $this->messageService->deleteMessage($message);

            return $this->successResponse(
                message: 'Message deleted successfully'
            );
        } catch (UserNotHavePermissionException $exception) {
            return $this->errorResponse($exception->getMessage());
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
            return $this->errorResponse('Error happened while saving message.');
        }
    }
}
