<?php

namespace App\Http\Controllers\Api;

use App\DTO\NewMessageDTO;
use App\Exceptions\UserNotHavePermissionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\MessageService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function __construct(
        private readonly MessageService $messageService
    )
    {
    }

    public function index(Conversation $conversation)
    {
        try {

            return $this->successResponse([
                'messages' => $this->messageService->conversationMessages($conversation)
            ],
                'Message received successfully'
            );

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
            return $this->errorResponse('Error happened while receiving messages.');
        }
    }

    public function store(StoreMessageRequest $request)
    {
        try {
            $message = $this->messageService->saveMessage(NewMessageDTO::fromFormRequest($request));

            return $this->successResponse([
                'message' => $message
            ],
                'Message has been saved'
            );
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
            return $this->errorResponse('Error happened while saving message.');
        }
    }

    public function view(Message $message)
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

    public function destroy(Message $message)
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
