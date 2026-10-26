<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SubaccountSecurityTest extends TestCase
{
    protected $adminUser;
    protected $createdSubaccounts = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('role', 'admin')->first();
        if (!$this->adminUser) {
            $this->adminUser = User::create([
                'name' => 'Admin Security Test',
                'email' => 'admin_sec_' . uniqid() . '@dyd.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'permissions' => 'full_access',
                'is_active' => 1,
            ]);
            $this->createdSubaccounts[] = $this->adminUser;
        }
    }

    protected function tearDown(): void
    {
        foreach ($this->createdSubaccounts as $account) {
            if ($account && $account->exists) {
                $account->delete();
            }
        }

        parent::tearDown();
    }

    /**
     * Verifica que un Administrador puede crear una subcuenta con permisos específicos.
     */
    public function test_subaccount_creation_and_permission_assignment()
    {
        $uniqueEmail = 'subadmin_' . uniqid() . '@dyd.com';

        $payload = [
            'name' => 'Subcuenta Auditoría',
            'email' => $uniqueEmail,
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'clientes' => 'on', // Solo permiso de clientes
        ];

        $response = $this->actingAs($this->adminUser)->post(route('subaccounts.store'), $payload);

        $response->assertRedirect(route('subaccounts.index'));
        $response->assertSessionHas('success');

        $subaccount = User::where('email', $uniqueEmail)->first();
        $this->assertNotNull($subaccount, 'La subcuenta debe existir en la base de datos.');
        $this->createdSubaccounts[] = $subaccount;

        $this->assertEquals('subadmin', $subaccount->role);
        $this->assertEquals($this->adminUser->id, $subaccount->parent_id);
        $this->assertStringContainsString('clientes.index', $subaccount->permissions);
        $this->assertStringNotContainsString('gastos.index', $subaccount->permissions);
    }

    /**
     * Verifica que la subcuenta puede acceder a las rutas que tiene autorizadas.
     */
    public function test_subaccount_can_access_authorized_routes()
    {
        $subaccount = User::create([
            'name' => 'Subcuenta Solo Clientes',
            'email' => 'sub_cli_' . uniqid() . '@dyd.com',
            'password' => Hash::make('secret123'),
            'role' => 'subadmin',
            'parent_id' => $this->adminUser->id,
            'permissions' => 'clientes.index,clientes.create,clientes.edit',
            'is_active' => 1,
        ]);
        $this->createdSubaccounts[] = $subaccount;

        // Debe poder entrar al dashboard (siempre permitido)
        $dashResponse = $this->actingAs($subaccount)->get(route('dashboard'));
        $dashResponse->assertStatus(200);

        // Debe poder entrar a clientes (autorizado)
        $clientesResponse = $this->actingAs($subaccount)->get(route('clientes.index'));
        $clientesResponse->assertStatus(200);
        $clientesResponse->assertViewIs('admin.clientes.index');
    }

    /**
     * Verifica que el middleware IsAdmin bloquea rutas no asignadas mostrando la vista unauthorized.
     */
    public function test_subaccount_is_blocked_from_unauthorized_routes()
    {
        $subaccount = User::create([
            'name' => 'Subcuenta Restringida',
            'email' => 'sub_rest_' . uniqid() . '@dyd.com',
            'password' => Hash::make('secret123'),
            'role' => 'subadmin',
            'parent_id' => $this->adminUser->id,
            'permissions' => 'clientes.index',
            'is_active' => 1,
        ]);
        $this->createdSubaccounts[] = $subaccount;

        // Intento de entrar a Gastos -> Denegado (admin.unauthorized)
        $gastosResponse = $this->actingAs($subaccount)->get(route('gastos.index'));
        $gastosResponse->assertViewIs('admin.unauthorized');

        // Intento de entrar a Settings -> Denegado (admin.unauthorized)
        $settingsResponse = $this->actingAs($subaccount)->get(route('settings.index'));
        $settingsResponse->assertViewIs('admin.unauthorized');

        // Intento de administrar subcuentas -> Denegado (admin.unauthorized)
        $subaccountsResponse = $this->actingAs($subaccount)->get(route('subaccounts.index'));
        $subaccountsResponse->assertViewIs('admin.unauthorized');
    }

    /**
     * Verifica que un invitado no autenticado es redirigido a login al intentar acceder a rutas protegidas.
     */
    public function test_guest_is_redirected_to_login()
    {
        $response = $this->get(route('clientes.index'));
        $response->assertRedirect('/login');

        $responseGastos = $this->get(route('gastos.index'));
        $responseGastos->assertRedirect('/login');
    }
}
