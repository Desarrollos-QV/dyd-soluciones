<?php

namespace App\Http\Controllers;

use App\Traits\NotifiesUsers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\{
    Cliente,
    Tecnico,
    User,
    Asignaciones,
    Devices
};

class AssignementsController extends Controller
{
    use NotifiesUsers;

    private $folder = "admin.asignaciones.";

    /**
     * Index 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $assignements = Asignaciones::with('cliente','device','tecnico')
                        ->whereStatus(0)
                        ->get();
        $tecnicos    = User::where('role', 'tecnico')->get();
        // return response()->json([
        //     'assignements' => $assignements
        // ]);

        return view($this->folder . 'index', compact('assignements', 'tecnicos'));
    }

    public function AssignsPerformed()
    {
        $assignements = Asignaciones::with('cliente','device','tecnico')
                        ->whereStatus(5)
                        ->get();
        $tecnicos    = User::where('role', 'tecnico')->get();
        
        return view($this->folder . 'index', compact('assignements', 'tecnicos'));
    }

    public function AssignsInProgress()
    {
        $assignements = Asignaciones::with('cliente','device','tecnico')
                        ->whereIn('status', [1, 2, 3, 4])
                        ->get();
        $tecnicos    = User::where('role', 'tecnico')->get();
        
        return view($this->folder . 'index', compact('assignements', 'tecnicos'));
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
        $devices    = Devices::all();

        return view($this->folder . 'create', compact('assignement', 'clientes', 'tecnicos', 'devices'));
    }

    /**
     * Store a newly created resource in storage.
     * 
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id'        => 'nullable|exists:clientes,id',
            'tecnico_id'        => 'nullable',
            'tipo_servicio'     => 'nullable|string',
            'tel_contact'       => 'nullable|string',
            'encargado_recibir' => 'nullable|string',
            'location'          => 'nullable|string',
            'lat'               => 'nullable|string',
            'lng'               => 'nullable|string',
            'viaticos'          => 'nullable|string',
            'tipo_vehiculo'     => 'nullable|string',
            'marca'             => 'nullable|string',
            'modelo'            => 'nullable|string',
            'placa'             => 'nullable|string',
            'observaciones'     => 'nullable|string',
        ]);

        try {
            Asignaciones::create($request->all());

            return response()->json([
                'ok' => true,
                'message' => 'Asignación creada correctamente.',
                'code' => 200,
                'redirect' => route('assignements.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear el Dispositivo: ' . $e->getMessage(),
            ]);
        }
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
        $devices     = Devices::all();

        return view($this->folder . 'edit', compact('assignement', 'clientes', 'tecnicos', 'devices'));
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
            'cliente_id'        => 'nullable|exists:clientes,id',
            'tecnico_id'        => 'nullable',
            'tipo_servicio'     => 'nullable|string',
            'tel_contact'       => 'nullable|string',
            'encargado_recibir' => 'nullable|string',
            'location'          => 'nullable|string',
            'lat'               => 'nullable|string',
            'lng'               => 'nullable|string',
            'viaticos'          => 'nullable|string',
            'tipo_vehiculo'     => 'nullable|string',
            'marca'             => 'nullable|string',
            'modelo'            => 'nullable|string',
            'placa'             => 'nullable|string',
            'observaciones'     => 'nullable|string',
        ]);

        try {
            $assignement = Asignaciones::findOrFail($id);
            $assignement->update($request->all());

            return response()->json([
                'ok' => true,
                'message' => 'Asignación actualizada correctamente.',
                'code' => 200,
                'redirect' => route('assignements.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear el Dispositivo: ' . $e->getMessage(),
            ]);
        }
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

    /**
     * Assign Tecnico to Asignacion
     * @param  int  $id , $tecnico
     */
    public function AssignTecn($id, $tecnico)
    {
        $assignement = Asignaciones::findOrFail($id);
        $assignement->tecnico_id = $tecnico;
        $assignement->status = 1; // Cambiar estado a "En Progreso"
        $assignement->save();

        // Generamos la notificación para el técnico asignado
        $this->notifyUser(
            $tecnico,
            'service_assigned',
            'Nueva Asignación',
            'Se te ha asignado una nueva tarea. Por favor, revisa los detalles.',
            ['assignement_id' => $assignement->id],
            route('assignements.show', $assignement->id),
            now()->addDays(7)
        );

        return redirect()->route('assignements.index')->with('success', 'Técnico asignado con éxito.');
    }

    /**
     * Bulk Delete Selected Assignements
     */
    public function deleteSelected(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json([
                'ok' => false,
                'message' => 'No se seleccionaron elementos para eliminar.',
            ]);
        }

        try {
            Asignaciones::whereIn('id', $ids)->delete();

            return response()->json([
                'ok' => true,
                'message' => 'Elementos eliminados correctamente.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al eliminar los elementos: ' . $e->getMessage(),
            ]);
        }
    }
}