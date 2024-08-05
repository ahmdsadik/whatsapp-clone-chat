<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Story\CreateStoryRequest;
use App\Http\Requests\Story\DeleteStoryRequest;
use App\Http\Resources\StoryResource;
use App\Models\Story;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoryController extends Controller
{
    /**
     * Retrieve user's authorized stories
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $stories = auth()->user()->stories()->with(['media'])->get();

            return $this->successResponse([
                'stories' => StoryResource::collection($stories),
            ], 'Stories retrieved Successfully!');
        } catch (\Throwable $throwable) {
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
    public function store(CreateStoryRequest $request)
    {
        // TODO:: broadcast the new story
        try {
            DB::transaction(function () use ($request) {
                $story = auth()->user()->stories()->create($request->validated());

                if ($request->hasFile('media')) {
                    $story->addMediaFromRequest('media')
                        ->toMediaCollection('media');
                }
            });

            return $this->successResponse(message: 'Story published Successfully!');
        } catch (\Throwable $throwable) {
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
    public function destroy(DeleteStoryRequest $request, Story $story)
    {
        // TODO:: broadcast the deleted story
        try {
            $story->delete();

            return $this->successResponse(message: 'Story deleted Successfully!');
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return $this->errorResponse('Error happened While trying to delete user\'s Story.');
        }
    }
}
