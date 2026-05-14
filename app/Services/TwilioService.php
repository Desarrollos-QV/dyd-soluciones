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
        try{
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
        }catch(\Exception $e){
            throw new \Exception('Error sending WhatsApp message: ' . $e->getMessage());
        }
    }

    /**
     * Envía un mensaje de WhatsApp usando un Content Template de Twilio.
     *
     * @param  string  $destination   Número destino con formato E.164 (ej. +5216361229546)
     * @param  string  $contentSid    SID del template (ej. HXxxxxxxxxxxxxxxxxxxxx)
     * @param  array   $variables     Variables del template, ej. ['1' => 'Juan', '2' => '1500']
     * @return array
     */
    public function sendWhatsappTemplate(string $destination, string $contentSid, array $variables = []): array
    {
        try {
            $client = new \GuzzleHttp\Client([
                'verify' => false
            ]);

            $response = $client->request('POST', "https://api.twilio.com/2010-04-01/Accounts/{$this->sid}/Messages.json", [
                'auth'        => [$this->sid, $this->token],
                'form_params' => [
                    'From'             => 'whatsapp:' . $this->from,
                    'To'               => 'whatsapp:' . $destination,
                    'ContentSid'       => $contentSid,
                    'ContentVariables' => json_encode($variables),
                ],
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            throw new \Exception('Error sending WhatsApp template: ' . $e->getMessage());
        }
    }

    public function sendMessageSMS($destination, $message)
    {
        
        try{
            $client = new \GuzzleHttp\Client([
                'verify' => false // Desactiva la verificación SSL
            ]);

            $response = $client->request('POST', "https://api.twilio.com/2010-04-01/Accounts/{$this->sid}/Messages.json", [
                'auth' => [$this->sid, $this->token],
                'form_params' => [
                    'To'               => $destination,
                    'From'             => $this->from,
                    'Body'             => $message
                    // 'ContentSid'       => $contentSid,
                    // 'ContentVariables' => json_encode($variables),
                ],
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            return json_decode($response->getBody(), true);
        }catch(\Exception $e){
            return true;
            // throw new \Exception('Error sending SMS: ' . $e->getMessage());
        }
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
