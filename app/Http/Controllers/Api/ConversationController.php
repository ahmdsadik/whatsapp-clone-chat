<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index()
    {
        return ConversationResource::collection(Conversation::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label' => ['required'],
            'type' => ['required'],
            'limit' => ['required'],
        ]);

        return new ConversationResource(Conversation::create($data));
    }

    public function show(Conversation $conversation)
    {
        return new ConversationResource($conversation);
    }

    public function update(Request $request, Conversation $conversation)
    {
        $data = $request->validate([
            'label' => ['required'],
            'type' => ['required'],
            'limit' => ['required'],
        ]);

        $conversation->update($data);

        return new ConversationResource($conversation);
    }

    public function destroy(Conversation $conversation)
    {
        $conversation->delete();

        return response()->json();
    }
}
