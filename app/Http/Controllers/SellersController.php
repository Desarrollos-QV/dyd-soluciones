<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sellers;

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
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'level_education' => 'required|string|max:100',
        ]);

        try {
            Sellers::create($request->all());
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
            ]);
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
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'level_education' => 'required|string|max:100',
        ]);

        try {
            $seller->update($request->all());
           return response()->json([
                'ok' => true,
                'message' => 'Vendedor actualizado exitosamente',
                'code' => 200,
                'redirect' => route('sellers.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear el vendedor: ' . $e->getMessage(),
            ]);
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
}