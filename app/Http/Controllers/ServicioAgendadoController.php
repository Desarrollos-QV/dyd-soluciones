<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Traits\NotifiesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\{
    Tecnico,
    User,
    Cliente,
    Asignaciones,
    ServiciosAgendado,
    FirmaServicio
};

class ServicioAgendadoController extends Controller
{
    use NotifiesUsers;
    private $folder = "admin.servicios_agendados.";

    public function index(Request $r)
    {
        // $query = Asignaciones::where('tecnico_id',Auth::user()->id)->with(['cliente','cliente.unidades','tecnico','device']);

        // if ($r->filled('search')) {
        //     $q = $r->search;
        //     $query->where('cliente', 'like', "%$q%")
        //         ->orWhereHas('tecnico', fn($q2) => $q2->where('name', 'like', "%$q%"));
        // }
        // $servicios = $query->paginate(10);

        $ListClients = Asignaciones::where('tecnico_id', Auth::user()->id)->pluck('cliente_id');
        $data = [];

        foreach ($ListClients as $key => $client) {
            $Client = Cliente::where('id', $client)->with([
                'unidades' => function ($q) {
                    $q->with('simcontrol', 'inventario', 'sensorName');
                }
            ])->first();

            $assign = Asignaciones::where('cliente_id', $client)->where('tecnico_id', Auth::user()->id)->whereStatus(0)->with('getFirma')->first();

            if (isset($assign->id)) {
                $data[] = [
                    'id' => $assign->id,
                    'tipo_servicio' => $assign->tipo_servicio,
                    'location' => $assign->location,
                    'coords' => ['lat' => $assign->lat, 'lng' => $assign->lng],
                    'viaticos' => $assign->viaticos,
                    'tipo_vehiculo' => $assign->tipo_vehiculo,
                    'marca' => $assign->marca,
                    'modelo' => $assign->modelo,
                    'placa' => $assign->placa,
                    'status' => $assign->status,
                    'firma' => $assign->getFirma,
                    'cliente' => $Client
                ];
            }
        }


        /**
         * Convertir Data a un objeto para facilitar el manejo en la vista Blade tipo  @foreach ($servicios as $s) $s->id
         */
        $dataCollection = collect($data)->map(function ($item) {
            return (object) $item;
        });


        // return response()->json([
        //     'servicios' => $dataCollection
        // ]);
        return view($this->folder . 'index', ['servicios' => $dataCollection]);
    }

    public function AssignsPerformed()
    {
        $ListClients = Asignaciones::where('tecnico_id', Auth::user()->id)->pluck('cliente_id');
        $data = [];

        foreach ($ListClients as $key => $client) {
            $Client = Cliente::where('id', $client)->with([
                'unidades' => function ($q) {
                    $q->with('simcontrol', 'inventario', 'sensorName');
                }
            ])->first();

            $assign = Asignaciones::where('cliente_id', $client)
                ->where('tecnico_id', Auth::user()->id)
                ->whereStatus(5)
                ->with('getFirma')->first();

            if (isset($assign->id)) {
                $data[] = [
                    'id' => $assign->id,
                    'tipo_servicio' => $assign->tipo_servicio,
                    'location' => $assign->location,
                    'coords' => ['lat' => $assign->lat, 'lng' => $assign->lng],
                    'viaticos' => $assign->viaticos,
                    'tipo_vehiculo' => $assign->tipo_vehiculo,
                    'marca' => $assign->marca,
                    'modelo' => $assign->modelo,
                    'placa' => $assign->placa,
                    'status' => $assign->status,
                    'firma' => $assign->getFirma,
                    'cliente' => $Client
                ];
            }
        }

        /**
         * Convertir Data a un objeto para facilitar el manejo en la vista Blade tipo  @foreach ($servicios as $s) $s->id
         */
        $dataCollection = collect($data)->map(function ($item) {
            return (object) $item;
        });

        return view($this->folder . 'index', ['servicios' => $dataCollection]);
    }

