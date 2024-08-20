<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Services\StoryViewService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Log;

class StoryViewController extends Controller
{
    public function __construct(
        private readonly StoryViewService $storyViewService
    )
    {
    }

    public function viewStory(Story $story)
    {
        try {

            $this->storyViewService->viewStory($story);

            $this->successResponse(message: 'Story viewed successfully');

        } catch (UniqueConstraintViolationException) {
            return $this->errorResponse('User Can\'t view story twice.');
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            $this->errorResponse('error happened while viewing story');
        }
    }
}
