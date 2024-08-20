<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\ConversationParticipantController;
use App\Http\Controllers\Api\ConversationParticipantRoleController;
use App\Http\Controllers\Api\LinkedDeviceController;
use App\Http\Controllers\Api\StoryController;
use App\Http\Controllers\Api\UserContactController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\UserStoryPrivacyController;
use Illuminate\Support\Facades\Route;

################## Login and register users route ##################
Route::post('login', [AuthenticationController::class, 'login']);


Route::get('test/{conversation}', function (\App\Models\Conversation $conversation) {
    dd($conversation->isAllowing(\App\Enums\ConversationPermission::ADD_OTHER_PARTICIPANTS));

})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthenticationController::class, 'logout']);

    ################## User Profile Routes ##################
    Route::prefix('user')->group(function () {
        Route::get('/', [UserProfileController::class, 'user']);
        Route::post('update-name', [UserProfileController::class, 'updateName']);
        Route::post('update-avatar', [UserProfileController::class, 'updateAvatar']);
        Route::post('update-info', [UserProfileController::class, 'updateInfo']);
    });

    ################## User Contacts Routes ##################
    Route::post('contacts', [UserContactController::class, 'insertContacts']);
    Route::get('contacts-list', [UserContactController::class, 'contactsInfo']);

    ################## User Linked Devices Routes ##################
    Route::prefix('linked-device')->group(function () {
        Route::get('/', [LinkedDeviceController::class, 'index']);
        Route::get('create', [LinkedDeviceController::class, 'createLinkChannel'])->withoutMiddleware('auth:sanctum');
        Route::post('link', [LinkedDeviceController::class, 'linkDevice']);
        Route::delete('{linked_device}', [LinkedDeviceController::class, 'unlinkDevice']);
    });

    ################## User Stories Routes ##################
    Route::prefix('stories')->group(function () {
        Route::get('/', [StoryController::class, 'index']);
        Route::post('/', [StoryController::class, 'store']);
        Route::delete('{story}', [StoryController::class, 'destroy']);

        Route::post('update-privacy', UserStoryPrivacyController::class);
    });

    ################## Conversations Routes ##################
    Route::prefix('conversations')->group(function () {

        ################## Conversations CRUD Routes ##################
        Route::get('/', [ConversationController::class, 'index']);
        Route::post('/', [ConversationController::class, 'store']);
        Route::post('/{conversation}', [ConversationController::class, 'update']);
        Route::delete('/{conversation}', [ConversationController::class, 'destroy']);

        ################## Conversations Participants Routes ##################
        Route::prefix('{conversation}/participants')->group(function () {
            Route::get('/', [ConversationParticipantController::class, 'participants']);
            Route::post('/', [ConversationParticipantController::class, 'addParticipant']);
            Route::post('/remove', [ConversationParticipantController::class, 'removeParticipant']);
        });

        ################## Conversations Participants Role Routes ##################
        Route::prefix('{conversation}/participants/role')->group(function () {
            Route::post('/', ConversationParticipantRoleController::class);
        });

    });

});
