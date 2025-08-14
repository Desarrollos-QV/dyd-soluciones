<?php

namespace App\Http\Controllers;
 
use App\Services\TwilioService;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\{
    User,
    Collections
};

class CollectionsController extends Controller
{
    private $folder = "admin.collections";


    public function index()
    {
        $collection = Collections::where('user_id',Auth::user()->id)->first();
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

            $collect = Collections::where('user_id',Auth::user()->id)->first();
            $collect->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Configuración actualizada con éxito.',
                'redirect' => route('collections.index')
            ]);
       } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
 
    }

    public function sendTestNoify(Request $request, $type)
    {
        try{
            $settings = Collections::where('user_id', Auth::user()->id)->first();
            $sendTest = new TwilioService($settings->TWILIO_SID, $settings->TWILIO_AUTH_TOKEN, $settings->TWILIO_PHONE);

            $phone = $request->query('phone_test');
            $email = $request->query('email_test');

            switch($type){
                case 'sms':
                    if($phone != null)
                    {
                        $req = $sendTest->sendMessageSMS($phone, 'SMS DEMO desde DYD Soluciones....');
                    }
                    break;
                case 'whatsapp':
                    if($phone != null)
                    {
                        $req = $sendTest->sendMessageWhatsapp($phone, 'Wahtsapp DEMO desde DYD Soluciones....');
                    }
                    break;
                case 'email':
                    if($email != null)
                    {
                        $req = $sendTest->sendMessageEmail($email, 'Email DEMO desde DYD Soluciones....');
                    }
                    break;
            }

            return response()->json([
                'success' => true,
                'data'    => $req,
                'message' => 'Envio de prueba éxita.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}