<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;


use Illuminate\Support\Facades\Auth;
class ClienteController extends Controller
{
 
    
    public function index()
    {
        $clientes = Cliente::orderBy('created_at','DESC')->get();
        return view('admin.clientes.index', compact('clientes'));
    }

    public function create(Cliente $cliente)
    {
        return view('admin.clientes.create', compact('cliente'));
    }

    public function store(Request $request)
    {
        try{
            $request->validate([
                'nombre' => 'required',
                'direccion' => 'required',
                'numero_contacto' => 'required',
                'numero_alterno' => 'required',
                'tipo_empresa' => 'required',
                'empresa' => 'required',
                'direccion_empresa' => 'required',
            ]);

            $data = $request->all();

            // Manejo de la imagen ine_comprobante
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/avatars'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
                $data['avatar'] = 'uploads/avatars/' . $filename; // Ruta relativa para guardar en la base de datos
            }

            // Manejo de la imagen ine_comprobante
            if ($request->hasFile('identificacion')) {
                $file = $request->file('identificacion');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/identificaciones'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
                $data['identificacion'] = 'uploads/identificaciones/' . $filename; // Ruta relativa para guardar en la base de datos
            }


            Cliente::create($data);
            return response()->json([
                'ok' => true,
                'message' => 'Cliente creado con éxito.',
                'code' => 200,
                'redirect' => route('clientes.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear el inventario: ' . $e->getMessage(),
            ]);
        }
    }

    public function edit(Cliente $cliente)
    {
        
        return view('admin.clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        try {
            $request->validate([
                'nombre' => 'required', 
                'direccion' => 'required',
                'numero_contacto' => 'required',
                'numero_alterno' => 'required',
                'tipo_empresa' => 'required',
                'empresa' => 'required', 
                'direccion_empresa' => 'required',
            ]);

            $data = $request->all();

            // Manejo de la imagen avatar
            if ($request->hasFile('avatar')) {
                // Eliminar el archivo anterior si existe
                if ($cliente->avatar && file_exists(public_path($cliente->avatar))) {
                    unlink(public_path($cliente->avatar));
                }

                // Subir el nuevo archivo
                $file = $request->file('avatar');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/avatars'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
                $data['avatar'] = 'uploads/avatars/' . $filename; // Ruta relativa para guardar en la base de datos
            }

            // Manejo de la imagen avatar
            if ($request->hasFile('identificacion')) {
                // Eliminar el archivo anterior si existe
                if ($cliente->identificacion && file_exists(public_path($cliente->identificacion))) {
                    unlink(public_path($cliente->identificacion));
                }

                // Subir el nuevo archivo
                $file = $request->file('identificacion');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/identificaciones'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
                $data['identificacion'] = 'uploads/identificaciones/' . $filename; // Ruta relativa para guardar en la base de datos
            }

            $cliente->update($data); 
            return response()->json([
                'ok' => true,
                'message' => 'Cliente actualizado con éxito.',
                'code' => 200,
                'redirect' => route('clientes.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear el inventario: ' . $e->getMessage(),
            ]);
        }
    }

    public function destroy(Cliente $cliente)
    {
        try {
            $cliente->delete();
            return redirect()->route('clientes.index')->with('success', 'Cliente eliminado.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ha ocurrido un problema: ' . $e->getMessage());
        }
    }
}
