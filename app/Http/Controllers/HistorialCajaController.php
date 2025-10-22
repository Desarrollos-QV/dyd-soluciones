<?php
namespace App\Http\Controllers;

use App\Models\HistorialCaja;
use Illuminate\Http\Request;

class HistorialCajaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = HistorialCaja::query();

        // Filtros por fechas o tipo
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin]);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $historial = $query->orderByDesc('fecha')->paginate(20);

        return view('admin.historial_caja.index', compact('historial'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.historial_caja.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|in:ingreso,egreso',
            'concepto' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
        ]);

        HistorialCaja::create([
            'user_id' => auth()->id(),
            'fecha' => $request->fecha,
            'hora' => now()->format('H:i'),
            'tipo' => $request->tipo,
            'concepto' => $request->concepto,
            'monto' => $request->monto,
            'metodo_pago' => $request->metodo_pago,
            'descripcion' => $request->descripcion,
            'autorizado_por' => $request->autorizado_por,
            'referencia' => $request->referencia,
        ]);

        return redirect()->route('historial-caja.index')->with('success', 'Movimiento registrado correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HistorialCaja $historial_caja)
    {
        return view('admin.historial_caja.edit', compact('historial_caja'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HistorialCaja $historial_caja)
    {
        $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|in:ingreso,egreso',
            'concepto' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
        ]);

        $historial_caja->update($request->all());

        return redirect()->route('historial-caja.index')->with('success', 'Movimiento actualizado correctamente.');
    }

    public function destroy(HistorialCaja $historial_caja)
    {
        $historial_caja->delete();

        return redirect()->route('historial-caja.index')->with('success', 'Movimiento eliminado correctamente.');
    }
}
