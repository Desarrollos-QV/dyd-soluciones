<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\{
    ReporteExport,
    UnidadesExport
};
use Maatwebsite\Excel\Facades\Excel;

use App\Models\{
    Gasto,
    Cliente
};
class ReporteIngresoEgresoController extends Controller
{
    public function index(Request $request)
    {
        // Filtrado por fecha si se selecciona
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $gastos = Gasto::query(); 

        if ($fechaInicio && $fechaFin) {
            $gastos->whereBetween('fecha', [$fechaInicio, $fechaFin]); 
        }

        $gastos = $gastos->get(); 

        // Totales
        $totalGastos = $gastos->sum('monto'); 
        $balance = $totalGastos;

        return view('admin.gastos.reportes', compact('gastos', 'totalGastos', 'balance', 'fechaInicio', 'fechaFin'));
    }

    public function exportarExcel(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        return Excel::download(new ReporteExport($fechaInicio, $fechaFin), 'reporte-ingresos-gastos.xlsx');
    }

    public function ReportClients()
    {
        $clientes = Cliente::all();
        return view('admin.clientes.reports' , compact('clientes'));
    }

    public function exportarExcelClients(Request $request)
    {
        $clienteId = $request->input('cliente_id'); 

        // $cliente  = Cliente::with('unidades')->find($clienteId);
        // return response()->json(['cliente' => $cliente]);
        return Excel::download(new UnidadesExport($clienteId), 'reporte-cliente-'.$clienteId.'.xlsx');
    }
}
