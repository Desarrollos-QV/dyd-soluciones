<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use App\Models\{
    Cliente,
    Unidades,
    Collection,
    Settings,
    Sellers,
    Prospects,
    ProspectEvent,
    Notification
};

class AutomationsAndCronTest extends TestCase
{
    protected $cliente;
    protected $unidad;
    protected $seller;
    protected $prospect;
    protected $event;

    protected function setUp(): void
    {
        parent::setUp();

        // Asegurar que existan Settings configurados con mensajes automáticos apagados para la prueba
        $settings = Settings::first();
        if (!$settings) {
            Settings::create([
                'user_id' => 1,
                'recordatorios' => 'sms',
                'mensaje_personalizado' => 'Recordatorio de prueba',
                'mensajes_automaticos' => 'no',
                'dias_tolerancia' => 5,
            ]);
        } else {
            $settings->update(['mensajes_automaticos' => 'no', 'dias_tolerancia' => 5]);
        }

        // Crear Cliente de prueba
        $this->cliente = Cliente::create([
            'nombre' => 'Cliente Cron Test',
            'empresa' => 'Automations S.A.',
            'direccion' => 'Av. Tecnológico #456',
            'direccion_empresa' => 'Av. Tecnológico #456',
            'sex' => 'male',
            'tipo_empresa' => 'Moral',
            'telefono' => '6361009988',
            'numero_contacto' => '6361009988',
            'email' => 'cron_' . uniqid() . '@test.com',
            'estado' => 1,
        ]);
    }

    protected function tearDown(): void
    {
        if ($this->unidad && $this->unidad->exists) {
            Collection::where('unidad_id', $this->unidad->id)->delete();
            $this->unidad->delete();
        }

        if ($this->cliente && $this->cliente->exists) {
            $this->cliente->delete();
        }

        if ($this->event && $this->event->exists) {
            $this->event->delete();
        }

        if ($this->prospect && $this->prospect->exists) {
            $this->prospect->delete();
        }

        if ($this->seller && $this->seller->exists) {
            Notification::where('seller_id', $this->seller->id)->delete();
            $this->seller->delete();
        }

        parent::tearDown();
    }

    /**
     * Prueba el comando automatizado 'collections:process' para generar cobros pendientes.
     */
    public function test_collections_process_generates_pending_collection()
    {
        // Unidad cuya fecha de cobro vence en 2 días (dentro del rango de tolerancia de 5 días)
        $this->unidad = Unidades::create([
            'cliente_id' => $this->cliente->id,
            'economico' => 'ECO-CRON-' . rand(100, 999),
            'tipo_unidad' => 'Automovil',
            'marca' => 'Nissan',
            'submarca' => 'NP300',
            'modelo' => '2023',
            'placa' => 'CRON-' . rand(100, 999),
            'fecha_instalacion' => Carbon::today()->format('Y-m-d'),
            'fecha_cobro' => Carbon::today()->addDays(2),
            'pago_mensual' => 450,
            'tipo_pago' => 'mensual',
        ]);

        $exitCode = Artisan::call('collections:process');
        $this->assertEquals(0, $exitCode, 'El comando collections:process debe ejecutarse exitosamente con código 0.');

        // Verificar que se creó el cobro en la tabla collections
        $collection = Collection::where('unidad_id', $this->unidad->id)->first();
        $this->assertNotNull($collection, 'Debe generarse un registro de cobranza para la unidad próxima a vencer.');
        $this->assertEquals($this->cliente->id, $collection->cliente_id);
        $this->assertEquals(450, (float) $collection->amount);
        $this->assertEquals('pending', $collection->status);
        $this->assertNotNull($collection->notified_at, 'Debe marcarse la fecha notified_at para evitar bucles de notificación.');
    }

    /**
     * Prueba que el comando 'collections:process' marca como 'overdue' (vencidas) unidades pasadas.
     */
    public function test_collections_process_marks_overdue_collection()
    {
        // Unidad cuya fecha de cobro venció hace 3 días
        $this->unidad = Unidades::create([
            'cliente_id' => $this->cliente->id,
            'economico' => 'ECO-VENC-' . rand(100, 999),
            'tipo_unidad' => 'Automovil',
            'marca' => 'Ford',
            'submarca' => 'Ranger',
            'modelo' => '2022',
            'placa' => 'VENC-' . rand(100, 999),
            'fecha_instalacion' => Carbon::today()->subMonths(2)->format('Y-m-d'),
            'fecha_cobro' => Carbon::today()->subDays(3),
            'pago_mensual' => 600,
            'tipo_pago' => 'mensual',
        ]);

        $exitCode = Artisan::call('collections:process');
        $this->assertEquals(0, $exitCode);

        $collection = Collection::where('unidad_id', $this->unidad->id)->first();
        $this->assertNotNull($collection);
        $this->assertEquals('overdue', $collection->status, 'Un cobro con fecha anterior a hoy debe marcarse como overdue.');
    }

    /**
     * Prueba el comando automatizado 'prospects:notify-meetings' que notifica reuniones del día.
     */
    public function test_prospects_notify_meetings_for_today()
    {
        $this->seller = Sellers::create([
            'name' => 'Vendedor Cron',
            'email' => 'seller_cron_' . uniqid() . '@dyd.com',
            'password' => bcrypt('secret123'),
            'status' => 'active',
        ]);

        $this->prospect = Prospects::create([
            'name_company' => 'Transportes Norte',
            'name_prospect' => 'Lic. Ramos',
            'company' => 'Transportes Norte',
            'potencial' => 'Alto',
            'sellers_id' => $this->seller->id,
            'location' => 'Chihuahua',
            'contacts' => '6141234567',
            'status' => 1,
        ]);

        $this->event = ProspectEvent::create([
            'prospect_id' => $this->prospect->id,
            'title' => 'Demostración de Rastreo GPS',
            'start' => Carbon::today()->setHour(11)->setMinute(30),
            'end' => Carbon::today()->setHour(12)->setMinute(30),
            'description' => 'Presentar plataforma en sus oficinas',
        ]);

        $exitCode = Artisan::call('prospects:notify-meetings');
        $this->assertEquals(0, $exitCode, 'El comando prospects:notify-meetings debe ejecutarse con código 0.');

        // Verificar que se creó la notificación interna en la tabla notifications
        $notification = Notification::where('seller_id', $this->seller->id)
            ->where('type', 'reunion_hoy')
            ->first();

        $this->assertNotNull($notification, 'Debe crearse una notificación para el vendedor con la reunión de hoy.');
        $this->assertStringContainsString('Lic. Ramos', $notification->message);
        $this->assertEquals(route('sellers.kanban.index'), $notification->route_redirect);
    }
}
