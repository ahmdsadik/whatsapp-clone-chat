<?php

namespace App\Services;

use App\Actions\CheckAddParticipantPermissionAction;
use App\Actions\CheckParticipantLeaveAction;
use App\Actions\CheckRemoveParticipantAction;
use App\Actions\ConversationNeedsAdminsCheckAction;
use App\Events\Participant\NewParticipantEvent;
use App\Events\Participant\ParticipantLeftEvent;
use App\Events\Participant\ParticipantRemovedEvent;
use App\Exceptions\ParticipantNotExistsInConversationException;
use App\Exceptions\UserNotHavePermissionException;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class ConversationParticipantService
{
    /**
     * Get conversation participants
     *
     * @param Conversation $conversation
     * @return void
     */
    public function conversationParticipants(Conversation $conversation)
    {
        return $conversation->participants()->with('media')->get();
    }

    public function addParticipant(FormRequest $request, Conversation $conversation): void
    {
        $newParticipants = User::whereIn('mobile_number', $request->validated('participants'))->get();

        (new CheckAddParticipantPermissionAction())->execute($conversation);

        DB::transaction(function () use ($conversation, $newParticipants) {

            $participants_ids = $newParticipants->pluck('id')->toArray();

            $conversation->participants()->attach($participants_ids);
            $conversation->previousParticipants()->detach($participants_ids);

            // TODO:: Broadcast to new participants

            broadcast(new NewParticipantEvent($conversation, $newParticipants, auth()->user()));
        });
    }

    /**
     * Remove participant from conversation
     * 
     * @throws UserNotHavePermissionException|ParticipantNotExistsInConversationException
     */
    public function removeParticipant(FormRequest $request, Conversation $conversation): void
    {
        $participant = User::firstWhere('mobile_number', $request->validated('participant'));

        (new CheckRemoveParticipantAction())->execute($conversation, $participant->id);

        DB::transaction(function () use ($conversation, $participant) {

            $conversation->participants()->detach($participant->id);
            $conversation->previousParticipants()->attach($participant->id);

            // TODO: Broadcast
            broadcast(new ParticipantRemovedEvent($conversation, $participant, auth()->user()));
        });
    }

    /**
     * Participant leave conversation
     * 
     * @throws ParticipantNotExistsInConversationException
     */
    public function participantLeave(Conversation $conversation): void
    {

        (new CheckParticipantLeaveAction())->execute($conversation);

        DB::transaction(function () use ($conversation) {

            $conversation->participants()->detach(auth()->id());
            $conversation->previousParticipants()->attach(auth()->id());

            // TODO: Broadcast
            broadcast(new ParticipantLeftEvent($conversation, auth()->user()));

            (new ConversationNeedsAdminsCheckAction())->execute($conversation);
        });
    }
}
