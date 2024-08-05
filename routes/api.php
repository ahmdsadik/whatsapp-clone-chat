<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\LinkedDeviceController;
use App\Http\Controllers\Api\StoryController;
use App\Http\Controllers\Api\UserContactController;
use App\Http\Controllers\Api\UserProfileController;
use Illuminate\Support\Facades\Route;

################## Login and register users route ##################
Route::post('login', [AuthenticationController::class, 'login']);

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

    Route::prefix('stories')->group(function () {
        Route::get('/', [StoryController::class, 'index']);
        Route::post('/', [StoryController::class, 'store']);
        Route::delete('{story}', [StoryController::class, 'destroy']);
    });
});
