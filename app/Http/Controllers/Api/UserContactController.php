<?php

namespace App\Http\Controllers\Api;

use App\DTO\UserContactsDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\InsertUserContactRequest;
use App\Services\UserContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UserContactController extends Controller
{
    public function __construct(
        private readonly UserContactService $userContactService
    ) {}

    /**
     * Insert Contacts
     */
    public function insertContacts(InsertUserContactRequest $request): JsonResponse
    {
        try {
            $this->userContactService->insertContacts(UserContactsDTO::fromFormRequest($request->validated('contacts')));

            return $this->successResponse(message: 'User contacts updated successfully!');

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());

            return $this->errorResponse('Failed to update user contacts!');
        }
    }

    /**
     * Get Registered and not Registered Contacts
     */
    public function contactsInfo(): JsonResponse
    {
        try {

            [$registeredContacts, $notRegisteredContacts] = $this->userContactService->UserContactsDetails();

            return $this->successResponse(
                [
                    'registeredContacts' => $registeredContacts,
                    'notRegisteredUsers' => $notRegisteredContacts,
                ]
            );

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());

            return $this->errorResponse('Error happened while trying to get contacts!');
        }
    }
}
