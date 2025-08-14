<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\{
    Cliente,
    Tecnico,
    User,
    Asignaciones,
    Unidades
};

class AssignementsController extends Controller
{
    private $folder = "admin.asignaciones.";

    /**
     * Index 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $assignements = Asignaciones::with('cliente','unidad','tecnico')->get();

        // return response()->json([
        //     'assignements' => $assignements
        // ]);

        return view($this->folder . 'index', compact('assignements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $assignement = new Asignaciones;
        $clientes    = Cliente::all();
        $tecnicos    = User::where('role', 'tecnico')->get();
        $unidades    = Unidades::all();

        return view($this->folder . 'create', compact('assignement', 'clientes', 'tecnicos', 'unidades'));
    }

    /**
     * Store a newly created resource in storage.
     * 
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id'                  => 'required|exists:clientes,id',
            'unidad_id'                   => 'required|exists:unidades,id',
            'costo_plataforma'            => 'required|numeric|min:0',
            'costo_sim'                   => 'required|numeric|min:0',
            'pago_mensual'                => 'required|numeric|min:0',
            'fecha_inicio'                => 'required|date',
            'fecha_ultimo_mantenimiento'  => 'required|date',
            'descuento'                   => 'nullable|numeric|min:0',
            'cobro_adicional'             => 'nullable|numeric|min:0',
            'ganancia'                    => 'nullable|numeric|min:0',
            'observaciones'               => 'nullable|string',
            'observaciones_mantenimiento' => 'nullable|string',
        ]);

        Asignaciones::create($request->all());

        return redirect()->route('assignements.index')->with('success', 'Asignación creada correctamente.');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit($id)
    {
        $assignement = Asignaciones::findOrFail($id);
        $clientes    = Cliente::all();
        $tecnicos    = User::where('role', 'tecnico')->get();
        $unidades    = Unidades::all();

        return view($this->folder . 'edit', compact('assignement', 'clientes', 'tecnicos', 'unidades'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'cliente_id'                  => 'required|exists:clientes,id',
            'unidad_id'                   => 'required|exists:unidades,id',
            'costo_plataforma'            => 'required|numeric|min:0',
            'costo_sim'                   => 'required|numeric|min:0',
            'pago_mensual'                => 'required|numeric|min:0',
            'fecha_inicio'                => 'required|date',
            'fecha_ultimo_mantenimiento'  => 'required|date',
            'descuento'                   => 'nullable|numeric|min:0',
            'cobro_adicional'             => 'nullable|numeric|min:0',
            'ganancia'                    => 'nullable|numeric|min:0',
            'observaciones'               => 'nullable|string',
            'observaciones_mantenimiento' => 'nullable|string',
        ]);

        $assignement = Asignaciones::findOrFail($id);
        $assignement->update($request->all());

        return redirect()->route('assignements.index')->with('success', 'Asignación actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id 
     */
    public function destroy(Asignaciones $assignement)
    {
        $assignement->delete();
        return redirect()->route('assignements.index')->with('success', 'Asignación Eliminada con éxito.');
    }
}