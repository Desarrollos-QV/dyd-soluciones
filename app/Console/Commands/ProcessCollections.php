<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\NotifiesUsers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\{
    Cliente,
    Collection,
    Servicio,
    Unidades,
    Settings
};
class ProcessCollections extends Command
{
    use NotifiesUsers;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collections:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera y actualiza registros de collections para cobranza';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Iniciando proceso de Gestion de cobranza: ' . now());
        
        $settings = Settings::where('user_id', 1)->first();
        $hoy = Carbon::today();
        $alertaDias = $settings->dias_tolerancia; // días antes de vencer
        $limite = $hoy->copy()->addDays($alertaDias);

        Log::info('⏳ Iniciando procesamiento de cobranza...');
        Log::info("📅 Fecha actual: {$hoy->toDateString()}");

        $unidades = Unidades::whereNotNull('fecha_cobro')
            ->whereDate('fecha_cobro', '<=', $limite)
            ->with('cliente')
            ->get();

        if ($unidades->isEmpty()) {
            Log::info('✅ No hay unidades por procesar.');
            return Command::SUCCESS;
        }


        Log::info("Unidades encontradas: ". count($unidades));

        foreach ($unidades as $unidad) {
            Log::info("!!--------------------------------------------------------------------------!!");
            Log::info("!!--------------------------------------------------------------------------!!");
           
            $fechaCobro = Carbon::parse($unidad->fecha_cobro);

            if ($fechaCobro->lt($hoy)) {
                $estatus = 'paid'; 
            } else {
                $estatus = 'pending';
            }

            Collection::updateOrCreate(
                [
                    'unidad_id' => $unidad->id,
                    'notified_at' => $hoy
                ],
                [
                    'cliente_id' => $unidad->cliente_id,
                    'unidad_id' => $unidad->id,
                    'due_date' => $fechaCobro,
                    'amount' => $unidad->pago_mensual,
                    'status' => $estatus,
                    'notified_at' => $hoy
                ]
            );

            $days = Carbon::now()->diffInDays($fechaCobro, false);
        
            Log::info('Dias faltantes para esta alerta: '. $days);

            // Verificamos si estan activas las notificaciones autmaticas
            if($settings->mensajes_automaticos == 'si'){
                // Notificamos al Usuario
                if($settings->recordatorios == 'sms'){
                    $this->notifyUserSMS(
                        $unidad->cliente->numero_contacto,
                        $settings->mensaje_personalizado //<- Mensaje automatico
                    );
                }else if($settings->recordatorios == 'whatsapp'){
                    $this->notifyUserWhatsapp(
                        $unidad->cliente->numero_contacto,
                        $settings->mensaje_personalizado //<- Mensaje automatico
                    );
                }else if($settings->recordatorios == 'email'){
                    // $this->notifyUserEmail(
                    //     $unidad->cliente->numero_contacto,
                    //     $settings->mensaje_automatico // Falta de email
                    // );
                }
            }

            // Notificamos al Administrador
                $this->notifyUser(
                    1, // SuperAdmin
                    'gestion_cobranza',
                    'Gestión de cobranza',
                    "La mensualidad del cliente {$unidad->cliente->nombre} esta por vencer.",
                    json_encode(["unidad" => $unidad->id]),
                    route('collections.index'),
                    now()->addDays(7)
                );

            Log::info("✔ Unidad #{$unidad->id} | Cliente #{$unidad->cliente_id} | {$estatus}");

        }

        Log::info('🚀 Proceso de cobranza finalizado correctamente.');

        return Command::SUCCESS;
    }
}
