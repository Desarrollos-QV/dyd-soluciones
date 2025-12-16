<?php 

namespace App\Traits;

use App\Models\Notification;
use App\Helpers\PhoneHelper;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Log;
use App\Models\{
    User,
    Settings
};

trait NotifiesUsers
{
    public function notifyUser($userId, $type, $title, $message, $data = [], $route, $expiresAt = null)
    {
        return Notification::create([
            'user_id'   => $userId,
            'type'      => $type,
            'title'     => $title,
            'message'   => $message,
            'data'      => $data,
            'route_redirect' => $route,
            'expires_at'=> $expiresAt
        ]);
    }

    public function notifyUserWhatsapp($destination, $message)
    {
        
        $settings = Settings::where('user_id', 1)->first();
        $notify = new TwilioService($settings->TWILIO_SID, $settings->TWILIO_AUTH_TOKEN, $settings->TWILIO_PHONE);
        $telefono = PhoneHelper::formatMX($destination);

        if (!$telefono) {
            Log::info("Numero de telefono invalido... sendWhatsapp". $telefono);
            throw new \Exception('Número de teléfono inválido');
        }

        Log::info("Enviando Mensaje sendWhatsapp..." . $telefono);
        
        return $notify->sendMessageWhatsapp($telefono, $message);
    }

    public function notifyUserSMS($destination, $message)
    {
        
        $settings = Settings::where('user_id', 1)->first();
        $notify = new TwilioService($settings->TWILIO_SID, $settings->TWILIO_AUTH_TOKEN, $settings->TWILIO_PHONE);
        $telefono = PhoneHelper::formatMX($destination);

        if (!$telefono) {
            Log::info("Numero de telefono invalido... sendSMS". $telefono);
            throw new \Exception('Número de teléfono inválido');
        }

        Log::info("Enviando Mensaje SMS..." . $telefono);


        return $notify->sendMessageSMS($telefono, $message);
    }

    public function notifyUserEmail($destination, $message)
    {
        
        $settings = Settings::where('user_id', 1)->first();
        $notify = new TwilioService($settings->TWILIO_SID, $settings->TWILIO_AUTH_TOKEN, $settings->TWILIO_PHONE);
        $telefono = PhoneHelper::formatMX($destination);

        if (!$telefono) {
            Log::info("Numero de telefono invalido... sendEmail". $telefono);
            throw new \Exception('Número de teléfono inválido');
        }

        Log::info("Enviando Mensaje Email..." . $telefono);

        return $notify->sendMessageEmail($telefono, $message);
    }

}
