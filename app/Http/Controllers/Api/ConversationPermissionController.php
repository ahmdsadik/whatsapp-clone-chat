<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\UserNotHavePermissionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Conversation\UpdatePermissionRequest;
use App\Models\Conversation;
use App\Services\ConversationService;
use Illuminate\Support\Facades\Log;

class ConversationPermissionController extends Controller
{
    public function __construct(
        private readonly ConversationService $conversationService
    )
    {
    }

    public function __invoke(UpdatePermissionRequest $request, Conversation $conversation)
    {
        try {
            $this->conversationService->updatePermissions($conversation);
        } catch (UserNotHavePermissionException $exception) {
            return $this->errorResponse($exception->getMessage());
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
            return $this->errorResponse('Error happened while updating conversation permissions.');
        }
    }
}
