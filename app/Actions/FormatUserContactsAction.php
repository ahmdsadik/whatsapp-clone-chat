<?php

namespace App\Actions;

use Illuminate\Support\Facades\Log;
use Propaganistas\LaravelPhone\PhoneNumber;

class FormatUserContactsAction
{
    public function execute(array $contacts): array
    {
        $country_code = $this->getAuthUserCountryCode();

        return $this->formatNumbers($contacts, $country_code);
    }

    private function getAuthUserCountryCode(): string
    {
        $phone_number = new PhoneNumber(auth()->user()->mobile_number);
        return $phone_number->getCountry();
    }

    private function formatNumbers(array $contacts, string $country_code): array
    {
        foreach ($contacts as &$contact) {

            try {
                if (!str_starts_with($contact['mobile_number'], '+')) {
                    $contact['mobile_number'] = (new PhoneNumber($contact['mobile_number'], $country_code))->formatE164();
                }
            } catch (\Throwable $throwable) {
                Log::warning('Could\'t parse this number', ['mobile_number' => $contact['mobile_number']]);
            }
        }

        return $contacts;
    }
}
