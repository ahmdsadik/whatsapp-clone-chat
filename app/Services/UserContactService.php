<?php

namespace App\Services;

use App\Actions\FormatUserContactsAction;
use App\DTO\UserContactsDTO;
use App\Http\Resources\ContactResource;

class UserContactService
{
    /**
     * Get User Contacts Details
     *
     * @return array
     */
    public function UserContactsDetails(): array
    {
        $userContacts = auth()->user()->load('contacts.registeredUser.media');

        $registeredContacts = [];
        $notRegisteredContacts = [];

        // Check if the contact is registered or not
        foreach ($userContacts->contacts as $contact) {
            if ($contact->registeredUser) {
                $registeredContacts[] = ContactResource::make($contact);
            } else {
                $notRegisteredContacts[] = ContactResource::make($contact);
            }
        }

        return [$registeredContacts, $notRegisteredContacts];
    }

    /**
     * Insert User Contacts
     *
     * @param UserContactsDTO $userContactsDTO
     * @return void
     */
    public function insertContacts(UserContactsDTO $userContactsDTO): void
    {
        // Format contacts to start with country code
        $formated_contacts = (new FormatUserContactsAction())->execute($userContactsDTO->contacts);

        // Insert contacts and update contact name if found
        auth()->user()->contacts()->upsert($formated_contacts, ['mobile_number'], ['name']);
    }
}
