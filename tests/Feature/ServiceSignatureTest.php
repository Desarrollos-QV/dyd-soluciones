<?php

namespace Tests\Feature;

use App\Models\Asignaciones;
use App\Models\Cliente;
use App\Models\FirmaServicio;
use App\Models\Unidades;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ServiceSignatureTest extends TestCase
{
    protected $tecnico;
    protected $adminUser;
    protected $cliente;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('role', 'admin')->first() ?: User::first();

        // 1. Crear Técnico de Prueba
        $this->tecnico = User::create([
            'name' => 'Técnico Campo',
            'lastname' => 'Instalador',
            'email' => 'tecnico_campo_' . uniqid() . '@dyd.com',
            'password' => bcrypt('secret123'),
            'role' => 'tecnico',
            'permissions' => 'dashboard,servicios_agendados.index',
            'is_active' => 1,
            'schooling' => 'Técnica',
            'experience' => '2 años',
            'licence' => 'SI',
            'vehicle' => 'Moto',
            'tools' => json_encode(['Desarmador']),
            'skills' => 'GPS',
            'uniform' => 'Talla M',
        ]);

        // 2. Crear Cliente de Prueba
        $this->cliente = Cliente::create([
            'nombre' => 'Cliente Servicio ' . uniqid(),
            'email' => 'cliente_serv_' . uniqid() . '@dyd.com',
            'sex' => 'male',
            'direccion' => 'Calle Central 50',
            'numero_contacto' => '6361234567',
            'tipo_empresa' => 'Fisica',
            'empresa' => 'Transportes SA',
            'direccion_empresa' => 'Calle Central 50',
        ]);

        // 3. Crear Servicio Asignado al Técnico
        $this->service = Asignaciones::create([
            'cliente_id' => $this->cliente->id,
            'tecnico_id' => $this->tecnico->id,
            'tipo_servicio' => 'Servicio de reparacion o mantenimiento',
            'tel_contact' => '6361234567',
            'encargado_recibir' => 'Dueño',
            'location' => 'Patio Central',
            'lat' => '30.36',
            'lng' => '-107.95',
            'status' => 2, // En progreso
        ]);
    }

    protected function tearDown(): void
    {
        // Limpiar registros creados
        FirmaServicio::where('servicio_id', $this->service->id)->delete();
        $this->service->delete();
        $this->cliente->delete();
        $this->tecnico->delete();

        parent::tearDown();
    }

    /**
     * Prueba el control de acceso a la pantalla de firma (/firmar/{id})
     */
    public function test_signature_screen_access_control()
    {
        $encodedId = base64_encode((string) $this->service->id);

        // 1. Un usuario no autenticado no puede acceder (redirige al listado con aviso de no autorizado)
        $guestResponse = $this->get("/firmar/{$encodedId}");
        $guestResponse->assertRedirect(route('servicios_agendados.index'));

        // 2. Otro técnico no asignado no debe poder firmar el servicio
        $otherTecnico = User::create([
            'name' => 'Otro Técnico',
            'email' => 'otro_tec_' . uniqid() . '@dyd.com',
            'password' => bcrypt('secret123'),
            'role' => 'tecnico',
            'permissions' => 'none',
            'is_active' => 1,
        ]);

        $unauthorizedResponse = $this->actingAs($otherTecnico)->get("/firmar/{$encodedId}");
        $unauthorizedResponse->assertRedirect(route('servicios_agendados.index'));
        $otherTecnico->delete();

        // 3. El técnico asignado accede exitosamente (HTTP 200)
        $authorizedResponse = $this->actingAs($this->tecnico)->get("/firmar/{$encodedId}");
        $authorizedResponse->assertStatus(200);
    }

    /**
     * Prueba el guardado de la firma de conformidad (/firmar-conformidad)
     */
    public function test_save_signature_and_complete_service()
    {
        // Imagen PNG falsa en Base64 de 1x1 pixel
        $fakeSignatureBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $payload = [
            'service_id' => $this->service->id,
            'firma' => $fakeSignatureBase64,
            'lugar_instalacion' => 'Bajo el volante',
            'equipo_encendido' => 'Si',
            'estado_vehiculo' => 'Bueno',
            'satisfaccion' => 'Excelente',
            'comentarios' => 'Instalación completada y verificada en plataforma',
            'location' => json_encode(['lat' => 30.3667, 'lng' => -107.95]),
        ];

        $response = $this->actingAs($this->tecnico)
            ->post('/firmar-conformidad', $payload);

        // Debe responder exitosamente y generar la descarga del PDF
        $response->assertStatus(200);
        $this->assertStringStartsWith('%PDF', $response->getContent(), 'Debe generar el PDF de conformidad al terminar.');

        // Verificar que el estatus del servicio avanzó a 5 (Completado)
        $this->service->refresh();
        $this->assertEquals(5, $this->service->status, 'El servicio debe marcarse con estatus 5 (Completado).');

        // Verificar que se creó el registro de firma en la base de datos
        $firma = FirmaServicio::where('servicio_id', $this->service->id)->first();
        $this->assertNotNull($firma, 'El registro FirmaServicio debe existir en la BD.');
        $this->assertNotNull($firma->firma, 'La ruta de la firma debe estar guardada.');

        // Verificar que el archivo físico PNG de la firma fue creado en el disco
        $filePath = public_path($firma->firma);
        $this->assertFileExists($filePath, 'El archivo PNG de la firma debe existir en la carpeta de uploads.');

        // Borrar el archivo de firma de prueba creado
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
    }

    /**
     * Prueba el visor del expediente fotográfico del servicio (/servicios_agendados/seePhotoRecord/{id})
     */
    public function test_see_photo_record_view()
    {
        $encodedId = base64_encode((string) $this->service->id);

        // 1. Si no hay registro fotográfico, redirige amigablemente con aviso
        $responseNoPhotos = $this->actingAs($this->adminUser)
            ->get("/servicios_agendados/seePhotoRecord/{$encodedId}");
        $responseNoPhotos->assertRedirect();
        $responseNoPhotos->assertSessionHas('error');

        // 2. Con fotos registradas, carga la vista correctamente (HTTP 200)
        $firma = FirmaServicio::create([
            'servicio_id' => $this->service->id,
            'firma' => 'uploads/test_firma.png',
            'questions' => json_encode([]),
            'registro_fotografico' => json_encode(['lado_derecho' => 'foto1.jpg']),
            'lat' => 30.3667,
            'lng' => -107.95,
        ]);

        $responseWithPhotos = $this->actingAs($this->adminUser)
            ->get("/servicios_agendados/seePhotoRecord/{$encodedId}");
        $responseWithPhotos->assertStatus(200);

        $firma->delete();
    }

    /**
     * Prueba el guardado de firma para una Instalación nueva (con o sin fotos opcionales)
     */
    public function test_save_signature_for_new_installation_service()
    {
        $this->service->tipo_servicio = 'Instalacion nueva';
        $this->service->save();

        $fakeSignatureBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $payload = [
            'service_id' => $this->service->id,
            'firma' => $fakeSignatureBase64,
            'lugar_instalacion' => 'Tablero central',
            'equipo_encendido' => 'SI',
            'estado_vehiculo' => 'Bueno',
            'satisfaccion' => 'SI',
            'comentarios' => 'Instalación nueva completada',
            'location' => json_encode(['lat' => 30.3667, 'lng' => -107.95]),
        ];

        $response = $this->actingAs($this->tecnico)
            ->post('/firmar-conformidad', $payload);

        $response->assertStatus(200);
        $this->assertStringStartsWith('%PDF', $response->getContent(), 'Debe generar el PDF de conformidad para Instalacion nueva.');

        $this->service->refresh();
        $this->assertEquals(5, $this->service->status);

        $firma = FirmaServicio::where('servicio_id', $this->service->id)->first();
        $this->assertNotNull($firma);
        if ($firma && file_exists(public_path($firma->firma))) {
            @unlink(public_path($firma->firma));
        }
    }
}
