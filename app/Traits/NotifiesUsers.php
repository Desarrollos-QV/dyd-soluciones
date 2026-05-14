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

    /**
     * Envía un WhatsApp usando un Content Template aprobado de Twilio.
     *
     * @param  string  $destination  Número del destinatario (sin formato, se normaliza)
     * @param  string  $contentSid   SID del template en Twilio (HXxxxxxxxxxxxxxxxxxx)
     * @param  array   $variables    Variables del template ej. ['1'=>'Juan','2'=>'$1,500','3'=>'2026-06-01']
     */
    public function notifyUserWhatsappTemplate(string $destination, string $contentSid, array $variables = [])
    {
        $settings = Settings::where('user_id', 1)->first();
        $notify   = new TwilioService($settings->TWILIO_SID, $settings->TWILIO_AUTH_TOKEN, $settings->TWILIO_PHONE);
        $telefono = PhoneHelper::formatMX($destination);

        if (!$telefono) {
            Log::info("Número de teléfono inválido... sendWhatsappTemplate: " . $destination);
            throw new \Exception('Número de teléfono inválido');
        }

        Log::info("Enviando WhatsApp Template ({$contentSid}) a: " . $telefono);

        return $notify->sendWhatsappTemplate($telefono, $contentSid, $variables);
    }

    public function notifyUserSMS(string $destination, string $message)
    {
        $settings = Settings::where('user_id', 1)->first();
        $phone_sms_capabilities = config('services.twilio.phone_sms_capabilities',
                                    env('TWILIO_PHONE_SMS_CAPABILITIES'));
        // Aca forzamos a usar el PHONE de TWILIO para el envio especificamente de SMS
        $notify = new TwilioService($settings->TWILIO_SID, $settings->TWILIO_AUTH_TOKEN, $phone_sms_capabilities);
        $telefono = PhoneHelper::formatMX($destination);

        if (!$telefono) {
            Log::info("Numero de telefono invalido... sendSMS". $telefono);
            throw new \Exception('Número de teléfono inválido');
        }

        Log::info("Enviando Mensaje SMS ({$message}) a: " . $telefono);

        return $notify->sendMessageSMS($telefono, $message);
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
