<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Traits\NotifiesUsers;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Collection;
use App\Models\CollectionPayment;
use App\Models\Cliente;
use App\Models\Unidades;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\HistorialCaja;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class CollectionsController extends Controller
{
    use NotifiesUsers;
    private $folder = "admin.gestor_cobranza.";

    /**
     * Dashboard principal del Gestor de Cobranza
     */

    public function index(Request $request)
    {
        $totalDeuda = Collection::where('status', '!=', 'paid')->sum('amount');
        $pagosprocesados = CollectionPayment::count();
        $ingresosRegistrados = CollectionPayment::selectRaw('SUM(REPLACE(amount, ",", "") + 0) as total_amount')->value('total_amount');
        $clientesMorosos = Collection::where('status', '!=', 'paid')
            ->where('due_date', '<', Carbon::now())
            ->count();

        $query = Collection::with(['cliente', 'cliente.unidades', 'cliente.asignaciones']);

        if ($request->search) {
            $query->whereHas('cliente', function ($q) use ($request) {
                $q->where('nombre', 'LIKE', "%{$request->search}%");
            });
        }

        if ($request->status && $request->status !== 'todos') {
            $query->where('status', $request->status);
        }

        if ($request->dateFrom && $request->dateTo) {
            $query->whereBetween('due_date', [$request->dateFrom, $request->dateTo]);
        }

        $data = $query->orderBy('due_date', 'ASC')->get();

        return view($this->folder . 'index', compact(
            'data',
            'totalDeuda',
            'pagosprocesados',
            'ingresosRegistrados',
            'clientesMorosos'
        ));
    }
    public function getCollecionAll(Request $request)
    {
        $query = Collection::with(['cliente', 'unidad', 'cliente.unidades', 'cliente.asignaciones', 'pagos']);

        if ($request->search) {
            $query->whereHas('cliente', function ($q) use ($request) {
                $q->where('nombre', 'LIKE', "%{$request->search}%");
            });
        }

        if ($request->status && $request->status !== 'todos') {
            $query->where('status', $request->status);
        }

        if ($request->dateFrom && $request->dateTo) {
            $query->whereBetween('due_date', [$request->dateFrom, $request->dateTo]);
        }

        $data = $query->orderBy('due_date', 'ASC')->get();

        // Renderizar tabla en servidor
        $html = view($this->folder . 'table', compact('data'))->render();

        return response()->json(['html' => $html]);
    }

    /**
     * Registrar pago
     */
    public function pagar(Request $request, $id)
    {
        $collection = Collection::with('cliente')->findOrFail($id);
        $unidad = Unidades::find($collection->unidad_id);

        CollectionPayment::create([
            'collection_id' => $collection->id,
            'amount' => $collection->amount,
            'paid_by' => $collection->cliente_id,
        ]);

        $collection->update([
            'status' => 'paid',
            'paid_at' => Carbon::now()
        ]);

        $unidad->update([
            'fecha_cobro' => Carbon::now()->addDays(30)
        ]);

        // Registramos el ingreso
        HistorialCaja::create([
            'user_id' => auth()->id(),
            'fecha' => Carbon::now(),
            'hora' => Carbon::now()->format('H:i:s'),
            'tipo' => 'ingreso',
            'concepto' => 'Pago Mensualidad',
            'monto' => $collection->amount,
            'descripcion' => "Pago de mensualidad del cliente: " . $collection->cliente->nombre,
            'autorizado_por' => 1,
        ]);

        // Notificamos al Administrador
        $this->notifyUser(
            1, // SuperAdmin
            'gestion_cobranza',
            'Pago realizado',
            "La mensualidad del cliente {$collection->cliente->nombre} ha sido pagada.",
            json_encode(["unidad" => $collection->id]),
            route('collections.index'),
            now()->addDays(7)
        );
        return response()->json([
            'status' => true,
            'message' => "Pago realizado con éxito."
        ]);
    }

    /**
     * Notificar cliente (SMS/Email/WhatsApp según config)
     */
    public function notify($id)
    {
        $collection = Collection::with(['cliente'])->findOrFail($id);

        // Aquí aplicas tu método de notificación (Twilio, WhatsApp, Email, SMS, etc.)
        // Ejemplo básico:
        $medio = request('medio');
        $phoneNumber = $collection->cliente->numero_contacto;
        $clientName = $collection->cliente->nombre;
        $amount = $collection->amount;
        $message = "Hola {$clientName}, le recordamos el pago de su mensualidad por un monto de {$amount}. Por favor, comunicarse para coordinar. ¡Gracias!";

        // Notificamos al Usuario
        switch ($medio) {
            case 'sms':
                $this->notifyUserSMS(
                    $phoneNumber,
                    request('mensaje')
                );
                break;
            case 'whatsapp':
                $this->notifyUserWhatsapp(
                    $phoneNumber,
                    request('mensaje')
                );
                break;
            case 'email':
                // Verificar que el cliente tenga email
                if (!$collection->cliente->email) {
                    return response()->json([
                        'status' => false,
                        'message' => 'El cliente no tiene un correo electrónico registrado.'
                    ], 400);
                }
                
                $this->notifyUserEmail(
                    $collection->cliente->email,
                    $clientName,
                    $amount,
                    $collection->due_date,
                    request('mensaje')
                );
                break;
            default:
                $this->notifyUserSMS(
                    $phoneNumber,
                    request('mensaje')
                );
                break;
        }


        $collection->update([
            'status' => 'notified',
            'notified_at' => Carbon::now()
        ]);

        return back()->with('success', 'Cliente notificado correctamente.');
    }

    /**
     * API para DropZone u otras integraciones
     */
    public function fetchData(Request $request)
    {
        $cobranzas = Collection::with(['cliente', 'unidad'])
            ->orderBy('due_date', 'ASC')
            ->get();

        return response()->json($cobranzas);
    }
}