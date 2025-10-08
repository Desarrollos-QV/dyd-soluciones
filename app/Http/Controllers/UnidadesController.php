<?php

namespace App\Http\Controllers;

use App\Models\{
    Unidades,
    Cliente
};
use Illuminate\Http\Request;

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
            'economico'             => 'required|numeric|min:0',
            'placa'                 => 'required|string|max:50',
            'tipo_unidad'           => 'required|string|max:20',
            'fecha_instalacion'     => 'required|digits:4|integer|min:1900|max:' . date('Y-m-d'),
            'anio_unidad'           => 'nullable|numeric|max:50',
            'marca'                 => 'nullable|string|max:30',
            'submarca'              => 'nullable|string|max:30',
            'numero_de_motor'       => 'nullable|string|max:100',
            'vin'                   => 'nullable|string|max:50',
            'imei'                  => 'nullable|string|max:100',
            'np_sim'                => 'nullable|string|max:100',
            'cuenta_con_apagado'    => 'nullable|string',
            'foto_unidad'           => 'nullable|string|max:20',
            'numero_de_emergencia'  =>  'nullable|string',
            'observaciones'         =>  'nullable|string',
        ]);

        try {

            $data = $request->all();
            // Manejo de la imagen avatar
            if ($request->hasFile('foto_unidad')) {
                // Subir el nuevo archivo
                $file = $request->file('foto_unidad');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/fotos_unidades'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
                $data['foto_unidad'] = 'uploads/fotos_unidades/' . $filename; // Ruta relativa para guardar en la base de datos
            }

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
            'economico'             => 'required|numeric|min:0',
            'placa'                 => 'required|string|max:50',
            'tipo_unidad'           => 'required|string|max:200',
            'fecha_instalacion'     => 'required|string|max:100',
            'anio_unidad'           => 'nullable|string|max:50',
            'marca'                 => 'nullable|string|max:30',
            'submarca'              => 'nullable|string|max:30',
            'numero_de_motor'       => 'nullable|string|max:100',
            'vin'                   => 'nullable|string|max:50',
            'imei'                  => 'nullable|string|max:100',
            'np_sim'                => 'nullable|string|max:100',
            'cuenta_con_apagado'    => 'nullable|string',
            'foto_unidad'           => 'file',
            'numero_de_emergencia'  => 'nullable|string',
            'observaciones'         => 'nullable|string',
        ]);

        try {
            $data = $request->all();

            // Manejo de la imagen avatar
            if ($request->hasFile('foto_unidad')) {
                // Eliminar el archivo anterior si existe
                if ($unidade->foto_unidad && file_exists(public_path($unidade->foto_unidad))) {
                    unlink(public_path($unidade->foto_unidad));
                }

                // Subir el nuevo archivo
                $file = $request->file('foto_unidad');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/fotos_unidades'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
                $data['foto_unidad'] = 'uploads/fotos_unidades/' . $filename; // Ruta relativa para guardar en la base de datos
            }

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

    public function destroy(Unidades $unidades)
    {
        try {
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
}