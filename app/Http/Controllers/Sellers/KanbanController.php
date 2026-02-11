<?php

namespace App\Http\Controllers\Sellers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prospects;
use App\Models\ProspectNote;
use App\Models\ProspectEvent;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use App\Traits\NotifiesUsers;

class KanbanController extends Controller
{
    use NotifiesUsers;

    public function index()
    {
        $sellerId = Auth::guard('sellers')->id();
        
        // Fetch prospects with status 1, 2, 3 ordered by updated_at desc
        // 1 = En Proceso, 2 = Concretado, 3 = Competencia/Instaladores
        $prospects = Prospects::where('sellers_id', $sellerId)
            ->whereIn('status', [1, 2, 3])
            ->with(['notes' => function($query) {
                $query->orderBy('created_at', 'desc');
            }, 'events' => function($query) {
                $query->orderBy('start', 'asc');
            }])
            ->orderBy('updated_at', 'desc')
            ->get();

        $enProceso = $prospects->where('status', 1);
        $concretado = $prospects->where('status', 2);
        $competencia = $prospects->where('status', 3);

        return view('sellers.kanban.index', compact('enProceso', 'concretado', 'competencia'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'prospect_id' => 'required|exists:prospects,id',
            'status' => 'required|in:1,2,3'
        ]);

        $sellerId = Auth::guard('sellers')->id();
        $prospect = Prospects::where('id', $request->prospect_id)
            ->where('sellers_id', $sellerId)
            ->firstOrFail();

        $oldStatus = $prospect->status;
        $prospect->status = $request->status;
        $prospect->save();

        // If status changed to 2 (Concretado), consider automation to create Client?
        // User request says "al final poder convertir este prospecto a un cliente formal"
        // so we might need a specific button action for that, not just drag and drop.
        
        return response()->json(['message' => 'Estatus actualizado correctamente.']);
    }

    public function storeNote(Request $request)
    {
        $request->validate([
            'prospect_id' => 'required|exists:prospects,id',
            'note' => 'required|string'
        ]);

        // Verify ownership
        $sellerId = Auth::guard('sellers')->id();
        $prospect = Prospects::where('id', $request->prospect_id)
            ->where('sellers_id', $sellerId)
            ->firstOrFail();

        $note = ProspectNote::create([
            'prospect_id' => $request->prospect_id,
            'note' => $request->note
        ]);

        return response()->json([
            'message' => 'Nota agregada correctamente.',
            'note' => $note
        ]);
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'prospect_id' => 'required|exists:prospects,id',
            'title' => 'required|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'description' => 'nullable|string'
        ]);

        // Verify ownership
        $sellerId = Auth::guard('sellers')->id();
        $prospect = Prospects::where('id', $request->prospect_id)
            ->where('sellers_id', $sellerId)
            ->firstOrFail();

        $event = ProspectEvent::create([
            'prospect_id' => $request->prospect_id,
            'title' => $request->title,
            'start' => $request->start,
            'end' => $request->end,
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'Evento agregado correctamente.',
            'event' => $event
        ]);
    }

    public function convertToClient(Request $request)
    {
        $request->validate([
            'prospect_id' => 'required|exists:prospects,id'
        ]);

        $sellerId = Auth::guard('sellers')->id();
        $prospect = Prospects::where('id', $request->prospect_id)
            ->where('sellers_id', $sellerId)
            ->firstOrFail();

        if ($prospect->status != 2) {
            return response()->json(['message' => 'El prospecto debe estar en estatus "Concretado" para convertirlo a cliente.'], 400);
        }

        // Check if client already exists (optional logic, but good practice)
        // For now, simple creation as per admin controller logic
        $client = new Cliente;
        $client->nombre             = $prospect->name_prospect;
        $client->direccion          = $prospect->location;
        $client->numero_contacto    = $prospect->contacts;
        $client->empresa            = $prospect->name_company;
        $client->direccion_empresa  = $prospect->company;
        $client->save();

        // Update status to 5 (or any status not in [1, 2, 3]) to hide from Kanban
        $prospect->status = 5; 
        $prospect->save();

        return response()->json([
            'message' => 'Cliente creado exitosamente.',
            'client_id' => $client->id
        ]);
    }
    public function getProspectDetails($id)
    {
        $sellerId = Auth::guard('sellers')->id();
        $prospect = Prospects::where('id', $id)
            ->where('sellers_id', $sellerId)
            ->with(['notes' => function($query) {
                $query->orderBy('created_at', 'desc');
            }, 'events' => function($query) {
                $query->orderBy('start', 'asc');
            }])
            ->firstOrFail();

        return response()->json($prospect);
    }
}
