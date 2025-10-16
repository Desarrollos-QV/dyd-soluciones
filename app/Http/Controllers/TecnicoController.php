<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
 
use App\Models\User;

class TecnicoController extends Controller
{

    

    public function index()
    {
        $tecnicos =  User::where('role', 'tecnico')->get();
        return view('admin.tecnicos.index', compact('tecnicos'));
    }

    public function create()
    {
        $tecnico = new User;
        return view('admin.tecnicos.create', compact('tecnico'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname'  => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'schooling'  => 'required|string|max:100',
            'experience'  => 'required|string|max:200',
            'licence'  => 'required|string|max:100',
            'vehicle'  => 'required|string|max:100',
            'tools'  => 'required|array',
            'skills'  => 'required|string',
            'uniform'  => 'required|string|max:100',
        ]);

        try {
            $data = $request->all();

            $permission[] = 'dashboard,clientes.index,inventario.index';
            $permission = implode(',', $permission);
            $data['password'] = Hash::make($request->password);
            $data['role'] = 'tecnico';
            $data['parent_id'] = auth()->id();
            $data['permission'] = 'none';
            $data['permissions'] = $permission;
            $data['is_active'] = 1;

            $data['tools'] = json_encode($data['tools']);

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

            User::create($data);

            return response()->json([
                'ok' => true,
                'message' => 'Técnico creado exitosamente.',
                'code' => 200,
                'redirect' => route('tecnicos.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear la unidad: ' . $e->getMessage(),
            ]);
        }
    }

    public function edit(User $tecnico)
    {
        return view('admin.tecnicos.edit', compact('tecnico'));
    }

    public function update(Request $request, User $tecnico)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tecnicos,email,' . $tecnico->id,
        ]);

        $data = $request->all();
         

       

        // Manejo de la imagen avatar
        if ($request->hasFile('avatar')) {
            // Eliminar el archivo anterior si existe
            if ($tecnico->avatar && file_exists(public_path($tecnico->avatar))) {
                unlink(public_path($tecnico->avatar));
            }

            // Subir el nuevo archivo
            $file = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
            $data['avatar'] = 'uploads/avatars/' . $filename; // Ruta relativa para guardar en la base de datos
        }else {
            $data['avatar'] = $tecnico->avatar; // Mantener la ruta actual si no se sube una nueva imagen
        }

        // Manejo de la imagen avatar
        if ($request->hasFile('identificacion')) {
            // Eliminar el archivo anterior si existe
            if ($tecnico->identificacion && file_exists(public_path($tecnico->identificacion))) {
                unlink(public_path($tecnico->identificacion));
            }

            // Subir el nuevo archivo
            $file = $request->file('identificacion');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/identificaciones'), $filename); // Guardar en la carpeta 'uploads/ine_comprobantes'
            $data['identificacion'] = 'uploads/identificaciones/' . $filename; // Ruta relativa para guardar en la base de datos
        }else {
            $data['identificacion'] = $tecnico->identificacion; // Mantener la ruta actual si no se sube una nueva imagen
        }
        
        if ($request->password != null && !Hash::check($request->password, $tecnico->password)) {
            $request->validate([
                'password' => 'required|min:6',
            ]);

            $tecnico->update([
                'name' => $request->name,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'password' => Hash::make($request->password),
                'is_active' => $request->is_active,
                'avatar' => $data['avatar'],
                'identificacion' => $data['identificacion'],
                'tools' => json_encode($data['tools'])
            ]);
        } else {
            $tecnico->update([
                'name' => $request->name,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'is_active' => $request->is_active,
                'avatar' => $data['avatar'],
                'identificacion' => $data['identificacion'],
                'tools' => json_encode($data['tools'])
            ]);
        }

        return redirect()->route('tecnicos.index')->with('success', 'Técnico actualizado exitosamente.');
    }

    public function destroy(User $tecnico)
    {
        $tecnico->delete();
        return redirect()->route('tecnicos.index')->with('success', 'Técnico eliminado exitosamente.');
    }
}
