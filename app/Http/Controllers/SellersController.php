<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sellers;
use Illuminate\Support\Facades\Hash;

class SellersController extends Controller
{
    public $folder = 'admin.sellers';

    /**
     * Summary of index
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $sellers = Sellers::all();
        return view($this->folder . '.index', compact('sellers'));
    }

    /**
     * Summary of show
     * @param \App\Models\Sellers $seller
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Sellers $seller)
    {
        return view($this->folder . '.show', compact('seller'));
    }

    /**
     * Summary of create
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view($this->folder . '.create');
    }

    /**
     * Summary of store
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:sellers,email',
            'password' => 'required|string|min:6|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'level_education' => 'required|string|max:100',
            'picture' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:10240',
            'observations' => 'nullable|string'
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'address.required' => 'La dirección es obligatoria.',
            'phone.required' => 'El teléfono es obligatorio.',
            'level_education.required' => 'El nivel de estudios es obligatorio.',
            'picture.required' => 'La identificación oficial (INE) es obligatoria.',
            'picture.file' => 'La identificación oficial debe ser un archivo válido.',
            'picture.mimes' => 'La identificación oficial debe ser una imagen (JPG, PNG) o un archivo PDF.',
            'picture.max' => 'La identificación oficial no debe superar los 10 MB.',
        ]);

        try {
            $data = $request->all();
            // Manejo de la identificación oficial (imagen o PDF)
            if ($request->hasFile('picture')) {
                // Subir el nuevo archivo
                $file = $request->file('picture');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/identificaciones'), $filename);
                $data['picture'] = 'uploads/identificaciones/' . $filename;
            }

            $data['password'] = Hash::make($data['password']);

            Sellers::create($data);
            return response()->json([
                'ok' => true,
                'message' => 'Vendedor creado exitosamente',
                'code' => 200,
                'redirect' => route('sellers.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear el vendedor: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Summary of edit
     * @param \App\Models\Sellers $seller
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Sellers $seller)
    {
        return view($this->folder . '.edit', compact('seller'));
    }

    /**
     * Summary of update
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Sellers $seller
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Sellers $seller)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:sellers,email,' . $seller->id,
            'password' => 'nullable|string|min:6|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'level_education' => 'required|string|max:100',
            'picture' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:10240',
            'observations' => 'nullable|string'
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'address.required' => 'La dirección es obligatoria.',
            'phone.required' => 'El teléfono es obligatorio.',
            'level_education.required' => 'El nivel de estudios es obligatorio.',
            'picture.file' => 'La identificación oficial debe ser un archivo válido.',
            'picture.mimes' => 'La identificación oficial debe ser una imagen (JPG, PNG) o un archivo PDF.',
            'picture.max' => 'La identificación oficial no debe superar los 10 MB.',
        ]);

        try {
            $data = $request->all();

            // Manejo de la identificación oficial (imagen o PDF)
            if ($request->hasFile('picture')) {
                // Eliminar el archivo anterior si existe
                if ($seller->picture && file_exists(public_path($seller->picture))) {
                    @unlink(public_path($seller->picture));
                }

                // Subir el nuevo archivo
                $file = $request->file('picture');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/identificaciones'), $filename);
                $data['picture'] = 'uploads/identificaciones/' . $filename;
            } else {
                $data['picture'] = $seller->picture;
            }

            // Validamos si la contraseña ha sido cambiada
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            } else {
                unset($data['password']);
            }

            $seller->update($data);
           return response()->json([
                'ok' => true,
                'message' => 'Vendedor actualizado exitosamente',
                'code' => 200,
                'redirect' => route('sellers.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al actualizar el vendedor: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Summary of destroy
     * @param \App\Models\Sellers $seller
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Sellers $seller)
    {
        try {
            $seller->delete();
            return redirect()->route('sellers.index')->with('success', 'Vendedor eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el vendedor: ' . $e->getMessage());
        }
    }

    /**
     * Summary of deleteSelected
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteSelected(Request $request)
    {
        $ids = $request->input('ids', []);

        try {
            Sellers::whereIn('id', $ids)->delete();
            return response()->json([
                'ok' => true,
                'message' => 'Vendedores eliminados exitosamente',
                'code' => 200,
                'redirect' => route('sellers.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al eliminar los vendedores: ' . $e->getMessage(),
            ]);
        }
    }
}