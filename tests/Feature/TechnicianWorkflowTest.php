<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Asignaciones;
use Illuminate\Support\Facades\Hash;

class TechnicianWorkflowTest extends TestCase
{
    protected $tecnico;
    protected $cliente;
    protected $assignment;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Crear un Técnico (incluso con permissions = 'none' como ocurría antes)
        $this->tecnico = User::create([
            'name' => 'Técnico Especialista',
            'email' => 'tec_flow_' . uniqid() . '@dyd.com',
            'password' => Hash::make('password123'),
            'role' => 'tecnico',
            'permissions' => 'none',
            'is_active' => 1,
        ]);

        // 2. Crear un Cliente
        $this->cliente = Cliente::create([
            'nombre' => 'Transportes del Desierto',
            'empresa' => 'Transportes S.A.',
            'direccion' => 'Calle Central 789',
            'direccion_empresa' => 'Calle Central 789',
            'sex' => 'male',
            'tipo_empresa' => 'Moral',
            'numero_contacto' => '6365554321',
            'email' => 'cliente_tec_' . uniqid() . '@test.com',
            'estado' => 1,
        ]);

        // 3. Crear una asignación de servicio con técnico asignado (status 1 = En progreso)
        $this->assignment = Asignaciones::create([
            'cliente_id' => $this->cliente->id,
            'tecnico_id' => $this->tecnico->id,
            'tipo_servicio' => 'Instalación GPS',
            'tel_contact' => '6365554321',
            'encargado_recibir' => 'Sr. Hernández',
            'location' => 'Patio de maniobras 1',
            'lat' => '30.3667',
            'lng' => '-107.9500',
            'viaticos' => '250',
            'tipo_vehiculo' => 'Tractocamión',
            'marca' => 'Kenworth',
            'modelo' => 'T680',
            'placa' => 'KW-9988',
            'status' => 1,
        ]);
    }

    protected function tearDown(): void
    {
        if ($this->assignment && $this->assignment->exists) {
            $this->assignment->delete();
        }

        if ($this->cliente && $this->cliente->exists) {
            $this->cliente->delete();
        }

        if ($this->tecnico && $this->tecnico->exists) {
            $this->tecnico->delete();
        }

        parent::tearDown();
    }

    /**
     * Verifica que el técnico en su Dashboard ve sus métricas, menú de servicios y tabla de acciones.
     */
    public function test_technician_dashboard_displays_services_and_actions()
    {
        $response = $this->actingAs($this->tecnico)->get(route('dashboard'));

        $response->assertStatus(200);

        // 1. Debe ver el menú de servicios en la barra lateral
        $response->assertSee('Listado de servicios');
        $response->assertSee('Nuevos Servicios');
        $response->assertSee('Servicios En');

        // 2. Debe ver la tarjeta de métricas de técnico
        $response->assertSee('Servicios Asignados (Total)');
        $response->assertSee('Servicios En Curso');

        // 3. Debe ver el servicio asignado en la tabla del Dashboard
        $response->assertSee('Instalación GPS');
        $response->assertSee('Transportes Del Desierto');

        // 4. Debe contar con botones de acción rápida para atender o firmar
        $response->assertSee(route('servicios_agendados.edit', $this->assignment->id));
        $response->assertSee(route('servicios_agendados.firmar', ['id' => base64_encode($this->assignment->id)]));
    }

    /**
     * Verifica que el técnico puede navegar libremente por sus módulos de servicios asignados sin bloqueos de permisos.
     */
    public function test_technician_can_access_servicios_agendados_views()
    {
        // 1. Acceso a Nuevos Servicios
        $responseIndex = $this->actingAs($this->tecnico)->get(route('servicios_agendados.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Instalación GPS');

        // 2. Acceso a Servicios En Curso
        $responseProgress = $this->actingAs($this->tecnico)->get(route('servicios_agendados.inprogress'));
        $responseProgress->assertStatus(200);
        $responseProgress->assertSee('Instalación GPS');

        // 3. Acceso a Ver / Atender Detalle del Servicio
        $responseEdit = $this->actingAs($this->tecnico)->get(route('servicios_agendados.edit', $this->assignment->id));
        $responseEdit->assertStatus(200);

        // 4. Acceso a la pantalla de Firma
        $encodedId = base64_encode((string) $this->assignment->id);
        $responseFirmar = $this->actingAs($this->tecnico)->get(route('servicios_agendados.firmar', $encodedId));
        $responseFirmar->assertStatus(200);
    }
}
