<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Unidades;
use App\Models\Asignaciones;
use App\Models\Devices;
use App\Models\SimControl;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MultiUnitServiceTest extends TestCase
{
    protected $admin;
    protected $tecnico;
    protected $cliente;
    protected $unidades = [];
    protected $device;
    protected $sim;
    protected $createdAssignments = [];

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Admin User
        $this->admin = User::create([
            'name' => 'Admin MultiUnit',
            'email' => 'admin_mu_' . uniqid() . '@dyd.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'permissions' => 'all',
            'is_active' => 1,
        ]);

        // 2. Tecnico User
        $this->tecnico = User::create([
            'name' => 'Tecnico MultiUnit',
            'email' => 'tec_mu_' . uniqid() . '@dyd.com',
            'password' => Hash::make('password123'),
            'role' => 'tecnico',
            'permissions' => 'none',
            'is_active' => 1,
        ]);

        // 3. Cliente
        $this->cliente = Cliente::create([
            'nombre' => 'Flotillas del Norte',
            'empresa' => 'Logística Global S.A.',
            'direccion' => 'Av. Industrial 400',
            'direccion_empresa' => 'Av. Industrial 400',
            'sex' => 'male',
            'tipo_empresa' => 'Moral',
            'numero_contacto' => '6561112233',
            'email' => 'flota_' . uniqid() . '@test.com',
            'estado' => 1,
        ]);

        // 4. Device & Sim for test
        $this->device = Devices::create([
            'type' => 'GPS',
            'dispositivo' => 'Teltonika FMB920',
            'marca' => 'Teltonika',
            'generacion' => '4G',
            'garantia' => now()->addYear(),
            'stock_min_alert' => 1,
            'stock' => 10,
        ]);

        $this->sim = SimControl::create([
            'type' => 'GPS',
            'compañia' => 'Telcel',
            'numero_sim' => '8952' . rand(10000000, 99999999),
            'numero_publico' => '636' . rand(1000000, 9999999),
        ]);

        // 5. Create 3 Units for client
        for ($i = 1; $i <= 3; $i++) {
            $this->unidades[] = Unidades::create([
                'cliente_id' => $this->cliente->id,
                'economico' => 'ECO-' . $i . '-' . rand(100, 999),
                'placa' => 'ABC-' . $i . rand(10, 99),
                'tipo_unidad' => 'Tractocamión',
                'marca' => 'Freightliner',
                'submarca' => 'Cascadia',
                'anio_unidad' => 2024,
                'fecha_instalacion' => now()->format('Y-m-d'),
                'fecha_cobro' => now()->format('Y-m-d'),
                'dispositivo_instalado' => 'Teltonika FMB920',
                'devices_id' => $this->device->id,
                'simcontrol_id' => $this->sim->id,
                'cuenta_con_apagado' => 'si',
            ]);
        }
    }

    protected function tearDown(): void
    {
        foreach ($this->createdAssignments as $assignment) {
            if ($assignment && $assignment->exists) {
                DB::table('asignacion_unidades')->where('asignacion_id', $assignment->id)->delete();
                $assignment->delete();
            }
        }

        foreach ($this->unidades as $u) {
            if ($u && $u->exists) {
                $u->delete();
            }
        }

        if ($this->sim && $this->sim->exists) {
            $this->sim->delete();
        }

        if ($this->device && $this->device->exists) {
            $this->device->delete();
        }

        if ($this->cliente && $this->cliente->exists) {
            $this->cliente->delete();
        }

        if ($this->tecnico && $this->tecnico->exists) {
            $this->tecnico->delete();
        }

        if ($this->admin && $this->admin->exists) {
            $this->admin->delete();
        }

        parent::tearDown();
    }

    public function test_admin_can_create_service_with_multiple_fleet_units()
    {
        $unitIds = array_map(fn($u) => $u->id, $this->unidades);

        $payload = [
            'cliente_id' => $this->cliente->id,
            'tecnico_id' => $this->tecnico->id,
            'tipo_servicio' => 'Instalacion nueva',
            'tel_contact' => '6561112233',
            'encargado_recibir' => 'Ing. López',
            'location' => 'Base Central Logística',
            'lat' => '31.7400',
            'lng' => '-106.4800',
            'viaticos' => '500',
            'unidad_ids' => $unitIds,
            'observaciones' => 'Instalación en las 3 unidades de la flotilla',
        ];

        $response = $this->actingAs($this->admin)->post(route('assignements.store'), $payload);

        $response->assertStatus(200);
        $response->assertJson(['ok' => true]);

        // Verify assignment created in database
        $assignment = Asignaciones::where('cliente_id', $this->cliente->id)->latest('id')->first();
        $this->assertNotNull($assignment);
        $this->createdAssignments[] = $assignment;

        // Verify primary unit compatibility
        $this->assertEquals($unitIds[0], $assignment->unidad_id);

        // Verify pivot table records
        $pivotCount = DB::table('asignacion_unidades')->where('asignacion_id', $assignment->id)->count();
        $this->assertEquals(3, $pivotCount);

        // Verify relationship returns all 3 units
        $this->assertCount(3, $assignment->unidades);
    }

    public function test_backward_compatibility_with_single_unidad_id()
    {
        $singleUnit = $this->unidades[0];

        $payload = [
            'cliente_id' => $this->cliente->id,
            'tecnico_id' => $this->tecnico->id,
            'tipo_servicio' => 'Servicio de reparacion o mantenimiento',
            'tel_contact' => '6561112233',
            'encargado_recibir' => 'Ing. López',
            'location' => 'Taller de Servicio',
            'lat' => '31.7400',
            'lng' => '-106.4800',
            'unidad_id' => $singleUnit->id,
        ];

        $response = $this->actingAs($this->admin)->post(route('assignements.store'), $payload);

        $response->assertStatus(200);
        $response->assertJson(['ok' => true]);

        $assignment = Asignaciones::where('cliente_id', $this->cliente->id)->latest('id')->first();
        $this->assertNotNull($assignment);
        $this->createdAssignments[] = $assignment;

        $this->assertEquals($singleUnit->id, $assignment->unidad_id);
        $this->assertCount(1, $assignment->unidades);
    }

    public function test_admin_and_technician_views_display_all_assigned_units()
    {
        // Create multi-unit assignment
        $unitIds = array_map(fn($u) => $u->id, $this->unidades);

        $assignment = Asignaciones::create([
            'cliente_id' => $this->cliente->id,
            'tecnico_id' => $this->tecnico->id,
            'tipo_servicio' => 'Instalacion nueva',
            'tel_contact' => '6561112233',
            'encargado_recibir' => 'Ing. López',
            'location' => 'Base Central Logística',
            'lat' => '31.7400',
            'lng' => '-106.4800',
            'unidad_id' => $unitIds[0],
            'status' => 1,
        ]);
        $assignment->unidades()->sync($unitIds);
        $this->createdAssignments[] = $assignment;

        // 1. Admin Show View
        $adminView = $this->actingAs($this->admin)->get(route('assignements.show', $assignment->id));
        $adminView->assertStatus(200);
        $adminView->assertSee('Unidades / Vehículos Asignados');
        $adminView->assertSee('3 Vehículos (Flota)');
        foreach ($this->unidades as $u) {
            $adminView->assertSee($u->economico);
            $adminView->assertSee($u->placa);
        }

        // 2. Technician Service View
        $tecnicoView = $this->actingAs($this->tecnico)->get(route('servicios_agendados.edit', $assignment->id));
        $tecnicoView->assertStatus(200);
        $tecnicoView->assertSee('Flotilla: Unidades Asignadas a este Servicio');
        $tecnicoView->assertSee('3 Vehículos');
        foreach ($this->unidades as $u) {
            $tecnicoView->assertSee($u->economico);
            $tecnicoView->assertSee($u->placa);
        }
    }

    public function test_admin_can_update_multi_unit_assignment()
    {
        // Create initial assignment with 3 units
        $unitIds = array_map(fn($u) => $u->id, $this->unidades);

        $assignment = Asignaciones::create([
            'cliente_id' => $this->cliente->id,
            'tecnico_id' => $this->tecnico->id,
            'tipo_servicio' => 'Instalacion nueva',
            'encargado_recibir' => 'Ing. López',
            'location' => 'Base Central Logística',
            'lat' => '31.7400',
            'lng' => '-106.4800',
            'unidad_id' => $unitIds[0],
            'status' => 1,
        ]);
        $assignment->unidades()->sync($unitIds);
        $this->createdAssignments[] = $assignment;

        // Update to only 2 units (unit 2 and unit 3)
        $newUnitIds = [$this->unidades[1]->id, $this->unidades[2]->id];

        $updatePayload = [
            'cliente_id' => $this->cliente->id,
            'tecnico_id' => $this->tecnico->id,
            'tipo_servicio' => 'Instalacion nueva',
            'encargado_recibir' => 'Ing. López',
            'location' => 'Base Central Logística',
            'lat' => '31.7400',
            'lng' => '-106.4800',
            'unidad_ids' => $newUnitIds,
        ];

        $response = $this->actingAs($this->admin)->put(route('assignements.update', $assignment->id), $updatePayload);

        $response->assertStatus(200);
        $response->assertJson(['ok' => true]);

        $assignment->refresh();
        $this->assertEquals($newUnitIds[0], $assignment->unidad_id);
        $this->assertCount(2, $assignment->unidades);
    }

    public function test_multivehicle_pdf_generation_succeeds()
    {
        $unitIds = array_map(fn($u) => $u->id, $this->unidades);

        $assignment = Asignaciones::create([
            'cliente_id' => $this->cliente->id,
            'tecnico_id' => $this->tecnico->id,
            'tipo_servicio' => 'Instalacion nueva',
            'encargado_recibir' => 'Ing. López',
            'location' => 'Base Central Logística',
            'lat' => '31.7400',
            'lng' => '-106.4800',
            'unidad_id' => $unitIds[0],
            'status' => 4, // Finalizado
        ]);
        $assignment->unidades()->sync($unitIds);
        $this->createdAssignments[] = $assignment;

        $pdfResponse = $this->actingAs($this->admin)->get(route('servicios_agendados.generarPDF', [
            'id' => base64_encode($assignment->id)
        ]));

        $pdfResponse->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $pdfResponse->headers->get('Content-Type'));
    }
}
