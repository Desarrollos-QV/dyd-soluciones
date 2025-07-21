<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index()
    {
        $inventarios = Inventario::all();
        return view('admin.inventarios.index', compact('inventarios'));
    }

    public function create()
    {
        $inventario = new Inventario;
        return view('admin.inventarios.create', compact('inventario'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'direccion' => 'required|string',
            'telefono' => 'required|string|max:20',
            'telefono_alterno' => 'nullable|string|max:20',
            'evaluacion_calidad' => 'required|in:si,no',
            'ine_comprobante' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación para imágenes
        ]);

        try {

            $data = $request->all();

            // Manejo de la imagen ine_comprobante
            if ($request->hasFile('ine_comprobante')) {
                $file = $request->file('ine_comprobante');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/ine_comprobantes'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
                $data['ine_comprobante'] = 'uploads/ine_comprobantes/' . $filename; // Ruta relativa para guardar en la base de datos
            }

            Inventario::create($data);
            return response()->json([
                'ok' => true,
                'message' => 'Elemento creado con éxito.',
                'code' => 200,
                'redirect' => route('inventarios.index')
            ]);
            // return redirect()->route('inventarios.index')->with('success', 'Inventario creado exitosamente.');
        } catch (\Exception $e) {
            // return redirect()->route('inventarios.index')->with('error', 'Error al crear el inventario: ' . $e->getMessage());
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear el inventario: ' . $e->getMessage(),
            ]);
        }
    }

    public function edit(Inventario $inventario)
    {
        return view('admin.inventarios.edit', compact('inventario'));
    }

    public function update(Request $request, Inventario $inventario)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'direccion' => 'required|string',
            'telefono' => 'required|string|max:20',
            'telefono_alterno' => 'nullable|string|max:20',
            'evaluacion_calidad' => 'required|in:si,no',
            'ine_comprobante' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación para imágenes
        ]);

        try {
            $data = $request->all();

            // Manejo de la imagen ine_comprobante
            if ($request->hasFile('ine_comprobante')) {
                // Eliminar el archivo anterior si existe
                if ($inventario->ine_comprobante && file_exists(public_path($inventario->ine_comprobante))) {
                    unlink(public_path($inventario->ine_comprobante));
                }

                // Subir el nuevo archivo
                $file = $request->file('ine_comprobante');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/ine_comprobantes'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
                $data['ine_comprobante'] = 'uploads/ine_comprobantes/' . $filename; // Ruta relativa para guardar en la base de datos
            }

            $inventario->update($data);

            return response()->json([
                'ok' => true,
                'message' => 'Elemento actualizado con éxito.',
                'code' => 200,
                'redirect' => route('inventarios.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear el inventario: ' . $e->getMessage(),
            ]);
        }
    }

    public function destroy(Inventario $inventario)
    {
        try {
            $inventario->delete();
            return redirect()->route('inventarios.index')->with('success', 'Inventario eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('inventarios.index')->with('error', 'Error al eliminar el inventario: ' . $e->getMessage());
        }
    }
}