<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Prospects, Sellers, Cliente};
use App\Traits\NotifiesUsers;

class ProspectsController extends Controller
{
    use NotifiesUsers;
    public $folder = 'admin.prospects';

    /**
     * Summary of index
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $prospects = Prospects::with('seller')->get();
        $sellers = Sellers::all();

        // return response()->json([
        //     'prospects' => $prospects,
        //     'sellers' => $sellers
        // ]);

        return view($this->folder . '.index', compact('prospects','sellers'));
    }

    /**
     * Summary of create
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Prospects $prospect)
    {
        $sellers = Sellers::all();
        return view($this->folder . '.create', compact('prospect','sellers'));
    }

    /**
     * Summary of store
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_company' => 'required|string|max:255',
            'name_prospect' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'potencial' => 'required|string|max:100',
            'sellers_id' => 'exists:sellers,id',
            'location' => 'required|string',
            'contacts' => 'required|string',
            'observations' => 'nullable|string'
        ]);

        try {
            Prospects::create($request->all());
            return response()->json([
                'ok' => true,
                'message' => 'Prospecto creado exitosamente',
                'code' => 200,
                'redirect' => route('prospects.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear el Prospecto: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Summary of edit
     * @param \App\Models\Prospects $prospect
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Prospects $prospect)
    {
        $sellers = Sellers::all();
        return view($this->folder . '.edit', compact('prospect', 'sellers'));
    }

    /**
     * Summary of update
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Prospects $prospect
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Prospects $prospect)
    {
        $request->validate([
            'name_company' => 'required|string|max:255',
            'name_prospect' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'potencial' => 'required|string|max:100',
            'sellers_id' => 'exists:sellers,id',
            'location' => 'required|string',
            'contacts' => 'required|string',
            'observations' => 'nullable|string'
        ]);

        try {
            $prospect->update($request->all()); 
            return response()->json([
                'ok' => true,
                'message' => 'Prospecto actualizado exitosamente',
                'code' => 200,
                'redirect' => route('prospects.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear el Prospecto: ' . $e->getMessage(),
            ]);
        }
    }
    
    /**
     * Summary of ChangeStatus
     * @param int $id
     * @param int $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ChangeStatus($status)
    {
        $prospect = Prospects::findOrFail($_GET['id']);
        $prospect->status = $status;
        $prospect->save();

        // Verificamos si el status es = 2(Concretado) para pasar este elemento a la tabla de clientes
        if($status == 2){
            $client = new Cliente;
            $client->nombre             = $prospect->name_prospect;
            $client->direccion          = $prospect->location;
            $client->numero_contacto    = $prospect->contacts;
            $client->empresa            = $prospect->name_company;
            $client->direccion_empresa  = $prospect->company;
            $client->save();
        }

        // Validamos si el prospecto ya esta asignado a un seller si es asi, notificamos al seller que el status de su prospecto ha cambiado
        if($prospect->sellers_id != null){
            $this->notifySeller($prospect->sellers_id, 'info', 'Prospecto actualizado', 'El status de tu prospecto ha cambiado', [
                'prospect' => $prospect
            ], route('sellers.prospects.index'));
        }

        return redirect()->route('prospects.index')->with('success', 'Estado del prospecto actualizado exitosamente');
    }

    /**
     * Summary of AssignSeller
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function AssignSeller($id , $seller)
    {
        $prospect = Prospects::findOrFail($id);

        // Validamos si el prospecto ya tiene un seller asignado
        if($prospect->sellers_id != 0   ){
            // Obtenemos el selle actual para notificarle que ya no sera su prospecto
            $this->notifySeller($prospect->sellers_id, 'info', 'Prospecto reasignado', 'El prospecto ha sido reasignado a otro vendedor', [
                'prospect' => $prospect
            ], route('sellers.prospects.index'));
        }

        $prospect->sellers_id = $seller;
        $prospect->save();

        if($seller != 0){
            // Notificamos al nuevo seller que se le ha asignado un nuevo prospecto
            $this->notifySeller($prospect->sellers_id, 'info', 'Nuevo prospecto asignado', 'Se te ha asignado un nuevo prospecto', [
                'prospect' => $prospect
            ], route('sellers.prospects.index'));
        }

        return redirect()->route('prospects.index')->with('success', 'Vendedor asignado al prospecto exitosamente');
    }


    /**
     * Summary of destroy
     * @param \App\Models\Prospects $prospect
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Prospects $prospect)
    {
        try {
            $prospect->delete();
            return redirect()->route('prospects.index')->with('success', 'Prospecto eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el prospecto: ' . $e->getMessage());
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
            Prospects::whereIn('id', $ids)->delete();
            return response()->json([
                'ok' => true,
                'message' => 'Prospectos eliminados exitosamente',
                'code' => 200,
                'redirect' => route('prospects.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al eliminar los prospectos: ' . $e->getMessage(),
            ]);
        }
    }
}