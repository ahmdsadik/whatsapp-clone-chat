<?php

namespace App\Services;

use App\Actions\CheckAddParticipantPermissionAction;
use App\Actions\CheckParticipantLeaveAction;
use App\Actions\CheckRemoveParticipantAction;
use App\Actions\ConversationNeedsAdminsCheckAction;
use App\Exceptions\ParticipantNotExistsInConversationException;
use App\Exceptions\UserNotHavePermissionException;
use App\Http\Resources\ParticipantResource;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class ConversationParticipantService
{
    /**
     * @param Conversation $conversation
     * @return AnonymousResourceCollection
     */
    public function conversationParticipants(Conversation $conversation): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ParticipantResource::collection($conversation->participants()->with('media')->get());
    }

    /**
     * @param FormRequest $request
     * @param Conversation $conversation
     * @return void
     * @throws UserNotHavePermissionException
     */
    public function addParticipant(FormRequest $request, Conversation $conversation): void
    {
        $users_ids = User::whereIn('mobile_number', $request->validated('participants'))->pluck('id')->toArray();

        (new CheckAddParticipantPermissionAction())->execute($conversation);

        DB::transaction(function () use ($conversation, $users_ids) {

            $conversation->participants()->attach($users_ids);
            $conversation->previousParticipants()->detach($users_ids);

        });

        // TODO:: Broadcast to new participants

    }

    /**
     * @throws UserNotHavePermissionException|ParticipantNotExistsInConversationException
     */
    public function removeParticipant(FormRequest $request, Conversation $conversation): void
    {
        $participant = User::where('mobile_number', $request->validated('participant'))->get(['id'])->first();
        $participant_id = $participant->id;

        (new CheckRemoveParticipantAction())->execute($conversation, $participant_id);

        DB::transaction(function () use ($conversation, $participant_id) {

            $conversation->participants()->detach($participant_id);
            $conversation->previousParticipants()->attach($participant_id);

        });

        // TODO: Broadcast

    }

    /**
     * @throws ParticipantNotExistsInConversationException
     */
    public function participantLeave(Conversation $conversation): void
    {

        (new CheckParticipantLeaveAction())->execute($conversation);

        DB::transaction(function () use ($conversation) {

            $conversation->participants()->detach(auth()->id());
            $conversation->previousParticipants()->attach(auth()->id());

            (new ConversationNeedsAdminsCheckAction())->execute($conversation);
        });

        // TODO: Broadcast

    }
}
