<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Str;


use Illuminate\Support\Facades\Auth;
class ClienteController extends Controller
{
 
    
    public function index()
    {
        $clientes = Cliente::orderBy('created_at','DESC')->with([
            'unidades',
            'unidades.simcontrol',
            'unidades.device'])->get();
       
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
                $filename = time() . '_identificacion_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/identificaciones'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
                $data['identificacion'] = 'uploads/identificaciones/' . $filename; // Ruta relativa para guardar en la base de datos
            }

            // Manejo de Documentacion Adicional
            if ($request->hasFile('comprobante_domicilio')) {
                $file = $request->file('comprobante_domicilio');
                $filename = time() . '_comprobante_domicilio_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/documentacion'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
                $data['comprobante_domicilio'] = 'uploads/documentacion/' . $filename; // Ruta relativa para guardar en la base de datos
            }
            if ($request->hasFile('copa_factura')) {
                $file = $request->file('copa_factura');
                $filename = time() . '_factura_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/documentacion'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
                $data['copa_factura'] = 'uploads/documentacion/' . $filename; // Ruta relativa para guardar en la base de datos
            }
            if ($request->hasFile('tarjeta_circulacion')) {
                $file = $request->file('tarjeta_circulacion');
                $filename = time() . '_tarjeta_circulacion_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/documentacion'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
                $data['tarjeta_circulacion'] = 'uploads/documentacion/' . $filename; // Ruta relativa para guardar en la base de datos
            }
            if ($request->hasFile('copia_consesion')) {
                $file = $request->file('copia_consesion');
                $filename = time() . '_consesion_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/documentacion'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
                $data['copia_consesion'] = 'uploads/documentacion/' . $filename; // Ruta relativa para guardar en la base de datos
            }
            if ($request->hasFile('contrato')) {
                $file = $request->file('contrato');
                $filename = time() . '_contrato_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/documentacion'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
                $data['contrato'] = 'uploads/documentacion/' . $filename; // Ruta relativa para guardar en la base de datos
            }
            if ($request->hasFile('anexo')) {
                $file = $request->file('anexo');
                $filename = time() . '_anexo_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/documentacion'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
                $data['anexo'] = 'uploads/documentacion/' . $filename; // Ruta relativa para guardar en la base de datos
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
                $filename = time() . '_identificacion_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/identificaciones'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
                $data['identificacion'] = 'uploads/identificaciones/' . $filename; // Ruta relativa para guardar en la base de datos
            }

            // Manejo de Documentacion Adicional
            if ($request->hasFile('comprobante_domicilio')) {
                $file = $request->file('comprobante_domicilio');
                $filename = time() . '_comprobante_domicilio_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/documentacion'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
                $data['comprobante_domicilio'] = 'uploads/documentacion/' . $filename; // Ruta relativa para guardar en la base de datos
            }
            if ($request->hasFile('copa_factura')) {
                $file = $request->file('copa_factura');
                $filename = time() . '_factura_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/documentacion'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
                $data['copa_factura'] = 'uploads/documentacion/' . $filename; // Ruta relativa para guardar en la base de datos
            }
            if ($request->hasFile('tarjeta_circulacion')) {
                $file = $request->file('tarjeta_circulacion');
                $filename = time() . '_tarjeta_circulacion_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/documentacion'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
                $data['tarjeta_circulacion'] = 'uploads/documentacion/' . $filename; // Ruta relativa para guardar en la base de datos
            }
            if ($request->hasFile('copia_consesion')) {
                $file = $request->file('copia_consesion');
                $filename = time() . '_consesion_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/documentacion'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
                $data['copia_consesion'] = 'uploads/documentacion/' . $filename; // Ruta relativa para guardar en la base de datos
            }
            if ($request->hasFile('contrato')) {
                $file = $request->file('contrato');
                $filename = time() . '_contrato_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/documentacion'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
                $data['contrato'] = 'uploads/documentacion/' . $filename; // Ruta relativa para guardar en la base de datos
            }
            if ($request->hasFile('anexo')) {
                $file = $request->file('anexo');
                $filename = time() . '_anexo_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/documentacion'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
                $data['anexo'] = 'uploads/documentacion/' . $filename; // Ruta relativa para guardar en la base de datos
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

    public function downloadDocs($clienteId)
    {
        $cliente = Cliente::findOrFail($clienteId);

        $zip = new \ZipArchive();
        $zipFileName = $cliente->id.'_documentos_cliente_' . Str::slug($cliente->nombre,"_","es") . '.zip';
        $zipFilePath = public_path($zipFileName);

        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            $documentFields = [
                'identificacion',
                'comprobante_domicilio',
                'copa_factura',
                'tarjeta_circulacion',
                'copia_consesion',
                'contrato',
                'anexo'
            ];

            foreach ($documentFields as $field) {
                if ($cliente->$field && file_exists(public_path($cliente->$field))) {
                    $filePath = public_path($cliente->$field);
                    if (is_file($filePath)){
                        $zip->addFile($filePath, basename($filePath));
                    }
                }
            }

            
            $zip->close();

            if($zip->lastId == -1){
                return redirect()->back()->with('error', 'No hay documentos disponibles para descargar.');
            }
            
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } else {
            return redirect()->back()->with('error', 'No se pudo crear el archivo zip.');
        }
    }

    public function deleteSelected(Request $request)
    {
        $ids = $request->input('ids', []);

        try {
            Cliente::whereIn('id', $ids)->delete();

            // Mandamos llamar la función para eliminar los documentos asociados
            foreach ($ids as $id) {
                Cliente::deleteClientDocuments($id);
            }

            return response()->json([
                'ok' => true,
                'message' => 'Clientes eliminados con éxito.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al eliminar los clientes: ' . $e->getMessage(),
            ]);
        }
    }
}
