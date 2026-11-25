<?php

namespace App\Http\Controllers;
 
use App\Services\TwilioService;
use App\Traits\NotifiesUsers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\{
    User,
    Settings
};

class SettingsController extends Controller
{
    use NotifiesUsers;
    private $folder = "admin.settings";

    public function index()
    {
        $collection = Settings::firstOrCreate(
            ['user_id' => Auth::user()->id],
            [
                'recordatorios' => 'sms',
                'mensajes_automaticos' => 'no',
                'dias_tolerancia' => 3,
                'mensaje_personalizado' => 'Estimado cliente, le recordamos su pago pendiente.',
            ]
        );
        return view($this->folder , compact('collection'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'recordatorios'              => 'required|string',
                'mensajes_automaticos'       => 'required|string',
                'dias_tolerancia'            => 'required|numeric|min:0',
                'TWILIO_SID'                 => 'nullable|string',
                'TWILIO_AUTH_TOKEN'          => 'nullable|string',
                'TWILIO_PHONE'               => 'nullable|string', 
            ]);

            $collect = Settings::where('user_id', Auth::user()->id)->first();
            if (!$collect) {
                $collect = Settings::create(['user_id' => Auth::user()->id]);
            }
            $collect->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Configuración actualizada con éxito.',
                'redirect' => route('settings.index')
            ]);
       } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
 
    }

    public function sendTestNoify(Request $request)
    {
        try {
            $type = $request->input('type');
            $settings = Settings::where('user_id', Auth::user()->id)->first() ?? Settings::first();
            
            if (!$settings) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró la configuración en la base de datos.'
                ], 422);
            }

            $sendTest = new TwilioService($settings->TWILIO_SID, $settings->TWILIO_AUTH_TOKEN, $settings->TWILIO_PHONE);

            $mensaje_personalizado = $request->input('mensaje_personalizado') ?: ($settings->mensaje_personalizado ?: 'Prueba de notificación desde DYD Soluciones.');
            $phone_test = $request->input('phone_test');
            $email_test = $request->input('email_test');

            switch ($type) {
                case 'sms':
                    if (empty($phone_test)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Por favor ingresa un número de teléfono para la prueba de SMS.'
                        ], 422);
                    }
                    if (empty($settings->TWILIO_SID) || empty($settings->TWILIO_AUTH_TOKEN)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Faltan configurar las credenciales de Twilio (Account SID y Auth Token) en los ajustes.'
                        ], 422);
                    }
                    $req = $this->notifyUserSMS($phone_test, $mensaje_personalizado);
                    break;

                case 'whatsapp':
                    if (empty($phone_test)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Por favor ingresa un número de teléfono para la prueba de WhatsApp.'
                        ], 422);
                    }
                    if (empty($settings->TWILIO_SID) || empty($settings->TWILIO_AUTH_TOKEN) || empty($settings->TWILIO_PHONE)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Faltan configurar las credenciales de Twilio (Account SID, Auth Token y Teléfono) en los ajustes.'
                        ], 422);
                    }
                    $clientName  = "Usuario De prueba";
                    $businesName = "DYD Soluciones";
                    $amount      = "2000";
                    
                    $contentSid = config('services.twilio.template_reminder_pay',
                                            env('TWILIO_TEMPLATE_REMINDER_PAY'));
                    if (empty($contentSid)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No se encontró la plantilla de WhatsApp configurada en el sistema.'
                        ], 422);
                    }

                    $req = $this->notifyUserWhatsappTemplate($phone_test, $contentSid, [
                        '1' => $clientName,
                        '2' => $businesName,
                        '3' => '$' . number_format((float) str_replace(',', '', $amount), 2).' MXN Monto demo',
                    ]);
                    break;

                case 'email':
                    if (empty($email_test) || !filter_var($email_test, FILTER_VALIDATE_EMAIL)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Por favor ingresa una dirección de correo electrónico válida para la prueba.'
                        ], 422);
                    }
                    $req = $sendTest->sendMessageEmail($email_test, 'Hola, este es un correo de prueba enviado exitosamente desde DYD Soluciones mediante la plataforma de cobranza.');
                    break;

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Tipo de prueba no reconocido: ' . $type
                    ], 422);
            }

            return response()->json([
                'success' => true,
                'data'    => $req ?? 'Enviado',
                'message' => 'Envío de prueba exitoso vía ' . strtoupper($type) . '.'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}