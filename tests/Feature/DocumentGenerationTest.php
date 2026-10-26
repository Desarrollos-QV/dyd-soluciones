<?php

namespace Tests\Feature;

use App\Models\Asignaciones;
use App\Models\Cliente;
use App\Models\FirmaServicio;
use App\Models\Gasto;
use App\Models\HistorialCaja;
use App\Models\Unidades;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class DocumentGenerationTest extends TestCase
{
    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::where('role', 'admin')->first() ?: User::first();
    }

    /**
     * Prueba la generación y descarga del PDF de conformidad de un servicio técnico
     */
    public function test_service_pdf_generation()
    {
        $cliente = Cliente::create([
            'nombre' => 'Cliente PDF ' . uniqid(),
            'email' => 'pdf_' . uniqid() . '@test.com',
            'sex' => 'male',
            'direccion' => 'Av. Independencia 500',
            'numero_contacto' => '6361234567',
            'tipo_empresa' => 'Fisica',
            'empresa' => 'Empresa PDF',
            'direccion_empresa' => 'Av. Central 200',
        ]);

        $tecnico = User::create([
            'name' => 'Técnico PDF',
            'lastname' => 'Gómez',
            'email' => 'tecnico_pdf_' . uniqid() . '@dyd.com',
            'password' => bcrypt('password'),
            'role' => 'tecnico',
            'permissions' => 'none',
            'is_active' => 1,
            'schooling' => 'Secundaria',
            'experience' => '1 año',
            'licence' => 'SI',
            'vehicle' => 'Auto',
            'tools' => json_encode(['Pinzas']),
            'skills' => 'GPS',
            'uniform' => 'Talla L',
        ]);

        $service = Asignaciones::create([
            'cliente_id' => $cliente->id,
            'tecnico_id' => $tecnico->id,
            'tipo_servicio' => 'Instalación GPS',
            'tel_contact' => '6361234567',
            'encargado_recibir' => 'Gerente de Patio',
            'location' => 'Calle 3a #123, Casas Grandes',
            'lat' => '30.3667',
            'lng' => '-107.95',
            'status' => 5, // Completado
        ]);

        $firma = FirmaServicio::create([
            'servicio_id' => $service->id,
            'firma' => 'uploads/servicios/firmas/test_firma.png',
            'questions' => json_encode([
                'lugar_instalacion' => 'Tablero frontal',
                'equipo_encendido' => 'Si',
                'estado_vehiculo' => 'Bueno',
                'satisfaccion' => 'Excelente',
            ]),
            'registro_fotografico' => json_encode([]),
            'lat' => 30.3667,
            'lng' => -107.95,
        ]);

        // Generar PDF usando el ID codificado en base64 (como lo espera la ruta)
        $encodedId = base64_encode((string) $service->id);
        $response = $this->actingAs($this->adminUser)
            ->get("/servicios_agendados/generarPDF/{$encodedId}");

        $response->assertStatus(200);

        // Validar que el archivo devuelto sea un PDF válido
        $content = $response->getContent();
        $this->assertStringStartsWith('%PDF', $content, 'El archivo generado debe ser un PDF válido que inicie con %PDF.');

        // Limpieza
        $firma->delete();
        $service->delete();
        $tecnico->delete();
        $cliente->delete();
    }

    /**
     * Prueba la exportación del reporte de Historial de Caja a Excel (.xlsx)
     */
    public function test_historial_caja_excel_export()
    {
        $movimiento = HistorialCaja::create([
            'user_id' => $this->adminUser->id,
            'fecha' => Carbon::today()->format('Y-m-d'),
            'hora' => Carbon::now()->format('H:i:s'),
            'tipo' => 'ingreso',
            'concepto' => 'Pago Mensualidad Test Excel',
            'monto' => 500.00,
            'descripcion' => 'Movimiento de prueba para exportar a Excel',
            'autorizado_por' => $this->adminUser->id,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get('/historial-caja/exportar-excel');

        $response->assertStatus(200);
        $this->assertTrue(
            str_contains($response->headers->get('content-disposition', ''), 'Historial_Caja_'),
            'La respuesta debe contener la cabecera de descarga de Historial_Caja.'
        );

        // Limpieza
        $movimiento->delete();
    }

    /**
     * Prueba la exportación del reporte general de Gastos a Excel (.xlsx)
     */
    public function test_gastos_report_excel_export()
    {
        $gasto = Gasto::create([
            'fecha' => Carbon::today()->format('Y-m-d'),
            'hora' => Carbon::now()->format('H:i:s'),
            'autorizado_por' => 'Admin Excel',
            'monto' => 250.00,
            'descripcion' => 'Gasto test para Excel',
            'solicitado_por' => 'Técnico',
            'motivo' => 'Compra cinta de aislar',
            'tipo' => 'Operativo',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get('/reportes/exportar-excel?fecha_inicio=' . Carbon::today()->startOfMonth()->format('Y-m-d') . '&fecha_fin=' . Carbon::today()->endOfMonth()->format('Y-m-d'));

        $response->assertStatus(200);
        $this->assertTrue(
            str_contains($response->headers->get('content-disposition', ''), 'reporte-ingresos-gastos.xlsx'),
            'Debe descargarse el archivo reporte-ingresos-gastos.xlsx.'
        );

        // Limpieza
        $gasto->delete();
    }

    /**
     * Prueba la exportación de Unidades de un Cliente a Excel (.xlsx)
     */
    public function test_client_units_excel_export()
    {
        $cliente = Cliente::create([
            'nombre' => 'Cliente Excel ' . uniqid(),
            'email' => 'cliente_excel_' . uniqid() . '@test.com',
            'sex' => 'male',
            'direccion' => 'Av. Tecnológico 100',
            'numero_contacto' => '6361110000',
            'tipo_empresa' => 'Moral',
            'empresa' => 'Transportes SA',
            'direccion_empresa' => 'Parque Industrial',
        ]);

        $unidad = Unidades::create([
            'cliente_id' => $cliente->id,
            'economico' => 'ECO-EXCEL-' . rand(100, 999),
            'placa' => 'XLS-' . rand(100, 999),
            'tipo_unidad' => 'Tractocamión',
            'marca' => 'Kenworth',
            'submarca' => 'T680',
            'fecha_instalacion' => Carbon::today()->format('Y-m-d'),
            'fecha_cobro' => Carbon::today()->addMonth()->format('Y-m-d'),
            'foto_unidad' => json_encode(['no_existe.jpg']), // Probar que no falle si la foto no existe
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get("/reportes/exportar-clients?cliente_id={$cliente->id}");

        $response->assertStatus(200);
        $this->assertTrue(
            str_contains($response->headers->get('content-disposition', ''), "reporte-cliente-{$cliente->id}.xlsx"),
            'Debe descargarse el archivo con las unidades del cliente.'
        );

        // Limpieza
        $unidad->delete();
        $cliente->delete();
    }
}
