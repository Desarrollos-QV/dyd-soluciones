<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProspectEvent;
use App\Models\Notification;
use App\Traits\NotifiesUsers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NotifySellerUpcomingMeetings extends Command
{
    use NotifiesUsers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prospects:notify-meetings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifica a los vendedores sobre sus reuniones de hoy y mañana';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Iniciando notificación de reuniones de prospectos: ' . now());

        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // 1. Obtener reuniones de HOY
        $eventsToday = ProspectEvent::whereDate('start', $today)
            ->with('prospect')
            ->get();

        foreach ($eventsToday as $event) {
            if ($event->prospect && $event->prospect->sellers_id) {
                $time = $event->start->format('g:i A');
                $this->notifySeller(
                    $event->prospect->sellers_id,
                    'reunion_hoy',
                    'Reunión Pendiente',
                    "Tienes una reunión pendiente hoy a las {$time} con {$event->prospect->name_prospect}. Detalles: {$event->description}",
                    ['event_id' => $event->id],
                    route('sellers.kanban.index'),
                    now()->addDay()
                );
                Log::info("Notificación enviada a seller {$event->prospect->sellers_id} para reunión hoy.");
            }
        }

        // 2. Obtener reuniones de MAÑANA
        $eventsTomorrow = ProspectEvent::whereDate('start', $tomorrow)
            ->with('prospect')
            ->get();

        foreach ($eventsTomorrow as $event) {
            if ($event->prospect && $event->prospect->sellers_id) {
                $time = $event->start->format('g:i A');
                $this->notifySeller(
                    $event->prospect->sellers_id,
                    'reunion_manana',
                    'Reunión Mañana',
                    "Tienes una reunión mañana a las {$time} con {$event->prospect->name_prospect}. Detalles: {$event->description}",
                    ['event_id' => $event->id],
                    route('sellers.kanban.index'),
                    now()->addDays(2)
                );
                Log::info("Notificación enviada a seller {$event->prospect->sellers_id} para reunión mañana.");
            }
        }

        Log::info('Proceso de notificación de reuniones finalizado.');

        return Command::SUCCESS;
    }
}
