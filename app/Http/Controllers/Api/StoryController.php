<?php

namespace App\Http\Controllers\Api;

use App\DTO\StoryDTO;
use App\Enums\StoryPrivacy;
use App\Enums\StoryType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Story\CreateStoryRequest;
use App\Http\Requests\Story\DeleteStoryRequest;
use App\Http\Resources\ContactsStoriesResource;
use App\Models\Story;
use App\Services\StoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class StoryController extends Controller
{
    public function __construct(
        private readonly StoryService $storyService
    ) {}

    /**
     * Retrieve user's authorized stories
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {

            $authorizedStories = $this->storyService->authorizedStories();

            return $this->successResponse([
                'stories' => ContactsStoriesResource::collection($authorizedStories),
            ], 'Stories retrieved Successfully!');

        } catch (Throwable $throwable) {
            Log::error($throwable->getMessage());
            return $this->errorResponse('Error happened While trying to retrieve Stories.');
        }
    }

    /**
     * Create a story
     *
     * @param CreateStoryRequest $request
     * @return JsonResponse
     */
    public function store(CreateStoryRequest $request): JsonResponse
    {
        try {

            $this->storyService->createStory(StoryDTO::fromFormRequest(
                StoryType::from($request->validated('type')),
                $request->validated('text'),
                $request->validated('duration'),
                StoryPrivacy::from($request->validated('privacy')),
                $request->validated('media'),
            ));

            return $this->successResponse(message: 'Story published Successfully!');
        } catch (Throwable $throwable) {
            Log::error($throwable->getMessage());
            return $this->errorResponse('Error happened While trying to publish user\'s Story.');
        }
    }

    /**
     * Delete a story
     *
     * @param DeleteStoryRequest $request
     * @param Story $story
     * @return JsonResponse
     */
    public function destroy(DeleteStoryRequest $request, Story $story): JsonResponse
    {
        try {

            $this->storyService->deleteStory($story);

            return $this->successResponse(message: 'Story deleted Successfully!');
        } catch (Throwable $throwable) {
            Log::error($throwable->getMessage());
            return $this->errorResponse('Error happened While trying to delete user\'s Story.');
        }
    }
}
