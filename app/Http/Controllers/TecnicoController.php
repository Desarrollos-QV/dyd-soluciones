<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Tecnico;
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
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
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

            User::create($data);

            return redirect()->route('tecnicos.index')->with('success', 'Técnico creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('tecnicos.index')->with('error', $e->getMessage());
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
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:tecnicos,email,' . $tecnico->id,
        ]);

        
        if ($request->password != null && !Hash::check($request->password, $tecnico->password)) {
            $request->validate([
                'password' => 'required|min:6',
            ]);

            $tecnico->update([
                'name' => $request->name,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'password' => Hash::make($request->password),
                'is_active' => $request->is_active
            ]);
        } else {
            $tecnico->update([
                'name' => $request->name,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'is_active' => $request->is_active
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
