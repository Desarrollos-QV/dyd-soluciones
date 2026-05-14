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
        $collection = Settings::where('user_id',Auth::user()->id)->first();
        return view($this->folder , compact('collection'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'recordatorios'              => 'required|string',
                'mensajes_automaticos'       => 'required|string',
                'dias_tolerancia'            => 'required|numeric|min:0',
                'TWILIO_SID'                 => 'string',
                'TWILIO_AUTH_TOKEN'          => 'string',
                'TWILIO_PHONE'               => 'string', 
            ]);

            $collect = Settings::where('user_id',Auth::user()->id)->first();
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
            ]);
        }
 
    }

    public function sendTestNoify(Request $request)
    {
        try{
            $type = $request->input('type');
            $settings = Settings::where('user_id', Auth::user()->id)->first();
            $sendTest = new TwilioService($settings->TWILIO_SID, $settings->TWILIO_AUTH_TOKEN, $settings->TWILIO_PHONE);

            $type = $request->input('type');
            $mensaje_personalizado = $request->input('mensaje_personalizado');
            $phone_test = $request->input('phone_test');
            $email_test = $request->input('email_test');

            switch($type){
                case 'sms':
                    if($phone_test != null)
                    {
                        $req = $this->notifyUserSMS($phone_test, $mensaje_personalizado);
                    }
                    break;
                case 'whatsapp':
                    if($phone_test != null)
                    {
                        /**
                         * Enviamos la plantilla
                         * Hola Sr. Adrian Quezada, te recordamos que tienes un pago pendiente con Dyd Soluciones.
                         * Por la cantidad de : $2,500 MXN
                         * Si ya realizaste tu pago, puedes ignorar este mensaje.
                         */

                        $clientName  = "Usuario De prueba";
                        $businesName = "DYD Soluciones";
                        $amount      = "2000";
                        
                        $contentSid = config('services.twilio.template_reminder_pay',
                                                env('TWILIO_TEMPLATE_REMINDER_PAY'));

                        $req = $this->notifyUserWhatsappTemplate($phone_test, $contentSid, [
                            '1' => $clientName,
                            '2' => $businesName,
                            '3' => '$' . number_format((float) str_replace(',', '', $amount), 2).' MXN Monto demo',
                        ]);
                    }
                    break;
                case 'email':
                    if($email_test != null)
                    {
                        $req = $sendTest->sendMessageEmail($email_test, 'Email DEMO desde DYD Soluciones....');
                    }
                    break;
            }

            return response()->json([
                'req' => $request->all(),
                'success' => true,
                'data'    => $req ?? 'Enviado',
                'message' => 'Envio de prueba éxita via: '.$type.'.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}