    public function AssignsInProgress()
    {
        $ListClients = Asignaciones::where('tecnico_id', Auth::user()->id)->pluck('cliente_id');
        $data = [];

        foreach ($ListClients as $key => $client) {
            $Client = Cliente::where('id', $client)->with([
                'unidades' => function ($q) {
                    $q->with('simcontrol', 'inventario', 'sensorName');
                }
            ])->first();

            $assign = Asignaciones::where('cliente_id', $client)
                ->where('tecnico_id', Auth::user()->id)
                ->whereIn('status', [1, 2, 3, 4])
                ->with('getFirma')->first();

            if (isset($assign->id)) {
                $data[] = [
                    'id' => $assign->id,
                    'tipo_servicio' => $assign->tipo_servicio,
                    'location' => $assign->location,
                    'coords' => ['lat' => $assign->lat, 'lng' => $assign->lng],
                    'viaticos' => $assign->viaticos,
                    'tipo_vehiculo' => $assign->tipo_vehiculo,
                    'marca' => $assign->marca,
                    'modelo' => $assign->modelo,
                    'placa' => $assign->placa,
                    'status' => $assign->status,
                    'firma' => $assign->getFirma,
                    'cliente' => $Client
                ];
            }
        }

        /**
         * Convertir Data a un objeto para facilitar el manejo en la vista Blade tipo  @foreach ($servicios as $s) $s->id
         */
        $dataCollection = collect($data)->map(function ($item) {
            return (object) $item;
        });

        return view($this->folder . 'index', ['servicios' => $dataCollection]);
    }

    public function create()
    {
        $tecnicos = User::where('role', 'tecnico')->get();
        return view($this->folder . 'create', compact('tecnicos'));
    }

