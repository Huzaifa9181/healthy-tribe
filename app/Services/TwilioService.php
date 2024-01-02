<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $client;
    protected $twilioNumber;

    public function __construct()
    {
        $accountSid = env('TWILIO_SID');
        $authToken = env('TWILIO_AUTH_TOKEN');
        $this->twilioNumber = env('TWILIO_NUMBER');

        $this->client = new Client($accountSid, $authToken);
    }

    public function sendSMS($to, $message)
    {
        return $this->client->messages->create($to, [
            'from' => $this->twilioNumber,
            'body' => $message
        ]);
    }
}
