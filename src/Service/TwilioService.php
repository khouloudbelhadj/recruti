<?php

namespace App\Service;

use Twilio\Rest\Client;

class TwilioService
{
    private $accountSid = 'AC30fba02ba86c922db12bb89ddebffdba';
    private $authToken = '2e624e8cd390f4ffe214efa26d6f8d68';
    private $twilioPhoneNumber = '+14025245814';
   
    public function sendSMS()
    {
        $to = '+21650381649'; // Le numéro de téléphone destinataire
        $message = 'you  signed up to our app recruti successfully'; // Le message que vous souhaitez envoyer
        $client = new Client($this->accountSid, $this->authToken);
        $client->messages->create(
            $to,
            [
                'from' => $this->twilioPhoneNumber,
                'body' => $message,
            ]
        );
    }
}


