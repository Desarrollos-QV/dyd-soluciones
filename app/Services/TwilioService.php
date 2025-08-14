<?php

// app/Services/TwilioService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class TwilioService
{
    protected $sid;
    protected $token;
    protected $from;

    public function __construct($TWILIO_SID, $TWILIO_AUTH_TOKEN, $TWILIO_PHONE)
    {
        $this->sid = $TWILIO_SID;
        $this->token = $TWILIO_AUTH_TOKEN;
        $this->from = $TWILIO_PHONE;
    }

    public function sendMessageWhatsapp($destination, $message)
    {
        $client = new \GuzzleHttp\Client([
            'verify' => false // Desactiva la verificación SSL
        ]);

        $response = $client->request('POST', "https://api.twilio.com/2010-04-01/Accounts/{$this->sid}/Messages.json", [
            'auth' => [$this->sid, $this->token],
            'form_params' => [
                'From' => 'whatsapp:' . $this->from,
                'To' => 'whatsapp:' . $destination,
                'Body' => $message
            ],
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function sendMessageSMS($destination, $message)
    {
        
        $client = new \GuzzleHttp\Client([
            'verify' => false // Desactiva la verificación SSL
        ]);

        $response = $client->request('POST', "https://api.twilio.com/2010-04-01/Accounts/{$this->sid}/Messages.json", [
            'auth' => [$this->sid, $this->token],
            'form_params' => [
                'To' => $destination,
                'From' => $this->from,
                'Body' => $message
            ],
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function sendMessageEmail($destination, $message)
    {
        Mail::raw($message, function ($mail) use ($destination) {
            $mail->to($destination)
                ->subject('Notificación desde la plataforma');
        });
        return true;
    }
}
