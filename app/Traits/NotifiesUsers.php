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
            'seller_id'   => null,
            'type'      => $type,
            'title'     => $title,
            'message'   => $message,
            'data'      => $data,
            'route_redirect' => $route,
            'expires_at'=> $expiresAt
        ]);
    }

    public function notifySeller($sellerId, $type, $title, $message, $data = [], $route, $expiresAt = null)
    {
        return Notification::create([
            'user_id'   => null,
            'seller_id' => $sellerId,
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


        $notify->sendMessageSMS($telefono, $message);
        return true;
    }

    public function notifyUserEmail($email, $clientName, $amount, $dueDate, $message)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::info("Email inválido: " . $email);
            throw new \Exception('Dirección de correo electrónico inválida');
        }

        Log::info("Enviando correo a: " . $email);

        try {
            \Illuminate\Support\Facades\Mail::to($email)->send(
                new \App\Mail\PaymentReminderMail($clientName, $amount, $dueDate, $message)
            );
            return true;
        } catch (\Exception $e) {
            Log::error("Error enviando email: " . $e->getMessage());
            throw $e;
        }
    }

}
