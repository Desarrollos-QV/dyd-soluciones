<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\ProspectEvent;
use App\Models\ProspectNote;
use App\Models\Prospects;
use App\Models\Sellers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class SellersKanbanTest extends TestCase
{
    protected $seller;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Prueba el inicio de sesión y cierre de sesión de vendedores (guard 'sellers')
     */
    public function test_seller_authentication_flow()
    {
        $uniqueEmail = 'vendedor_auth_' . uniqid() . '@dyd.com';
        $seller = Sellers::create([
            'name' => 'Vendedor Auth Test',
            'email' => $uniqueEmail,
            'password' => bcrypt('secret123'),
            'address' => 'Av. Tecnológico 123',
            'phone' => '6361234567',
            'level_education' => 'Licenciatura',
            'picture' => 'uploads/sellers/default.png',
        ]);

        // 1. Intento con credenciales incorrectas
        $badResponse = $this->post('/sellers/login', [
            'email' => $uniqueEmail,
            'password' => 'wrong_password',
        ]);
        $badResponse->assertSessionHasErrors('email');
        $this->assertFalse(Auth::guard('sellers')->check());

        // 2. Intento con credenciales correctas
        $goodResponse = $this->post('/sellers/login', [
            'email' => $uniqueEmail,
            'password' => 'secret123',
        ]);
        $goodResponse->assertRedirect(route('sellers.dashboard'));

        // 3. Logout
        $logoutResponse = $this->post('/sellers/logout');
        $logoutResponse->assertRedirect(route('sellers.login'));
        $this->assertFalse(Auth::guard('sellers')->check());

        // Limpieza
        $seller->delete();
    }

    /**
     * Prueba el flujo completo en el Tablero Kanban:
     * - Carga del Kanban
     * - Mover estado (update-status)
     * - Agregar notas y eventos
     * - Conversión de prospecto a cliente formal
     */
    public function test_kanban_flow_and_convert_to_client()
    {
        $seller = Sellers::create([
            'name' => 'Vendedor Kanban',
            'email' => 'vendedor_k_' . uniqid() . '@dyd.com',
            'password' => bcrypt('password123'),
            'address' => 'Calle 5a #300',
            'phone' => '6369998877',
            'level_education' => 'Preparatoria',
            'picture' => 'uploads/sellers/default.png',
        ]);

        $uniqueProspectName = 'Prospecto Ganado ' . uniqid();
        $prospect = Prospects::create([
            'name_company' => 'Transportes del Norte SA',
            'name_prospect' => $uniqueProspectName,
            'company' => 'Transportes del Norte SA',
            'potencial' => 'Alto',
            'location' => 'Parque Industrial Casas Grandes',
            'contacts' => 'Ing. Roberto Silva - 6365551122',
            'observations' => 'Interesado en 10 equipos GPS',
            'sellers_id' => $seller->id,
            'status' => 1, // En Proceso
        ]);

        // 1. Cargar el Tablero Kanban del vendedor
        $kanbanResponse = $this->actingAs($seller, 'sellers')
            ->get('/sellers/kanban');
        $kanbanResponse->assertStatus(200);

        // 2. Mover el prospecto a la columna "Concretado" (status = 2)
        $statusResponse = $this->actingAs($seller, 'sellers')
            ->postJson('/sellers/kanban/update-status', [
                'prospect_id' => $prospect->id,
                'status' => 2,
            ]);
        $statusResponse->assertStatus(200);
        $prospect->refresh();
        $this->assertEquals(2, $prospect->status, 'El prospecto debe cambiar a estatus 2 (Concretado).');

        // 3. Agregar una nota de seguimiento
        $noteResponse = $this->actingAs($seller, 'sellers')
            ->postJson('/sellers/kanban/note', [
                'prospect_id' => $prospect->id,
                'note' => 'Cliente aceptó propuesta comercial y solicitó alta.',
            ]);
        $noteResponse->assertStatus(200);
        $this->assertDatabaseHas('prospect_notes', [
            'prospect_id' => $prospect->id,
            'note' => 'Cliente aceptó propuesta comercial y solicitó alta.',
        ]);

        // 4. Agregar un evento / cita en la agenda del prospecto
        $eventResponse = $this->actingAs($seller, 'sellers')
            ->postJson('/sellers/kanban/event', [
                'prospect_id' => $prospect->id,
                'title' => 'Firma de Contrato e Instalación',
                'start' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
                'description' => 'Reunión en patio de la empresa para inicio de instalaciones.',
            ]);
        $eventResponse->assertStatus(200);
        $this->assertDatabaseHas('prospect_events', [
            'prospect_id' => $prospect->id,
            'title' => 'Firma de Contrato e Instalación',
        ]);

        // 5. Convertir prospecto a Cliente Formal
        $convertResponse = $this->actingAs($seller, 'sellers')
            ->postJson('/sellers/kanban/convert-client', [
                'prospect_id' => $prospect->id,
            ]);
        $convertResponse->assertStatus(200);

        $clientId = $convertResponse->json('client_id');
        $this->assertNotNull($clientId, 'La respuesta debe devolver el ID del cliente generado.');

        // Verificar que el cliente existe formalmente en la tabla clientes
        $cliente = Cliente::find($clientId);
        $this->assertNotNull($cliente, 'El cliente formal debe existir en la tabla clientes.');
        $this->assertEquals($uniqueProspectName, $cliente->nombre);
        $this->assertEquals('Transportes del Norte SA', $cliente->empresa);

        // Verificar que el prospecto fue archivado (status 5) para no repetirse en el Kanban
        $prospect->refresh();
        $this->assertEquals(5, $prospect->status);

        // 6. Limpieza de datos de prueba
        ProspectNote::where('prospect_id', $prospect->id)->delete();
        ProspectEvent::where('prospect_id', $prospect->id)->delete();
        if ($cliente) {
            $cliente->delete();
        }
        $prospect->delete();
        $seller->delete();
    }

    /**
     * Prueba el aislamiento y seguridad entre vendedores (un vendedor no debe poder modificar prospectos ajenos)
     */
    public function test_sellers_cannot_modify_other_sellers_prospects()
    {
        $sellerA = Sellers::create([
            'name' => 'Vendedor A',
            'email' => 'seller_a_' . uniqid() . '@dyd.com',
            'password' => bcrypt('password123'),
            'address' => 'Dir A',
            'phone' => '6361111111',
            'level_education' => 'Universidad',
            'picture' => 'uploads/sellers/default.png',
        ]);

        $sellerB = Sellers::create([
            'name' => 'Vendedor B',
            'email' => 'seller_b_' . uniqid() . '@dyd.com',
            'password' => bcrypt('password123'),
            'address' => 'Dir B',
            'phone' => '6362222222',
            'level_education' => 'Universidad',
            'picture' => 'uploads/sellers/default.png',
        ]);

        $prospectA = Prospects::create([
            'name_company' => 'Empresa A',
            'name_prospect' => 'Prospecto Exclusivo de A',
            'company' => 'Empresa A',
            'potencial' => 'Medio',
            'location' => 'Sector Norte',
            'contacts' => 'Contacto A',
            'sellers_id' => $sellerA->id,
            'status' => 1,
        ]);

        // Vendedor B intenta actualizar el estatus del prospecto del Vendedor A
        $unauthorizedResponse = $this->actingAs($sellerB, 'sellers')
            ->postJson('/sellers/kanban/update-status', [
                'prospect_id' => $prospectA->id,
                'status' => 2,
            ]);

        // Debe retornar 404 Not Found porque el controlador filtra por sellers_id
        $unauthorizedResponse->assertStatus(404);

        // Limpieza
        $prospectA->delete();
        $sellerA->delete();
        $sellerB->delete();
    }
}
