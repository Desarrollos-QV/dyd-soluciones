<?php
namespace App\Http\Controllers;

use App\Models\Gasto;
use Illuminate\Http\Request;
use App\Exports\ReporteExport;
use Maatwebsite\Excel\Facades\Excel;

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
}
