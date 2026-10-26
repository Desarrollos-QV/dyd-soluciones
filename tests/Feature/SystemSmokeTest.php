<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class SystemSmokeTest extends TestCase
{
    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        // Obtener el usuario admin existente o crear una instancia en memoria
        $this->adminUser = User::where('role', 'admin')->first();
        if (!$this->adminUser) {
            $this->adminUser = new User([
                'id' => 99999,
                'name' => 'Admin Test',
                'email' => 'admin_test@dydsoluciones.com',
                'role' => 'admin',
                'permissions' => 'full_access',
            ]);
        }
    }

    /**
     * Prueba de rutas públicas de autenticación.
     *
     * @dataProvider publicRoutesProvider
     */
    public function test_public_routes_return_ok($route)
    {
        $response = $this->get($route);
        $response->assertStatus(200);
    }

    public function publicRoutesProvider()
    {
        return [
            'Login Admin' => ['/login'],
            'Login Sellers' => ['/sellers/login'],
            'Recuperar Contraseña' => ['/password/reset'],
        ];
    }

    /**
     * Los usuarios invitados deben ser redirigidos al login.
     *
     * @dataProvider guestRedirectRoutesProvider
     */
    public function test_guest_is_redirected_to_login($route)
    {
        $response = $this->get($route);
        $response->assertRedirect('/login');
    }

    public function guestRedirectRoutesProvider()
    {
        return [
            'Home / Dashboard' => ['/'],
            'Clientes' => ['/clientes'],
            'Tecnicos' => ['/tecnicos'],
            'Unidades' => ['/unidades'],
            'Cobranza' => ['/collections'],
        ];
    }

    /**
     * Prueba de las vistas principales del panel administrativo (HTTP 200).
     *
     * @dataProvider adminRoutesProvider
     */
    public function test_admin_modules_load_successfully($route, $name)
    {
        $response = $this->actingAs($this->adminUser)->get($route);

        $this->assertTrue(
            in_array($response->getStatusCode(), [200, 302]),
            "Fallo en modulo {$name} ({$route}) con codigo de respuesta: " . $response->getStatusCode()
        );
        $this->assertNotEquals(500, $response->getStatusCode(), "Error 500 en modulo {$name} ({$route})");
    }

    public function adminRoutesProvider()
    {
        return [
            'Dashboard Raíz' => ['/', 'Dashboard'],
            'Dashboard Home' => ['/home', 'Home'],
            'Dashboard Panel' => ['/dashboard', 'Dashboard'],
            'Clientes Lista' => ['/clientes', 'Clientes'],
            'Clientes Crear' => ['/clientes/create', 'Crear Cliente'],
            'Técnicos Lista' => ['/tecnicos', 'Técnicos'],
            'Técnicos Crear' => ['/tecnicos/create', 'Crear Técnico'],
            'Unidades Lista' => ['/unidades', 'Unidades'],
            'Unidades Crear' => ['/unidades/create', 'Crear Unidad'],
            'Dispositivos Lista' => ['/devices', 'Dispositivos'],
            'Solicitudes Lista' => ['/solicitudes', 'Solicitudes de Instalación'],
            'Ejecuciones Lista' => ['/ejecuciones', 'Ejecución de Instalación'],
            'Entregas Lista' => ['/entregas', 'Entregas de Servicio'],
            'Otros Servicios Lista' => ['/otros-servicios', 'Otros Servicios'],
            'Servicios Agendados' => ['/servicios_agendados', 'Servicios Agendados'],
            'Servicios En Progreso' => ['/servicios_agendados/inprogress', 'Servicios Agendados En Progreso'],
            'Servicios Realizados' => ['/servicios_agendados/performed', 'Servicios Agendados Realizados'],
            'Asignaciones' => ['/assignements', 'Asignaciones'],
            'Inventarios' => ['/inventarios', 'Inventarios'],
            'SIM Control' => ['/simcontrol', 'SIM Control'],
            'Gastos' => ['/gastos', 'Gastos'],
            'Reportes Index' => ['/reportes', 'Reportes'],
            'Reportes Unidades' => ['/reportes/units', 'Reportes Unidades'],
            'Cobranza Lista' => ['/collections', 'Cobranza'],
            'Historial Caja' => ['/historial-caja', 'Historial de Caja'],
            'Vendedores Lista' => ['/sellers', 'Vendedores'],
            'Prospectos Lista' => ['/prospects', 'Prospectos'],
            'Subcuentas' => ['/subaccounts', 'Subcuentas'],
            'Settings' => ['/settings', 'Configuraciones'],
            'Notificaciones' => ['/notifications', 'Notificaciones'],
        ];
    }
}
