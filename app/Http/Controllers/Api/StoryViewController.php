<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Story\StoryViewRequest;
use App\Http\Resources\StoryViewResource;
use App\Models\StoryView;

class StoryViewController extends Controller
{
    public function index()
    {
        return StoryViewResource::collection(StoryView::all());
    }

    public function store(StoryViewRequest $request)
    {
        return new StoryViewResource(StoryView::create($request->validated()));
    }

    public function show(StoryView $storyView)
    {
        return new StoryViewResource($storyView);
    }

    public function update(StoryViewRequest $request, StoryView $storyView)
    {
        $storyView->update($request->validated());

        return new StoryViewResource($storyView);
    }

    public function destroy(StoryView $storyView)
    {
        $storyView->delete();

        return response()->json();
    }
}
