<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\{
    Tecnico,
    User,
    ServiciosAgendado,
    FirmaServicio
};

class ServicioAgendadoController extends Controller
{
    private $folder = "admin.servicios_agendados.";

    public function index(Request $r)
    {
        $query = ServiciosAgendado::with('tecnico');
        if ($r->filled('search')) {
            $q = $r->search;
            $query->where('titular', 'like', "%$q%")
                ->orWhereHas('tecnico', fn($q2) => $q2->where('name', 'like', "%$q%"));
        }
        $servicios = $query->paginate(10);

        // return response()->json([
        //     'servicios' => $servicios
        // ]);

        return view($this->folder . 'index', compact('servicios'));
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

    public function edit(ServiciosAgendado $servicios_agendado)
    {
        $tecnicos = User::where('role', 'tecnico')->get();
        // return response()->json([
        //     'servicio_agendado' => $servicios_agendado
        // ]);
        return view($this->folder . 'edit', compact('servicios_agendado', 'tecnicos'));
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
        $service = ServiciosAgendado::with('tecnico')->find($req);
        return view($this->folder . 'firma_report', compact('service'));
    }

    public function guardar(Request $request)
    {
        try {
            $request->validate([
                'firma' => 'required|string',
            ]);

            $id = $request->input('service_id');
            $service = ServiciosAgendado::find($id);

            $firmaData = $request->input('firma');
            $firma = str_replace('data:image/png;base64,', '', $firmaData);
            $firma = str_replace(' ', '+', $firma);
            $firmaImage = base64_decode($firma);
            $firmaFileName =  Str::uuid() . '.png';


            $rutaDestino = public_path('uploads/servicios/firmas');

            // Crea la carpeta si no existe
            if (!file_exists($rutaDestino)) {
                mkdir($rutaDestino, 0755, true);
            }

            // Guarda la imagen en la nueva ruta
            file_put_contents($rutaDestino . DIRECTORY_SEPARATOR . $firmaFileName, $firmaImage);

            // Si necesitas guardar la ruta relativa en la base de datos:
            $rutaRelativa = 'uploads/servicios/firmas/' . $firmaFileName;


            // Guardamos la firma del servicio
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

            $firmaService = FirmaServicio::create([
                'servicio_id' => $service->id,
                'firma' => $rutaRelativa,
                'questions' => $questions
            ]);

            $service->firma_cliente = $firmaService->id;
            $service->save();

            // Generar PDF
            $pdf = Pdf::loadView($this->folder . 'conformidad', compact('firmaService'));
            return $pdf->download("conformidad_servicio_{$service->id}.pdf");
            // return back()->with('success', 'Firma guardada correctamente.');
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);

            // return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function generarPDF($id)
    {
        $id = base64_decode($id);
        $service = ServiciosAgendado::findOrFail($id);
        $firmaService = $service->firma;

        // Generar PDF
        $pdf = Pdf::loadView($this->folder . 'conformidad', compact('service','firmaService')); 

        // Puedes guardar el PDF o forzar descarga:
        return $pdf->download("conformidad_servicio_{$service->id}.pdf");

        // O para guardar:
        // Storage::put("pdfs/conformidad_{$data->id}.pdf", $pdf->output());
        // return redirect()->back()->with('success', 'PDF generado.');
    }
}
