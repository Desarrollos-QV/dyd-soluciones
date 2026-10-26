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
        $assignements = Asignaciones::with('cliente', 'device', 'tecnico', 'getFirma')
            ->whereStatus(0)
            ->get();
        $tecnicos = User::where('role', 'tecnico')->get();
        // return response()->json([
        //     'assignements' => $assignements,
        //     'tecnicos' => $tecnicos
        // ]);

        return view($this->folder . 'index', compact('assignements', 'tecnicos'));
    }

    public function show($id)
    {
        $assignement = Asignaciones::with(['cliente', 'device', 'tecnico', 'getFirma', 'unidad', 'unidades.simcontrol', 'unidades.inventario'])->findOrFail($id);
        return view($this->folder . 'show', compact('assignement'));
    }

    public function AssignsPerformed()
    {
        $assignements = Asignaciones::with('cliente', 'device', 'tecnico')
            ->whereStatus(5)
            ->get();
        $tecnicos = User::where('role', 'tecnico')->get();

        return view($this->folder . 'index', compact('assignements', 'tecnicos'));
    }

    public function AssignsInProgress()
    {
        $assignements = Asignaciones::with('cliente', 'device', 'tecnico')
            ->whereIn('status', [1, 2, 3, 4])
            ->get();
        $tecnicos = User::where('role', 'tecnico')->get();

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
        $clientes = Cliente::all();
        $tecnicos = User::where('role', 'tecnico')->get();
        $devices = Devices::all();
        $simcontrols = \App\Models\SimControl::all();
        $sensores = \App\Models\Devices::where('type', 'sensor')->get();

        return view($this->folder . 'create', compact('assignement', 'clientes', 'tecnicos', 'devices', 'simcontrols', 'sensores'));
    }

    /**
     * Store a newly created resource in storage.
     * 
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'tecnico_id' => 'nullable',
            'tipo_servicio' => 'nullable|string',
            'tel_contact' => 'nullable|string',
            'encargado_recibir' => 'nullable|string',
            'location' => 'nullable|string',
            'lat' => 'nullable|string',
            'lng' => 'nullable|string',
            'viaticos' => 'nullable|string',
            'same_viaticos' => 'nullable|boolean',
            'unidad_id' => 'nullable|exists:unidades,id',
            'unidad_ids' => 'nullable|array',
            'observaciones' => 'nullable|string',
        ]);

        try {
            $data = $request->all();

            // Enfoque A: Manejo de una o múltiples unidades asignadas
            $unidadIds = [];
            if ($request->has('unidad_ids') && is_array($request->unidad_ids)) {
                $unidadIds = array_filter($request->unidad_ids);
            } elseif ($request->filled('unidad_id')) {
                $unidadIds = [$request->unidad_id];
            }

            if (!empty($unidadIds)) {
                $data['unidad_id'] = $unidadIds[0];
            }

            $assignment = Asignaciones::create($data);

            if (!empty($unidadIds)) {
                $assignment->unidades()->sync($unidadIds);
            }

            if ($request->filled('tecnico_id')) {
                $tecnico = User::find($request->tecnico_id);
                if ($tecnico) {
                    $this->AssignTecn($assignment->id, $tecnico->id);
                }
            }


            return response()->json([
                'ok' => true,
                'message' => 'Asignación creada correctamente.',
                'code' => 200,
                'redirect' => route('assignements.inprogress')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear la asignación: ' . $e->getMessage(),
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
        $assignement = Asignaciones::with(['unidades', 'cliente'])->findOrFail($id);
        $clientes = Cliente::all();
        $tecnicos = User::where('role', 'tecnico')->get();
        $devices = Devices::all();
        $simcontrols = \App\Models\SimControl::all();
        $sensores = \App\Models\Devices::where('type', 'sensor')->get();

        return view($this->folder . 'edit', compact('assignement', 'clientes', 'tecnicos', 'devices', 'simcontrols', 'sensores'));
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
            'cliente_id' => 'nullable|exists:clientes,id',
            'tecnico_id' => 'nullable',
            'tipo_servicio' => 'nullable|string',
            'tel_contact' => 'nullable|string',
            'encargado_recibir' => 'nullable|string',
            'location' => 'nullable|string',
            'lat' => 'nullable|string',
            'lng' => 'nullable|string',
            'viaticos' => 'nullable|string',
            'same_viaticos' => 'nullable',
            'unidad_id' => 'nullable|exists:unidades,id',
            'unidad_ids' => 'nullable|array',
            'observaciones' => 'nullable|string',
        ]);

        try {
            $assignement = Asignaciones::findOrFail($id);
            $originalTecnicoId = $assignement->tecnico_id;

            $data = $request->all();

            $unidadIds = [];
            if ($request->has('unidad_ids') && is_array($request->unidad_ids)) {
                $unidadIds = array_filter($request->unidad_ids);
            } elseif ($request->filled('unidad_id')) {
                $unidadIds = [$request->unidad_id];
            }

            if (!empty($unidadIds)) {
                $data['unidad_id'] = $unidadIds[0];
            }

            $assignement->update($data);

            if (!empty($unidadIds)) {
                $assignement->unidades()->sync($unidadIds);
            }

            if ($request->filled('tecnico_id')) {
                $newTecnicoId = $request->tecnico_id;

                // If technician changed and there was an original technician
                if ($originalTecnicoId && $originalTecnicoId != $newTecnicoId) {
                    $oldTecnico = User::find($originalTecnicoId);
                    if ($oldTecnico) {
                        $this->notifyUser(
                            $oldTecnico->id,
                            'service_reassigned',
                            'Servicio Reasignado',
                            'El servicio #' . $assignement->id . ' te ha sido reasignado a otro técnico.',
                            ['assignement_id' => $assignement->id],
                            route('servicios_agendados.edit', $assignement->id),
                            now()->addDays(7)
                        );
                    }
                }

                // If a new technician is assigned or technician changed
                if ($newTecnicoId && $originalTecnicoId != $newTecnicoId) {
                    $this->AssignTecn($assignement->id, $newTecnicoId);
                }
            }

            if ($request->hasFile('file')) {
                // Delete old file if exists
                if ($assignement->file && file_exists(public_path('uploads/ordenes_servicio/' . $assignement->file))) {
                    unlink(public_path('uploads/ordenes_servicio/' . $assignement->file));
                }

                $file = $request->file('file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/ordenes_servicio'), $filename);
                $assignement->file = $filename;
            }

            // Handle checkbox logic
            $assignement->same_viaticos = $request->has('same_viaticos_check') ? 1 : 0;
            $assignement->save();


            return response()->json([
                'ok' => true,
                'message' => 'Asignación actualizada correctamente.',
                'code' => 200,
                'redirect' => route('assignements.index'),
                'data' => $request->all()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al actualizar la Asignación: ' . $e->getMessage(),
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
            route('servicios_agendados.edit', $assignement->id),
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