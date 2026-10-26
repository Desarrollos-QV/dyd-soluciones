<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Devices;
use App\Models\Gasto;
use App\Models\Prospects;
use App\Models\Sellers;
use App\Models\SimControl;
use App\Models\Unidades;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CrudOperationsTest extends TestCase
{
    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::where('role', 'admin')->first();
        if (!$this->adminUser) {
            $this->adminUser = User::first();
        }
    }

    /**
     * Test CRUD completo para Clientes (Create, Read, Update, Delete)
     */
    public function test_crud_cliente_lifecycle()
    {
        $uniqueEmail = 'cliente_test_' . uniqid() . '@dyd.com';

        // 1. CREATE
        $payload = [
            'nombre' => 'Cliente Test Auto',
            'email' => $uniqueEmail,
            'sex' => 'male',
            'direccion' => 'Calle Principal 100',
            'numero_contacto' => '6361234567',
            'numero_alterno' => '6367654321',
            'tipo_empresa' => 'Fisica',
            'empresa' => 'Empresa Test',
            'direccion_empresa' => 'Av. Comercial 200',
        ];

        $response = $this->actingAs($this->adminUser)->post('/clientes', $payload);
        $this->assertTrue(in_array($response->getStatusCode(), [200, 302]));

        $cliente = Cliente::where('email', $uniqueEmail)->first();
        $this->assertNotNull($cliente, 'El cliente no fue creado en la base de datos.');

        // 2. READ / EDIT FORM
        $editResponse = $this->actingAs($this->adminUser)->get("/clientes/{$cliente->id}/edit");
        $editResponse->assertStatus(200);

        // 3. UPDATE
        $updatePayload = array_merge($payload, [
            'nombre' => 'Cliente Test Auto Modificado',
        ]);
        $updateResponse = $this->actingAs($this->adminUser)->put("/clientes/{$cliente->id}", $updatePayload);
        $this->assertTrue(in_array($updateResponse->getStatusCode(), [200, 302]));

        $cliente->refresh();
        $this->assertEquals('Cliente Test Auto Modificado', $cliente->nombre);

        // 4. DELETE
        $deleteResponse = $this->actingAs($this->adminUser)->post('/clientes/deleteSelected', [
            'ids' => [$cliente->id],
        ]);
        $this->assertTrue(in_array($deleteResponse->getStatusCode(), [200, 302]));
        $this->assertNull(Cliente::find($cliente->id), 'El cliente no fue eliminado de la base de datos.');
    }

    /**
     * Test CRUD completo para Técnicos (Create, Read, Update, Delete)
     */
    public function test_crud_tecnico_lifecycle()
    {
        $uniqueEmail = 'tecnico_test_' . uniqid() . '@dyd.com';

        // 1. CREATE
        $payload = [
            'name' => 'Técnico Test Auto',
            'lastname' => 'Prueba',
            'email' => $uniqueEmail,
            'telefono' => '6361112233',
            'password' => 'secret123',
            'schooling' => 'Ingeniería',
            'experience' => '3 años',
            'licence' => 'Chofer',
            'vehicle' => 'Moto',
            'tools' => ['Desarmadores', 'Pinzas', 'Multímetro'],
            'skills' => 'Instalación GPS',
            'uniform' => 'Talla M',
        ];

        $response = $this->actingAs($this->adminUser)->post('/tecnicos', $payload);
        $this->assertTrue(in_array($response->getStatusCode(), [200, 302]));

        $tecnico = User::where('email', $uniqueEmail)->first();
        $this->assertNotNull($tecnico, 'El técnico no fue creado en la base de datos.');

        // 2. READ / EDIT FORM
        $editResponse = $this->actingAs($this->adminUser)->get("/tecnicos/{$tecnico->id}/edit");
        $editResponse->assertStatus(200);

        // 3. UPDATE
        $updatePayload = array_merge($payload, [
            'name' => 'Técnico Test Modificado',
            'email' => $uniqueEmail,
        ]);
        unset($updatePayload['password']); // No modificar contraseña en update si no aplica

        $updateResponse = $this->actingAs($this->adminUser)->put("/tecnicos/{$tecnico->id}", $updatePayload);
        $this->assertTrue(in_array($updateResponse->getStatusCode(), [200, 302]));

        $tecnico->refresh();
        $this->assertEquals('Técnico Test Modificado', $tecnico->name);

        // 4. DELETE
        $deleteResponse = $this->actingAs($this->adminUser)->post('/tecnicos/deleteSelected', [
            'ids' => [$tecnico->id],
        ]);
        $this->assertTrue(in_array($deleteResponse->getStatusCode(), [200, 302]));
        $this->assertNull(User::find($tecnico->id), 'El técnico no fue eliminado de la base de datos.');
    }

    /**
     * Test CRUD completo para Gastos (Create, Read, Update, Delete)
     */
    public function test_crud_gasto_lifecycle()
    {
        $uniqueMotivo = 'Motivo Test ' . uniqid();

        // 1. CREATE
        $payload = [
            'fecha' => '2026-10-20',
            'hora' => '10:00',
            'autorizado_por' => 'Admin Test',
            'monto' => '750.00',
            'descripcion' => 'Gasto de prueba automatizada',
            'solicitado_por' => 'Técnico Test',
            'motivo' => $uniqueMotivo,
            'tipo' => 'Operativo',
        ];

        $response = $this->actingAs($this->adminUser)->post('/gastos', $payload);
        $this->assertTrue(in_array($response->getStatusCode(), [200, 302]));

        $gasto = Gasto::where('motivo', $uniqueMotivo)->first();
        $this->assertNotNull($gasto, 'El gasto no fue creado en la base de datos.');

        // 2. READ / EDIT FORM
        $editResponse = $this->actingAs($this->adminUser)->get("/gastos/{$gasto->id}/edit");
        $editResponse->assertStatus(200);

        // 3. UPDATE
        $updatePayload = array_merge($payload, [
            'descripcion' => 'Descripción modificada con éxito',
        ]);
        $updateResponse = $this->actingAs($this->adminUser)->put("/gastos/{$gasto->id}", $updatePayload);
        $this->assertTrue(in_array($updateResponse->getStatusCode(), [200, 302]));

        $gasto->refresh();
        $this->assertEquals('Descripción modificada con éxito', $gasto->descripcion);

        // 4. DELETE
        $deleteResponse = $this->actingAs($this->adminUser)->post('/gastos/deleteSelected', [
            'ids' => [$gasto->id],
        ]);
        $this->assertTrue(in_array($deleteResponse->getStatusCode(), [200, 302]));
        $this->assertNull(Gasto::find($gasto->id), 'El gasto no fue eliminado de la base de datos.');
    }

    /**
     * Test CRUD completo para Dispositivos (Create, Read, Update, Delete)
     */
    public function test_crud_device_lifecycle()
    {
        $uniqueDisp = 'Dispositivo Test ' . uniqid();

        // 1. CREATE
        $payload = [
            'type' => 'GPS',
            'dispositivo' => $uniqueDisp,
            'marca' => 'Coban',
            'generacion' => '4G',
            'imei' => ['8685' . rand(10000000000, 99999999999)],
            'garantia' => '2027-10-20',
            'stock_min_alert' => 2,
            'stock' => 1,
        ];

        $response = $this->actingAs($this->adminUser)->post('/devices', $payload);
        $this->assertTrue(in_array($response->getStatusCode(), [200, 302]));

        $device = Devices::where('dispositivo', $uniqueDisp)->first();
        $this->assertNotNull($device, 'El dispositivo no fue creado en la base de datos.');

        // 2. READ / EDIT FORM
        $editResponse = $this->actingAs($this->adminUser)->get("/devices/{$device->id}/edit");
        $editResponse->assertStatus(200);

        // 3. UPDATE
        $updatePayload = array_merge($payload, [
            'marca' => 'Coban Pro',
        ]);
        $updateResponse = $this->actingAs($this->adminUser)->put("/devices/{$device->id}", $updatePayload);
        $this->assertTrue(in_array($updateResponse->getStatusCode(), [200, 302]));

        $device->refresh();
        $this->assertEquals('Coban Pro', $device->marca);

        // 4. DELETE
        $deleteResponse = $this->actingAs($this->adminUser)->post('/devices/deleteSelected', [
            'ids' => [$device->id],
        ]);
        $this->assertTrue(in_array($deleteResponse->getStatusCode(), [200, 302]));
        $this->assertNull(Devices::find($device->id), 'El dispositivo no fue eliminado de la base de datos.');
    }

    /**
     * Test CRUD completo para Prospectos (Create, Read, Update, Delete)
     */
     public function test_crud_prospect_lifecycle()
     {
         $uniqueProspect = 'Prospecto Test ' . uniqid();
 
         // 1. CREATE
         $payload = [
             'name_company' => 'Empresa Prospecto SA',
             'name_prospect' => $uniqueProspect,
             'company' => 'Empresa Prospecto SA',
             'potencial' => 'Alto',
             'location' => 'Zona Centro',
             'contacts' => 'Juan Perez - 6361234567',
             'observations' => 'Comentarios de prueba automatizada',
             'phone' => '6361239999',
             'email' => 'prospect_' . uniqid() . '@test.com',
             'address' => 'Colonia Centro',
             'source' => 'Recomendación',
             'num_units' => 3,
             'status' => 0,
         ];
 
         $response = $this->actingAs($this->adminUser)->post('/prospects', $payload);
         $this->assertTrue(in_array($response->getStatusCode(), [200, 302]));
 
         $prospect = Prospects::where('name_prospect', $uniqueProspect)->first();
         $this->assertNotNull($prospect, 'El prospecto no fue creado en la base de datos.');
 
         // 2. READ / EDIT FORM
         $editResponse = $this->actingAs($this->adminUser)->get("/prospects/{$prospect->id}/edit");
         $editResponse->assertStatus(200);
 
         // 3. UPDATE
         $updatePayload = array_merge($payload, [
             'observations' => 'Comentarios actualizados',
         ]);
         $updateResponse = $this->actingAs($this->adminUser)->put("/prospects/{$prospect->id}", $updatePayload);
         $this->assertTrue(in_array($updateResponse->getStatusCode(), [200, 302]));
 
         $prospect->refresh();
         $this->assertEquals('Comentarios actualizados', $prospect->observations);
 
         // 4. DELETE
         $deleteResponse = $this->actingAs($this->adminUser)->post('/prospects/deleteSelected', [
             'ids' => [$prospect->id],
         ]);
         $this->assertTrue(in_array($deleteResponse->getStatusCode(), [200, 302]));
         $this->assertNull(Prospects::find($prospect->id), 'El prospecto no fue eliminado de la base de datos.');
     }
 
     /**
      * Test CRUD completo para Unidades (Create, Read, Update, Delete)
      */
     public function test_crud_unidad_lifecycle()
     {
         // Dependencias requeridas: cliente, dispositivo y SIM
         $cliente = Cliente::create([
             'nombre' => 'Cliente Para Unidad ' . uniqid(),
             'email' => 'cliente_u_' . uniqid() . '@test.com',
             'sex' => 'male',
             'direccion' => 'Dir Test',
             'numero_contacto' => '1234567890',
             'tipo_empresa' => 'Fisica',
             'empresa' => 'Empresa Test',
             'direccion_empresa' => 'Dir Emp',
         ]);

        $device = Devices::create([
            'type' => 'GPS',
            'dispositivo' => 'GPS Unidad ' . uniqid(),
            'marca' => 'Coban',
            'generacion' => '4G',
            'garantia' => now()->addYear(),
            'stock_min_alert' => 1,
            'stock' => 10,
        ]);

        $sim = SimControl::create([
            'type' => 'GPS',
            'compañia' => 'Telcel',
            'numero_sim' => '8952' . rand(10000000, 99999999),
            'numero_publico' => '636' . rand(1000000, 9999999),
        ]);

        $uniqueEco = 'ECO-' . rand(1000, 9999);

        // 1. CREATE
        $payload = [
            'cliente_id' => (string) $cliente->id,
            'economico' => $uniqueEco,
            'placa' => 'ABC-' . rand(100, 999),
            'tipo_unidad' => 'Camioneta',
            'marca' => 'Nissan',
            'submarca' => 'NP300',
            'fecha_instalacion' => now()->format('Y-m-d'),
            'fecha_cobro' => now()->addMonth()->format('Y-m-d'),
            'simcontrol_id' => (string) $sim->id,
            'devices_id' => (string) $device->id,
        ];

        $response = $this->actingAs($this->adminUser)->post('/unidades', $payload);
        $this->assertTrue(in_array($response->getStatusCode(), [200, 302]));

        $unidad = Unidades::where('economico', $uniqueEco)->first();
        if (!$unidad) {
            $this->fail("La unidad no fue creada. Respuesta: " . $response->getContent());
        }
        $this->assertNotNull($unidad);

        // 2. READ / EDIT FORM
        $editResponse = $this->actingAs($this->adminUser)->get("/unidades/{$unidad->id}/edit");
        $editResponse->assertStatus(200);

        // 3. UPDATE
        $updatePayload = array_merge($payload, [
            'marca' => 'Ford',
            'submarca' => 'Ranger',
        ]);
        $updateResponse = $this->actingAs($this->adminUser)->put("/unidades/{$unidad->id}", $updatePayload);
        $this->assertTrue(in_array($updateResponse->getStatusCode(), [200, 302]));

        $unidad->refresh();
        $this->assertEquals('Ford', $unidad->marca);

        // 4. DELETE
        $deleteResponse = $this->actingAs($this->adminUser)->post('/unidades/deleteSelected', [
            'ids' => [$unidad->id],
        ]);
        $this->assertTrue(in_array($deleteResponse->getStatusCode(), [200, 302]));
        $this->assertNull(Unidades::find($unidad->id), 'La unidad no fue eliminada de la base de datos.');

        // Limpieza de dependencias temporales
        $cliente->delete();
        $device->delete();
        $sim->delete();
    }
}
