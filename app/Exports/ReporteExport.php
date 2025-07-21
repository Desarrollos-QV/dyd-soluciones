<?php
namespace App\Exports;

use App\Models\Gasto;
use App\Models\Ingreso;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReporteExport implements FromView, WithTitle, ShouldAutoSize
{
    protected $fechaInicio;
    protected $fechaFin;

    public function __construct($fechaInicio, $fechaFin)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    public function view(): View
    {
        $gastos = Gasto::whereBetween('fecha', [$this->fechaInicio, $this->fechaFin])->get();
        // $ingresos = Ingreso::whereBetween('fecha', [$this->fechaInicio, $this->fechaFin])->get();

        return view('admin.gastos.excel', [
            'gastos' => $gastos,
            // 'ingresos' => $ingresos,
            'totalGastos' => $gastos->sum('monto'),
            // 'totalIngresos' => $ingresos->sum('monto'),
            'balance' => $gastos->sum('monto')
        ]);
    }

    public function title(): string
    {
        return 'Reporte de Gastos';
    }
}
