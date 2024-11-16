<?php

namespace App\Services\SMS;

use App\Services\SMS\SMS;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class TwilioSMS implements SMS
{
    protected Client $client;

    /**
     * TwilioSMS constructor.
     * 
     * @throws ConfigurationException
     */
    public function __construct()
    {
        $sid = config('sms-service.twilio_sid');
        $token = config('sms-service.twilio_token');


        $this->client = new Client($sid, $token);
    }

    /**
     * Send SMS
     * 
     * @throws TwilioException
     */
    public function send(string $to, string $message): void
    {
        $fromNumber = config('sms-service.twilio_from');

        $this->client->messages->create($to, [
            'from' => $fromNumber,
            'body' => $message
        ]);
    }
}
