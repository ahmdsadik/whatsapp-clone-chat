<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\InsertUserContactRequest;
use App\Http\Resources\ContactResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UserContactController extends Controller
{
    /**
     * Insert Contacts
     *
     * @param InsertUserContactRequest $request
     * @return JsonResponse
     */
    public function insertContacts(InsertUserContactRequest $request)
    {
        try {
            $user = auth()->user();

            $contacts = [];

            foreach ($request->contacts as $contact) {
                $contacts['user_id'] = $user->id;
                $contacts['mobile_number'] = $contact['mobile_number'];
                $contacts['name'] = $contact['name'];
            }

            $user->contacts()->insertOrIgnore($contacts);

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
            $user = auth()->user()->load('contacts.registeredUser.media');

            $registeredUsers = [];
            $notRegisteredUsers = [];

            foreach ($user->contacts as $contact) {
                if ($contact->registeredUser) {
                    $registeredUsers[] = ContactResource::make($contact);
                } else {
                    $notRegisteredUsers[] = ContactResource::make($contact);
                }
            }

            return $this->successResponse(
                [
                    'registeredUsers' => $registeredUsers,
                    'notRegisteredUsers' => $notRegisteredUsers
                ]
            );

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return $this->errorResponse('Error happened while trying to get contacts!');
        }
    }
}