    public function store(Request $req)
    {
        try {
            $data = $req->validate([
                'tipo_servicio' => 'required',
                'fecha' => 'required|date',
                'user_id' => 'required|exists:users,id',
                'titular' => 'required|string',
                'contacto' => 'required|string',
                'unidad' => 'required|string',
                'falla_reportada' => 'nullable|string',
                'reparacion_realizada' => 'nullable|string',
                'refacciones' => 'array',
                'refacciones.*' => 'string',
                'refacciones_cantidad' => 'array',
                'refacciones_cantidad.*' => 'integer',
                'fotos' => 'array',
                'fotos.*' => 'image|max:2048',
                'firma_cliente' => 'nullable|image|max:2048',
                'costo_instalador' => 'required|min:0',
                'gasto_adicional' => 'required|min:0',
                'saldo_favor' => 'required|min:0',
            ]);

            $costo_instalador = floatval(str_replace(',', '', $req->get('costo_instalador')));
            $data['costo_instalador'] = $costo_instalador;
            $gasto_adicional = floatval(str_replace(',', '', $req->get('gasto_adicional')));
            $data['gasto_adicional'] = $gasto_adicional;
            $saldo_favor = floatval(str_replace(',', '', $req->get('saldo_favor')));
            $data['saldo_favor'] = $saldo_favor;

            $files = [];
            if ($req->hasFile('fotos')) {
                foreach ($req->file('fotos') as $f) {
                    $nombre = time() . '_' . uniqid() . '.' . $f->getClientOriginalExtension();
                    $f->move(public_path('uploads/servicios/registro_fotografico'), $nombre); // Guardar en la carpeta 'uploads/ine_comprobantes'
                    $files[] = $nombre; // Ruta relativa para guardar en la base de datos
                }
            }

            $data['fotos'] = $files;

            if ($req->hasFile('firma_cliente')) {
                $data['firma_cliente'] = $req->file('firma_cliente')->store('servicios/firmas', 'public');
            }

            ServiciosAgendado::create($data);

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Servicio Registrado con éxito',
                'redirect' => route('servicios_agendados.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

    }

    public function edit($id)
    {
        // Single eager-loaded query: get the assignment with its cliente and the cliente's unidades
        // including nested relations (simcontrol and inventario)
        $assign = Asignaciones::with(['unidad.simcontrol', 'unidad.inventario', 'unidad.sensorName', 'getFirma'])
            ->where('id', $id)
            ->where('tecnico_id', Auth::id())
            ->firstOrFail();

        $data = [
            'id' => $assign->id,
            'tipo_servicio' => $assign->tipo_servicio,
            'location' => $assign->location,
            'coords' => ['lat' => $assign->lat, 'lng' => $assign->lng],
            'viaticos' => $assign->viaticos,
            'tipo_vehiculo' => $assign->tipo_vehiculo,
            'marca' => $assign->marca,
            'modelo' => $assign->modelo,
            'placa' => $assign->placa,
            'status' => $assign->status,
            'firma' => $assign->getFirma,
            'cliente' => $assign->cliente,
            'unidad' => $assign->unidad
        ];

        // convert to object for compatibility with existing views
        $servicios_agendado = (object) $data;

        // return response()->json($servicios_agendado);

        return view($this->folder . 'edit', compact('servicios_agendado'));
    }

    public function update(Request $req, ServiciosAgendado $servicios_agendado)
    {
        try {
            $data = $req->validate([
                'tipo_servicio' => 'required',
                'fecha' => 'required|date',
                'user_id' => 'required|exists:users,id',
                'titular' => 'required|string',
                'contacto' => 'required|string',
                'unidad' => 'required|string',
                'falla_reportada' => 'nullable|string',
                'reparacion_realizada' => 'nullable|string',
                'refacciones' => 'array',
                'refacciones.*' => 'string',
                'refacciones_cantidad' => 'array',
                'refacciones_cantidad.*' => 'integer',
                'fotos' => 'array',
                'fotos.*' => 'image|max:2048',
                'firma_cliente' => 'nullable|image|max:2048',
                'costo_instalador' => 'required|min:0',
                'gasto_adicional' => 'required|min:0',
                'saldo_favor' => 'required|min:0',
            ]);

            $costo_instalador = floatval(str_replace(',', '', $req->get('costo_instalador')));
            $data['costo_instalador'] = $costo_instalador;
            $gasto_adicional = floatval(str_replace(',', '', $req->get('gasto_adicional')));
            $data['gasto_adicional'] = $gasto_adicional;
            $saldo_favor = floatval(str_replace(',', '', $req->get('saldo_favor')));
            $data['saldo_favor'] = $saldo_favor;


            $files = $servicios_agendado->fotos ?: [];
            if ($req->hasFile('fotos')) {
                foreach ($req->file('fotos') as $f) {
                    $nombre = time() . '_' . uniqid() . '.' . $f->getClientOriginalExtension();
                    $f->move(public_path('uploads/servicios/registro_fotografico'), $nombre); // Guardar en la carpeta 'uploads/ine_comprobantes'
                    $files[] = $nombre; // Ruta relativa para guardar en la base de datos
                }
            }
            $data['fotos'] = $files;

            if ($req->hasFile('firma_cliente')) {
                $data['firma_cliente'] = $req->file('firma_cliente')->store('servicios/firmas', 'public');
            }

            $servicios_agendado->update($data);
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Servicio actualizado con éxito',
                'redirect' => route('servicios_agendados.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

    }

    public function destroy(ServiciosAgendado $servicios_agendado)
    {

        $fotos = $servicios_agendado->fotos;
        // Supón que $servicio->fotos es un array o un JSON con los nombres de los archivos
        foreach ($fotos as $foto) {
            @unlink(public_path('uploads/servicios/registro_fotografico/' . $foto));
        }

        $servicios_agendado->delete();
        return redirect()->route('servicios_agendados.index')->with('success', 'Servicio Agendado, Eliminado con éxito.');
    }

    public function deleteFile(Request $request)
    {
        $filename = $request->get('filename');
        $idService = $request->get('idService');

        $service = ServiciosAgendado::find($idService);
        // Elimina el nombre del array
        $fotos = array_filter($service->fotos, function ($foto) use ($filename) {
            return $foto !== $filename;
        });

        $service->fotos = $fotos;
        $service->save();

        @unlink(public_path('uploads/servicios/registro_fotografico/' . $filename));

        return response()->json([
            'data' => $request->all()
        ]);
    }

    public function firmar($id)
    {

        $req = base64_decode($id);
        // $service = Asignaciones::with(['cliente','tecnico','device'])->find($req);

        $assign = Asignaciones::with(['unidad.simcontrol', 'unidad.inventario', 'unidad.sensorName', 'tecnico', 'getFirma'])
            ->where('id', $req)
            ->where('tecnico_id', Auth::id())
            ->first();

        if (!$assign) {
            return redirect()->route('servicios_agendados.index')->with('error', 'Servicio no encontrado o no autorizado.');
        }

        $data = [
            'id' => $assign->id,
            'tipo_servicio' => $assign->tipo_servicio,
            'location' => $assign->location,
            'coords' => ['lat' => $assign->lat, 'lng' => $assign->lng],
            'viaticos' => $assign->viaticos,
            'tipo_vehiculo' => $assign->tipo_vehiculo,
            'marca' => $assign->marca,
            'modelo' => $assign->modelo,
            'placa' => $assign->placa,
            'status' => $assign->status,
            'tecnico' => $assign->tecnico,
            'cliente' => $assign->cliente,
            'unidad' => $assign->unidad,
            'firma' => $assign->getFirma
        ];

        // convert to object for compatibility with existing views
        $service = (object) $data;

        // return response()->json([
        //     'servicios' => $service
        // ]);
        return view($this->folder . 'firma_report', compact('service'));
    }

    public function guardar(Request $request)
    {
        try {

            // Validar todos los campos requeridos incluyendo fotos del registro fotográfico
            $validated = $request->validate([
                'firma' => 'required|string',
                'images_sides' => 'required|max:5120',
                'video_state_unit' => 'required|max:51200',
                'foto_ubicacion_dispositivo' => 'required|max:5120',
                'foto_toma_corriente' => 'required|max:5120',
                'foto_toma_tierra' => 'required|max:5120',
                'foto_coloca_relevador' => 'required|max:5120',
            ]);

            // Verificar manualmente que los archivos existen
            $fotoFields = [
                'images_sides',
                'video_state_unit',
                'foto_ubicacion_dispositivo',
                'foto_toma_corriente',
                'foto_toma_tierra',
                'foto_coloca_relevador'
            ];

            foreach ($fotoFields as $field) {
                if (!$request->hasFile($field)) {
                    return response()->json([
                        'error' => "El campo {$field} es requerido"
                    ], 422);
                }
            }

            $id = $request->input('service_id');
            $service = Asignaciones::find($id);

            if (!$service) {
                return response()->json([
                    'error' => 'Servicio no encontrado'
                ], 404);
            }

            // Crear carpeta si no existe
            $rutaDestino = public_path('uploads/servicios/registro_fotografico');
            if (!file_exists($rutaDestino)) {
                mkdir($rutaDestino, 0755, true);
            }

            // Procesar y guardar archivos de registro fotográfico
            $registro_fotografico = [];
            $fotoFields = [
                'images_sides' => '4_fotos_lados',
                'video_state_unit' => 'video_estado_unidad',
                'foto_ubicacion_dispositivo' => 'foto_ubicacion_dispositivo',
                'foto_toma_corriente' => 'foto_toma_corriente',
                'foto_toma_tierra' => 'foto_toma_tierra',
                'foto_coloca_relevador' => 'foto_coloca_relevador'
            ];

            foreach ($fotoFields as $fieldName => $displayName) {
                if ($request->hasFile($fieldName)) {
                    $file = $request->file($fieldName);
                    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($rutaDestino, $fileName);
                    $registro_fotografico[$displayName] = $fileName;
                }
            }

            // Procesar firma digital
            $firmaData = $request->input('firma');
            $firma = str_replace('data:image/png;base64,', '', $firmaData);
            $firma = str_replace(' ', '+', $firma);
            $firmaImage = base64_decode($firma);
            $firmaFileName = Str::uuid() . '.png';

            $rutaDestinoFirmas = public_path('uploads/servicios/firmas');
            if (!file_exists($rutaDestinoFirmas)) {
                mkdir($rutaDestinoFirmas, 0755, true);
            }

            file_put_contents($rutaDestinoFirmas . DIRECTORY_SEPARATOR . $firmaFileName, $firmaImage);
            $rutaRelativaFirma = 'uploads/servicios/firmas/' . $firmaFileName;

            // Procesar preguntas/respuestas
            $questions = json_encode([
                'lugar_instalacion' => $request->input('lugar_instalacion'),
                'equipo_encendido' => $request->input('equipo_encendido'),
                'estado_vehiculo' => $request->input('estado_vehiculo'),
                'cableado_bien' => $request->input('cableado_bien'),
                'fotografias_respaldo' => $request->input('fotografias_respaldo'),
                'camaras_bien' => $request->input('camaras_bien'),
                'equipo_discreto' => $request->input('equipo_discreto'),
                'boton_panico' => $request->input('boton_panico'),
                'pruebas_conectividad' => $request->input('pruebas_conectividad'),
                'satisfaccion' => $request->input('satisfaccion'),
                'aditamento' => $request->input('aditamento'),
                'tablero_armado' => $request->input('tablero_armado'),
                'manipulaciones_aceptadas' => $request->input('manipulaciones_aceptadas')
            ]);

            // Procesar ubicación
            $location = json_decode($request->input('location'), true);
            $lat = $location['lat'] ?? null;
            $lng = $location['lng'] ?? null;

            // Crear registro de firma con todas las fotos unificadas
            $firmaService = FirmaServicio::create([
                'servicio_id' => $service->id,
                'firma' => $rutaRelativaFirma,
                'questions' => $questions,
                'registro_fotografico' => json_encode($registro_fotografico),
                'lat' => $lat,
                'lng' => $lng,
                'comentarios' => $request->input('comentarios', null)
            ]);

            $service->status = 5; // Actualizar estado a 'Completado'
            $service->save();

            // Notificamos al administrador
            $this->notifyUser(
                1,
                'service_end',
                'Servicio Firmado',
                "El servicio #{$service->id} ha sido firmado y entregado por el técnico.",
                [],
                route('assignements.performed'),
                now()->addDays(7)
            );

            // Generar PDF
            $pdf = Pdf::loadView($this->folder . 'conformidad', compact('firmaService', 'service'));
            return $pdf->download("conformidad_servicio_{$service->id}.pdf");

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function generarPDF($id)
    {
        $id = base64_decode($id);
        $service = Asignaciones::findOrFail($id);
        $firmaService = $service->getFirma;

        // Generar PDF
        $pdf = Pdf::loadView($this->folder . 'conformidad', compact('service', 'firmaService'));

        // Puedes guardar el PDF o forzar descarga:
        return $pdf->download("conformidad_servicio_{$service->id}.pdf");

        // O para guardar:
        // Storage::put("pdfs/conformidad_{$data->id}.pdf", $pdf->output());
        // return redirect()->back()->with('success', 'PDF generado.');
    }

    public function seePhotoRecord($id)
    {
        $id = base64_decode($id);
        $service = Asignaciones::findOrFail($id);
        $firmaService = $service->getFirma;

        if (!$firmaService || !$firmaService->registro_fotografico) {
            return redirect()->back()->with('error', 'No hay registro fotográfico disponible para este servicio.');
        }

        $registroFotografico = json_decode($firmaService->registro_fotografico, true);

        return view($this->folder . 'see_photo_record', compact('service', 'registroFotografico'));
    }
}
