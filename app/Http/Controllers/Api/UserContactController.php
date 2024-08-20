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
    )
    {
    }

    /**
     * Insert Contacts
     *
     * @param InsertUserContactRequest $request
     * @return JsonResponse
     */
    public function insertContacts(InsertUserContactRequest $request)
    {
        try {
            $this->userContactService->insertContacts(UserContactsDTO::fromFormRequest($request));

            return $this->successResponse(message: 'User contacts updated successfully!');

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return $this->errorResponse('Failed to update user contacts!');
        }
    }

    /**
     * Get Registered and not Registered Contacts
     *
     * @return JsonResponse
     */
    public function contactsInfo()
    {
        try {

            [$registeredContacts, $notRegisteredContacts] = $this->userContactService->UserContactsDetails();

            return $this->successResponse(
                [
                    'registeredContacts' => $registeredContacts,
                    'notRegisteredUsers' => $notRegisteredContacts
                ]
            );

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return $this->errorResponse('Error happened while trying to get contacts!');
        }
    }
}
