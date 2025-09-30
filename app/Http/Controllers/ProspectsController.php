<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Prospects, Sellers};

class ProspectsController extends Controller
{
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
            'sellers_id' => 'required|exists:sellers,id',
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
            'sellers_id' => 'required|exists:sellers,id',
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
        $prospect->sellers_id = $seller;
        $prospect->save();

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
}