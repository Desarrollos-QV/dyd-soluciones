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
use App\Models\Settings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CollectionsController extends Controller
{
    use NotifiesUsers;
    private $folder = "admin.gestor_cobranza.";

    /**
     * Sincroniza en tiempo real las unidades hacia la tabla de cobranzas
     */
    public function syncUnidadesToCollections(): void
    {
        try {
            $settings = Settings::where('user_id', 1)->first() ?? Settings::first();
            $alertaDias = $settings->dias_tolerancia ?? 5;
            $hoy = Carbon::today();
            $limite = $hoy->copy()->addDays($alertaDias);

            $unidades = Unidades::whereNotNull('fecha_cobro')
                ->whereDate('fecha_cobro', '<=', $limite)
                ->get();

            foreach ($unidades as $unidad) {
                if (!$unidad->cliente_id) {
                    continue;
                }

                $fechaCobro = Carbon::parse($unidad->fecha_cobro);
                $estatus = $fechaCobro->lt($hoy) ? 'overdue' : 'pending';
                $monto = $unidad->pago_mensual ?? 0;

                $collection = Collection::where('unidad_id', $unidad->id)
                    ->where('status', '!=', 'paid')
                    ->first();

                if ($collection) {
                    $collection->update([
                        'cliente_id' => $unidad->cliente_id,
                        'due_date'   => $fechaCobro->toDateString(),
                        'amount'     => $monto,
                        'status'     => $collection->status === 'notified' ? 'notified' : $estatus,
                    ]);
                } else {
                    Collection::create([
                        'unidad_id'  => $unidad->id,
                        'cliente_id' => $unidad->cliente_id,
                        'due_date'   => $fechaCobro->toDateString(),
                        'amount'     => $monto,
                        'status'     => $estatus,
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error en syncUnidadesToCollections: ' . $e->getMessage());
        }
    }

    /**
     * Dashboard principal del Gestor de Cobranza
     */
    public function index(Request $request)
    {
        $this->syncUnidadesToCollections();

        $totalDeuda = Collection::where('status', '!=', 'paid')->sum('amount');
        $pagosprocesados = CollectionPayment::count();
        $ingresosRegistrados = CollectionPayment::selectRaw('SUM(REPLACE(amount, ",", "") + 0) as total_amount')->value('total_amount') ?? 0;
        $clientesMorosos = Collection::where('status', '!=', 'paid')
            ->where('due_date', '<', Carbon::now())
            ->count();

        $query = Collection::with(['cliente', 'unidad', 'cliente.unidades', 'cliente.asignaciones']);

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

        $clientes = Cliente::orderBy('nombre', 'ASC')->get();
        $unidades = Unidades::with('cliente')->orderBy('economico', 'ASC')->get();

        return view($this->folder . 'index', compact(
            'data',
            'totalDeuda',
            'pagosprocesados',
            'ingresosRegistrados',
            'clientesMorosos',
            'clientes',
            'unidades'
        ));
    }

    public function getCollecionAll(Request $request)
    {
        $this->syncUnidadesToCollections();

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
     * Endpoint para sincronización manual de cobranza con las unidades
     */
    public function syncNow()
    {
        $this->syncUnidadesToCollections();

        return response()->json([
            'ok' => true,
            'message' => 'Cobranza sincronizada exitosamente con las unidades.'
        ]);
    }

    /**
     * Crear un registro de cobranza manual
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'unidad_id'  => 'nullable|exists:unidades,id',
            'due_date'   => 'required|date',
            'amount'     => 'required|numeric|min:0',
        ], [
            'cliente_id.required' => 'Debe seleccionar un cliente.',
            'cliente_id.exists'   => 'El cliente seleccionado no es válido.',
            'due_date.required'   => 'La fecha de cobro es obligatoria.',
            'amount.required'     => 'El monto a cobrar es obligatorio.',
            'amount.numeric'      => 'El monto debe ser un valor numérico.',
        ]);

        try {
            $hoy = Carbon::today();
            $dueDate = Carbon::parse($request->due_date);
            $status = $dueDate->lt($hoy) ? 'overdue' : 'pending';

            $collection = Collection::create([
                'cliente_id' => $request->cliente_id,
                'unidad_id'  => $request->filled('unidad_id') ? $request->unidad_id : null,
                'due_date'   => $dueDate->toDateString(),
                'amount'     => $request->amount,
                'status'     => $status,
            ]);

            return response()->json([
                'ok' => true,
                'message' => 'Cobranza registrada exitosamente.',
                'collection' => $collection,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al registrar cobranza: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Registrar pago
     */
    public function pagar(Request $request, $id)
    {
        $collection = Collection::with('cliente')->findOrFail($id);
        $unidad = $collection->unidad_id ? Unidades::find($collection->unidad_id) : null;
        $userId = auth()->id() ?? (Auth::guard('sellers')->id() ?? 1);

        CollectionPayment::create([
            'collection_id' => $collection->id,
            'paid_by' => $userId,
            'amount' => $collection->amount,
        ]);

        $collection->update([
            'status' => 'paid',
            'paid_at' => Carbon::now()
        ]);

        // Restablecemos el mes en la unidad si existe
        if ($unidad && $unidad->fecha_cobro) {
            $unidad->update([
                'fecha_cobro' => Carbon::parse($unidad->fecha_cobro)->addMonth()
            ]);
        }

        // Registramos el ingreso
        HistorialCaja::create([
            'user_id' => $userId,
            'fecha' => Carbon::now(),
            'hora' => Carbon::now()->format('H:i:s'),
            'tipo' => 'ingreso',
            'concepto' => 'Pago Mensualidad',
            'monto' => $collection->amount,
            'descripcion' => "Pago de mensualidad del cliente: " . ($collection->cliente?->nombre ?? 'General'),
            'autorizado_por' => 1,
        ]);

        // Notificamos al Administrador
        $clienteNombre = $collection->cliente?->nombre ?? 'Cliente';
        $this->notifyUser(
            1, // SuperAdmin
            'gestion_cobranza',
            'Pago realizado',
            "La mensualidad del cliente {$clienteNombre} ha sido pagada.",
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
     * Deshacer el pago
     */
    public function undoPayment($id)
    {
        $collection = Collection::findOrFail($id);
        if ($collection->status == 'paid') {
            $collection->update([
                'status' => 'pending',
                'paid_at' => null
            ]);
            
            // Eliminar último pago asociado
            CollectionPayment::where('collection_id', $id)->orderBy('id', 'desc')->first()?->delete();
            
            // Restablecemos el mes en la unidad
            $unidad = Unidades::find($collection->unidad_id);
            if ($unidad && $unidad->fecha_cobro) {
                $unidad->update([
                    'fecha_cobro' => Carbon::parse($unidad->fecha_cobro)->subMonth()
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'El pago se ha deshecho correctamente.'
            ]);
        }
        return response()->json(['status' => false, 'message' => 'Este cobro no está pagado.']);
    }

    /**
     * Actualizar teléfono de notificación
     */
    public function updatePhone(Request $request, $id)
    {
        $collection = Collection::with('cliente')->findOrFail($id);
        $request->validate(['telefono' => 'required|string']);
        
        $collection->cliente->update(['numero_contacto' => $request->telefono]);
        
        return response()->json([
            'status' => true,
            'message' => 'Teléfono actualizado correctamente.'
        ]);
    }

    /**
     * Notificar cliente (SMS/Email/WhatsApp según config)
     */
    public function notify($id)
    {
        $collection = Collection::with(['cliente'])->findOrFail($id);

        $medio       = request('medio');
        $phoneNumber = $collection->cliente->numero_contacto;
        $clientName  = $collection->cliente->nombre;
        $amount      = $collection->amount;
        $businesName = "DYD Soluciones";
        $dueDate     = $collection->due_date
            ? Carbon::parse($collection->due_date)->format('d/m/Y')
            : 'próximo vencimiento';

        try {
            switch ($medio) {

                case 'whatsapp':
                    /**
                     * Template en Twilio: reminder_pay
                     * Hola {1}, te recordamos que tienes un pago pendiente con {2}.
                     * Por la cantidad de : {3} MXN
                     * Si ya realizaste tu pago, puedes ignorar este mensaje.
                     */
                    $contentSid = config('services.twilio.template_reminder_pay',
                                        env('TWILIO_TEMPLATE_REMINDER_PAY'));

                    $this->notifyUserWhatsappTemplate($phoneNumber, $contentSid, [
                        '1' => $clientName,
                        '2' => $businesName,
                        '3' => '$' . number_format((float) str_replace(',', '', $amount), 2).' MXN',
                    ]);
                    break;

                case 'sms':
                    $message = request('mensaje')
                        ?? "Hola {$clientName}, le recordamos el pago de \${$amount} con vencimiento el {$dueDate}. Comuníquese con nosotros. ¡Gracias!";
                    $this->notifyUserSMS($phoneNumber, $message);
                    break;

                case 'email':
                    if (!$collection->cliente->email) {
                        return response()->json([
                            'status'  => false,
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
                    return response()->json([
                        'status'  => false,
                        'message' => 'Medio de notificación no reconocido.'
                    ], 422);
            }

            $collection->update([
                'status'      => 'notified',
                'notified_at' => Carbon::now(),
            ]);

            return response()->json([
                'status'  => true,
                'message' => "Cliente {$clientName} notificado correctamente vía {$medio}."
            ]);

        } catch (\Exception $e) {
            Log::error("Error en notify() [{$medio}]: " . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Error al enviar la notificación: ' . $e->getMessage()
            ], 500);
        }
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