<?php

namespace App\Http\Controllers;

use App\Models\Unidades;
use Illuminate\Http\Request;

class UnidadesController extends Controller
{
    public function index()
    {
        $unidades = Unidades::all();
        return view('admin.unidades.index', compact('unidades'));
    }

    public function create()
    {
        $unidad = new Unidades;
        return view('admin.unidades.create', compact('unidad'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_unidad'           => 'required|string|max:100', 
            'precio'                => 'required|numeric|min:0',
            'economico'             => 'required|string|max:50',
            'placa'                 => 'required|string|max:20',
            'anio_unidad'           => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'vin'                   => 'nullable|string|max:50',
            'imei'                  => 'nullable|string|max:30',
            'sim_dvr'               => 'nullable|string|max:30',
            'marca_submarca'        => 'nullable|string|max:100',
            'numero_de_motor'       => 'nullable|string|max:50',
            'usuario'               => 'nullable|string|max:100',
            'password'              => 'nullable|string|max:100',
            'cuenta_con_apagado'    => 'nullable|string',
            'numero_de_emergencia'  => 'nullable|string|max:20',
        ]);

        try {

            $data = $request->all();

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
        return view('admin.unidades.edit', compact('unidad'));
    }

    public function update(Request $request, Unidades $unidade)
    {
        $request->validate([
            'tipo_unidad'           => 'required|string|max:100',
            'precio'                => 'required|numeric|min:0',
            'economico'             => 'required|string|max:50',
            'placa'                 => 'required|string|max:20',
            'anio_unidad'           => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'vin'                   => 'nullable|string|max:50',
            'imei'                  => 'nullable|string|max:30',
            'sim_dvr'               => 'nullable|string|max:30',
            'marca_submarca'        => 'nullable|string|max:100',
            'numero_de_motor'       => 'nullable|string|max:50',
            'usuario'               => 'nullable|string|max:100',
            'password'              => 'nullable|string|max:100',
            'cuenta_con_apagado'    => 'nullable|string',
            'numero_de_emergencia'  => 'nullable|string|max:20',
        ]);

        try {
            $data = $request->all();
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
                'message' => 'Error al crear el inventario: ' . $e->getMessage(),
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
}