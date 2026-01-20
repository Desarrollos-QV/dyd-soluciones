<?php
namespace App\Http\Controllers;

use App\Models\HistorialCaja;
use Illuminate\Http\Request;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HistorialCajaExport;

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
        $tecnicos = User::whereRole('tecnico')->whereIsActive('1')->get();

        return view('admin.historial_caja.index', compact('historial','tecnicos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|in:ingreso,egreso',
            'descripcion' => 'required|string|max:255',
            'monto' => 'required|string|min:0',
        ]);

        HistorialCaja::create([
            'user_id' => auth()->id(),
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'tipo' => $request->tipo,
            'concepto' => $request->descripcion,
            'monto' => $request->monto,
            'metodo_pago' => $request->metodo_pago,
            'descripcion' => $request->descripcion,
            'autorizado_por' => $request->autorizado_por,
            'referencia' => $request->referencia,
        ]);

        return redirect()->route('historial-caja.index')->with('success', 'Movimiento registrado correctamente.');
    }

    public function getElement($id)
    {
        $element = HistorialCaja::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $element
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HistorialCaja $historial_caja)
    {
        $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|in:ingreso,egreso',
            'descripcion' => 'required|string|max:255',
            'monto' => 'required|string|min:0',
        ]);

        $historial_caja->update([
            'user_id' => auth()->id(),
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'tipo' => $request->tipo,
            'concepto' => $request->descripcion,
            'monto' => $request->monto,
            'metodo_pago' => $request->metodo_pago,
            'descripcion' => $request->descripcion,
            'autorizado_por' => $request->autorizado_por,
            'referencia' => $request->referencia,
        ]);

        if($request->ajax()){
            return response()->json([
                'ok' => true,
                'message' => 'Movimiento actualizado correctamente.'
            ]);
        }

        // return redirect()->route('historial-caja.index')->with('success', 'Movimiento actualizado correctamente.');
    }

    public function destroy(HistorialCaja $historial_caja)
    {
        $historial_caja->delete();

        return redirect()->route('historial-caja.index')->with('success', 'Movimiento eliminado correctamente.');
    }

    /**
     * Export historial to Excel
     */
    public function exportarExcel(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $tipo = $request->get('tipo');

        $nombreArchivo = 'Historial_Caja_' . now()->format('d-m-Y_H-i-s') . '.xlsx';

        return Excel::download(
            new HistorialCajaExport($fechaInicio, $fechaFin, $tipo),
            $nombreArchivo
        );
    }
}
