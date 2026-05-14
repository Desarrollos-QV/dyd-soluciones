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

        $query = Unidades::whereNotNull('fecha_cobro')
            ->whereDate('fecha_cobro', '<=', $limite)
            ->with('cliente');

        if ($query->count() === 0) {
            Log::info('✅ No hay unidades por procesar.');
            return Command::SUCCESS;
        }

        Log::info("Unidades encontradas: ". $query->count());

        $query->chunkById(100, function ($unidades) use ($hoy, $settings) {
            foreach ($unidades as $unidad) {
                Log::info("!!--------------------------------------------------------------------------!!");
            
                $fechaCobro = Carbon::parse($unidad->fecha_cobro);
                $estatus = $fechaCobro->lt($hoy) ? 'overdue' : 'pending';

                // Buscamos si ya existe un registro de cobranza activo para esta unidad
                $collection = Collection::where('unidad_id', $unidad->id)
                    ->where('status', '!=', 'paid')
                    ->first();

                if ($collection) {
                    $collection->update([
                        'cliente_id' => $unidad->cliente_id,
                        'due_date' => $fechaCobro,
                        'amount' => $unidad->pago_mensual,
                        'status' => $estatus,
                    ]);
                    Log::info("✔ Actualizado cobro para Unidad #{$unidad->id} | Cliente #{$unidad->cliente_id} | Estatus: {$estatus}");
                } else {
                    $collection = Collection::create([
                        'unidad_id' => $unidad->id,
                        'cliente_id' => $unidad->cliente_id,
                        'due_date' => $fechaCobro,
                        'amount' => $unidad->pago_mensual,
                        'status' => $estatus,
                    ]);
                    Log::info("✔ Creado nuevo cobro para Unidad #{$unidad->id} | Cliente #{$unidad->cliente_id} | Estatus: {$estatus}");
                }

                $days = Carbon::now()->diffInDays($fechaCobro, false);
            
                Log::info('Dias faltantes para esta alerta: '. $days);

                // Verificamos si ya notificamos sobre este cobro
                if (empty($collection->notified_at)) {
                    // Verificamos si estan activas las notificaciones autmaticas
                    if($settings->mensajes_automaticos == 'si'){
                        try {
                            // Notificamos al Usuario
                            if($settings->recordatorios == 'sms'){
                                $this->notifyUserSMS(
                                    $unidad->cliente->numero_contacto,
                                    $settings->mensaje_personalizado //<- Mensaje automatico
                                );
                            }else if($settings->recordatorios == 'whatsapp'){
                                $clientName  = $unidad->cliente->nombre;
                                $businesName = "DYD Soluciones";
                                $amount      = $unidad->pago_mensual;
                                
                                $contentSid = config('services.twilio.template_reminder_pay', env('TWILIO_TEMPLATE_REMINDER_PAY'));

                                $this->notifyUserWhatsappTemplate($unidad->cliente->numero_contacto, $contentSid, [
                                    '1' => $clientName,
                                    '2' => $businesName,
                                    '3' => '$' . number_format((float) str_replace(',', '', $amount), 2).' MXN',
                                ]);
                            }else if($settings->recordatorios == 'email'){
                                // TODO: Implement email sending
                            }
                        } catch (\Exception $e) {
                            Log::error("Error al notificar al cliente {$unidad->cliente_id}: " . $e->getMessage());
                        }
                    }

                    try {
                        // Notificamos al Administrador
                        $this->notifyUser(
                            1, // SuperAdmin
                            'gestion_cobranza',
                            'Gestión de cobranza',
                            "La mensualidad del cliente {$unidad->cliente->nombre} esta por vencer.",
                            json_encode(["unidad" => $unidad->id]),
                            route('collections.index'), // Redireccionamos a caja
                            now()->addDays(7)
                        );
                    } catch (\Exception $e) {
                        Log::error("Error al notificar al administrador: " . $e->getMessage());
                    }
                    
                    // Marcamos la colección como notificada. 
                    // Esto evita que vuelva a intentar enviarse repetidas veces en caso de error, 
                    // previniendo envíos múltiples y bucles infinitos en ejecuciones del cron.
                    $collection->update(['notified_at' => now()]);
                }

                Log::info("✔ Unidad #{$unidad->id} | Cliente #{$unidad->cliente_id} | {$estatus}");

            }
        });

        Log::info('🚀 Proceso de cobranza finalizado correctamente.');

        return Command::SUCCESS;
    }
}
