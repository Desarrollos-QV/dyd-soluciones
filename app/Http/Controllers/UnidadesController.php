<?php

namespace App\Http\Controllers;

use App\Models\{
    Unidades,
    Cliente
};
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;

class UnidadesController extends Controller
{
    public function index()
    {
        $unidades = Unidades::with('cliente')->get();
        $clientes = Cliente::all();
        return view('admin.unidades.index', compact('unidades','clientes'));
    }

    public function create()
    {
        $unidad = new Unidades;
        $clientes = Cliente::all();
        return view('admin.unidades.create', compact('unidad','clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id'            => 'required|string|max:100', 
            'economico'             => 'required|string|max:50',
            'placa'                 => 'required|string|max:50',
            'tipo_unidad'           => 'required|string|max:100',
            'fecha_instalacion'     => 'required|string',
            'anio_unidad'           => 'nullable|string|max:50',
            'marca'                 => 'nullable|string|max:100',
            'submarca'              => 'nullable|string|max:100',
            'numero_de_motor'       => 'nullable|string|max:100',
            'vin'                   => 'nullable|string|max:100',
            'imei'                  => 'nullable|string|max:100',
            'np_sim'                => 'nullable|string|max:100',
            'cuenta_con_apagado'    => 'nullable|string', 
            'numero_de_emergencia'  => 'nullable|string',
            'observaciones'         => 'nullable|string',
            'costo_plataforma'      => 'nullable|string',
            'costo_sim'             => 'nullable|string',
            'pago_mensual'          => 'nullable|string',
            'name_empresa'          => 'nullable|string',
            'credenciales_user'     => 'nullable|array',
            'credenciales_pass'     => 'nullable|array',
        ]);

        try {

            $data = $request->all();

            $data['credenciales'] = json_encode([
                'user' => $request->credenciales_user,
                'pass' => $request->credenciales_pass,
            ]);
            

            $data['fecha_instalacion'] = \Carbon\Carbon::parse($request->garantia)->format('Y-m-d');
            
            Unidades::create($data);
            return response()->json([
                'ok' => true,
                'message' => 'Elemento creado con éxito.',
                'code' => 200,
                'redirect' => route('unidades.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear la unidad: ' . $e->getMessage(),
            ]);
        }
    }

    public function edit(Unidades $unidade)
    {
        $unidad = $unidade;
        $clientes = Cliente::all();
        return view('admin.unidades.edit', compact('unidad','clientes'));
    }

    public function update(Request $request, Unidades $unidade)
    {
         $request->validate([
            'cliente_id'            => 'required|string|max:100', 
            'economico'             => 'required|string|max:50',
            'placa'                 => 'required|string|max:50',
            'tipo_unidad'           => 'required|string|max:20',
            'fecha_instalacion'     => 'required|string',
            'anio_unidad'           => 'nullable|string|max:50',
            'marca'                 => 'nullable|string|max:30',
            'submarca'              => 'nullable|string|max:30',
            'numero_de_motor'       => 'nullable|string|max:100',
            'vin'                   => 'nullable|string|max:50',
            'imei'                  => 'nullable|string|max:100',
            'np_sim'                => 'nullable|string|max:100',
            'cuenta_con_apagado'    => 'nullable|string', 
            'numero_de_emergencia'  =>  'nullable|string',
            'observaciones'         =>  'nullable|string',
            'costo_plataforma'      => 'nullable|string',
            'costo_sim'             => 'nullable|string',
            'pago_mensual'          => 'nullable|string',
            'name_empresa'          => 'nullable|string',
            'credenciales_user'     => 'nullable|array',
            'credenciales_pass'     => 'nullable|array',
        ]);

        try {
            $data = $request->all();

            $data['credenciales'] = json_encode([
                'user' => $request->credenciales_user,
                'pass' => $request->credenciales_pass,
            ]);

            $data['fecha_instalacion'] = \Carbon\Carbon::parse($request->garantia)->format('Y-m-d');

            $unidade->update($data);

            return response()->json([
                'ok' => true,
                'message' => 'Elemento actualizado con éxito.',
                'code' => 200,
                'redirect' => route('unidades.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear la unidad: ' . $e->getMessage(),
            ]);
        }
    }

    public function destroy(Unidades $unidades, $id)
    {
        try {
            $unidades = Unidades::findOrFail($id);
            // Eliminar el archivo anterior si existe
            $unidades->delete();
            return redirect()->route('unidades.index')->with('success', 'unidad eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('unidades.index')->with('error', 'Error al eliminar la unidad: ' . $e->getMessage());
        }
    }

    public function AssignClient($id , $client)
    {
        $prospect = Unidades::findOrFail($id);
        $prospect->cliente_id = $client;
        $prospect->save();

        return redirect()->route('unidades.index')->with('success', 'Cliente asignado a la Unidad exitosamente');
    }

    public function AssignDisp($id , $disp)
    {
        $prospect = Unidades::findOrFail($id);
        $prospect->dispositivo_instalado = $disp;
        $prospect->save();

        return redirect()->route('unidades.index')->with('success', 'Dispositivo asignado a la Unidad exitosamente');
    }

    public function uploads(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $names = [];
            foreach ($file as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/fotos_unidades'), $filename);
                $names[] = $filename;
            }

            return response()->json([
                'ok' => true,
                'message' => 'Archivo subido con éxito.',
                'file_name' => $names
            ]);
        } else {
            return response()->json([
                'ok' => false,
                'message' => 'No se ha subido ningún archivo.'
            ]);
        }
    }

    function fetchUploads($id)
    {
        $unidad = Unidades::findOrFail($id);
        $images = [];
        
        if ($unidad->foto_unidad) {
            $fotos = json_decode($unidad->foto_unidad);
            foreach ($fotos as $key => $foto) {
                $images[] = [
                    'id' => $key,
                    'name' => basename($foto),
                    'size' => file_exists(public_path('uploads/fotos_unidades/' . $foto)) ? filesize(public_path('uploads/fotos_unidades/' . $foto)) : 0,
                    'url' => asset('uploads/fotos_unidades/' . $foto)
                ];
            }
        }

        return response()->json([
            'images' => $images
        ]);
    }

    function deleteUploads(Request $request)
    {
        $unidad = Unidades::find($request->get('id_unidad'));
        $images = $unidad->foto_unidad ? json_decode($unidad->foto_unidad) : [];
        $images_to_keep = [];
        
        foreach($images as $index => $image)
        {
            if($image != $request->get('name'))
            {
                $images_to_keep[] = $image;
            }
            else
            {
                // Eliminar el archivo de la carpeta
                $file_path = public_path('uploads/fotos_unidades/' . $image);
                if(File::exists($file_path))
                {
                    File::delete($file_path);
                }
            }
        }

        $unidad->foto_unidad = json_encode($images_to_keep);;
        $unidad->save();

        return response()->json([
            'ok' => true,
            'message' => 'Imagen eliminada con éxito.'
        ]);
      
    }
}