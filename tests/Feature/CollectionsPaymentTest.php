<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Collection;
use App\Models\CollectionPayment;
use App\Models\HistorialCaja;
use App\Models\Unidades;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class CollectionsPaymentTest extends TestCase
{
    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::where('role', 'admin')->first() ?: User::first();
    }

    /**
     * Prueba el flujo completo de pago:
     * 1. Registrar pago
     * 2. Verificar actualización de estado a 'paid'
     * 3. Verificar incremento de fecha_cobro en la unidad
     * 4. Verificar registro de ingreso en historial_caja
     * 5. Verificar creación de comprobante en collections_payments
     * 6. Deshacer el pago y verificar reversión completa
     */
    public function test_complete_payment_and_undo_lifecycle()
    {
        // 1. PREPARACIÓN DE DATOS
        $cliente = Cliente::create([
            'nombre' => 'Cliente Cobranza ' . uniqid(),
            'email' => 'cobranza_' . uniqid() . '@test.com',
            'sex' => 'male',
            'direccion' => 'Calle Reforma 123',
            'numero_contacto' => '6361112233',
            'tipo_empresa' => 'Fisica',
            'empresa' => 'Empresa Cobranza',
            'direccion_empresa' => 'Av. Juarez 456',
        ]);

        $initialFechaCobro = Carbon::today()->format('Y-m-d');

        $unidad = Unidades::create([
            'cliente_id' => $cliente->id,
            'economico' => 'ECO-PAGO-' . rand(100, 999),
            'placa' => 'PAG-' . rand(100, 999),
            'tipo_unidad' => 'Automovil',
            'marca' => 'Chevrolet',
            'submarca' => 'Aveo',
            'fecha_instalacion' => Carbon::today()->format('Y-m-d'),
            'fecha_cobro' => $initialFechaCobro,
        ]);

        $montoCobro = 450.00;

        $collection = Collection::create([
            'cliente_id' => $cliente->id,
            'unidad_id' => $unidad->id,
            'due_date' => $initialFechaCobro,
            'amount' => $montoCobro,
            'status' => 'pending',
        ]);

        // 2. EJECUTAR REGISTRO DE PAGO
        $payResponse = $this->actingAs($this->adminUser)
            ->postJson("/collections/{$collection->id}/pay");

        $payResponse->assertStatus(200);
        $payResponse->assertJson([
            'status' => true,
        ]);

        // 3. VERIFICACIONES DE PAGO
        $collection->refresh();
        $this->assertEquals('paid', $collection->status, 'El estatus del cobro debe ser paid.');
        $this->assertNotNull($collection->paid_at, 'La fecha paid_at debe registrarse.');

        // Verificar comprobante de pago
        $payment = CollectionPayment::where('collection_id', $collection->id)->first();
        $this->assertNotNull($payment, 'Debe existir un registro en collections_payments.');
        $this->assertEquals($montoCobro, (float) $payment->amount);

        // Verificar que la fecha de cobro de la unidad se haya aplazado +1 mes
        $unidad->refresh();
        $expectedNextMonth = Carbon::today()->addMonth()->format('Y-m-d');
        $this->assertEquals(
            $expectedNextMonth,
            Carbon::parse($unidad->fecha_cobro)->format('Y-m-d'),
            'La fecha de cobro de la unidad debe avanzar 1 mes.'
        );

        // Verificar que se registró el ingreso en caja chica
        $historialCaja = HistorialCaja::where('user_id', $this->adminUser->id)
            ->where('tipo', 'ingreso')
            ->where('concepto', 'Pago Mensualidad')
            ->where('monto', $montoCobro)
            ->orderBy('id', 'desc')
            ->first();

        $this->assertNotNull($historialCaja, 'Debe crearse el registro de ingreso en historial_caja.');

        // 4. EJECUTAR DESHACER PAGO (UNDO)
        $undoResponse = $this->actingAs($this->adminUser)
            ->postJson("/collections/{$collection->id}/undo-pay");

        $undoResponse->assertStatus(200);
        $undoResponse->assertJson([
            'status' => true,
        ]);

        // 5. VERIFICACIONES DE REVERSIÓN
        $collection->refresh();
        $this->assertEquals('pending', $collection->status, 'El cobro debe volver a pending.');
        $this->assertNull($collection->paid_at, 'paid_at debe restablecerse a null.');

        // Verificar que el pago se eliminó
        $deletedPayment = CollectionPayment::where('collection_id', $collection->id)->first();
        $this->assertNull($deletedPayment, 'El registro de pago debe ser eliminado tras deshacer el pago.');

        // Verificar que la fecha de la unidad se redujo 1 mes (regresó al inicio)
        $unidad->refresh();
        $this->assertEquals(
            $initialFechaCobro,
            Carbon::parse($unidad->fecha_cobro)->format('Y-m-d'),
            'La fecha de cobro de la unidad debe retroceder 1 mes al deshacer el pago.'
        );

        // 6. LIMPIEZA DE DATOS DE PRUEBA
        if ($historialCaja) {
            $historialCaja->delete();
        }
        $collection->delete();
        $unidad->delete();
        $cliente->delete();
    }

    /**
     * Prueba actualizar el teléfono de notificación de cobranza
     */
    public function test_update_phone_notification()
    {
        $cliente = Cliente::create([
            'nombre' => 'Cliente Phone ' . uniqid(),
            'email' => 'phone_' . uniqid() . '@test.com',
            'sex' => 'female',
            'direccion' => 'Calle 5a 400',
            'numero_contacto' => '6360000000',
            'tipo_empresa' => 'Fisica',
            'empresa' => 'Empresa Phone',
            'direccion_empresa' => 'Av. Central 100',
        ]);

        $unidad = Unidades::create([
            'cliente_id' => $cliente->id,
            'economico' => 'ECO-TEL-' . rand(100, 999),
            'placa' => 'TEL-' . rand(100, 999),
            'tipo_unidad' => 'Automovil',
            'marca' => 'Nissan',
            'submarca' => 'Versa',
            'fecha_instalacion' => Carbon::today()->format('Y-m-d'),
            'fecha_cobro' => Carbon::today()->format('Y-m-d'),
        ]);

        $collection = Collection::create([
            'cliente_id' => $cliente->id,
            'unidad_id' => $unidad->id,
            'due_date' => Carbon::today()->format('Y-m-d'),
            'amount' => 300.00,
            'status' => 'pending',
        ]);

        $newPhone = '6369998877';
        $response = $this->actingAs($this->adminUser)
            ->postJson("/collections/{$collection->id}/update-phone", [
                'telefono' => $newPhone,
            ]);

        $response->assertStatus(200);

        $cliente->refresh();
        $this->assertEquals($newPhone, $cliente->numero_contacto);

        // Limpieza
        $collection->delete();
        $unidad->delete();
        $cliente->delete();
    }

    /**
     * Prueba la consulta AJAX y renderizado de la tabla de cobranza
     */
    public function test_ajax_table_rendering()
    {
        $response = $this->actingAs($this->adminUser)
            ->getJson('/collections/getCollecionAll');

        $response->assertStatus(200);
        $this->assertArrayHasKey('html', $response->json(), 'La respuesta debe incluir el html renderizado de la tabla.');
    }
}
