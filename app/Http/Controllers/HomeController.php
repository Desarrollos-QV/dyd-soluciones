<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Tecnico;
use App\Models\Asignaciones;
use App\Models\ServiciosAgendado;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // Data for Chart (Services per day in current month)
        $chartData = [];

        // Determine date range for Chart and Table
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        
        if($dateFrom && $dateTo) {
            $startChart = Carbon::parse($dateFrom)->startOfDay();
            $endChart = Carbon::parse($dateTo)->endOfDay();
            $monthLabel = $startChart->translatedFormat('d M') . ' - ' . $endChart->translatedFormat('d M Y');
        } else {
            // Default to current month
            $now = Carbon::now();
            $startChart = $now->copy()->startOfMonth();
            $endChart = $now->copy()->endOfMonth();
            $monthLabel = $now->translatedFormat('F Y');
        }

        if(Auth::user()->isAdmin()) {
            // ADMIN DATA
            
            // 🔢 Clientes
            $clientesEsteMes = Cliente::whereBetween('created_at', [$startOfMonth, $now])->count();
            $clientesMesPasado = Cliente::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
            $clientesCambioPorcentaje = $this->getPercentageChange($clientesMesPasado, $clientesEsteMes);

            // 🔧 Técnicos
            $tecnicosEsteMes = User::whereRole('tecnico')->whereBetween('created_at', [$startOfMonth, $now])->count();
            $tecnicosMesPasado = User::whereRole('tecnico')->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
            $tecnicosCambioPorcentaje = $this->getPercentageChange($tecnicosMesPasado, $tecnicosEsteMes);

            // 🛠 Servicios (Nuevos este mes)
            $serviciosEsteMes = Asignaciones::whereBetween('created_at', [$startOfMonth, $now])->count();
            $serviciosMesPasado = Asignaciones::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
            $serviciosCambioPorcentaje = $this->getPercentageChange($serviciosMesPasado, $serviciosEsteMes);

            // 👥 Prospectos (Total)
            $prospectosCount = \App\Models\Prospects::count();

            // 🟡 Servicios en curso (Global) - Using Asignaciones filters by missing firma
            $queryServicios = Asignaciones::doesntHave('getFirma')
                ->with(['cliente', 'tecnico']);

            if($request->has('date_from') && $request->has('date_to')) {
                $queryServicios->whereBetween('created_at', [$startChart, $endChart]);
                $serviciosEnCurso = $queryServicios->get();
            } else {
                $serviciosEnCurso = $queryServicios->latest()->limit(10)->get();
            }

            // Chart Data (All services this month)
            $queryChart = Asignaciones::whereBetween('created_at', [$startChart, $endChart]);
            $chartData = $this->getChartData($queryChart);

            return view('admin.dashboard', compact(
                'clientesEsteMes', 'clientesMesPasado', 'clientesCambioPorcentaje',
                'tecnicosEsteMes', 'tecnicosMesPasado', 'tecnicosCambioPorcentaje',
                'serviciosEsteMes', 'serviciosMesPasado', 'serviciosCambioPorcentaje',
                'prospectosCount',
                'serviciosEnCurso',
                'chartData',
                'monthLabel'
            ));
    
        } else {
            // TECHNICIAN DATA

            // Servicios Asignados (Total assigned to this tech)
            $serviciosAsignadosCount = Asignaciones::whereTecnicoId(Auth::user()->id)->count();

            // Servicios En Curso (Assigned to this tech and not finished/signed)
            $serviciosEnCursoCount = Asignaciones::whereTecnicoId(Auth::user()->id)
                ->doesntHave('getFirma')
                ->count();
    
            // Listado Servicios en curso
            $queryServiciosTech = Asignaciones::whereTecnicoId(Auth::user()->id)
                ->doesntHave('getFirma')
                ->with(['cliente', 'tecnico']);

            if($request->has('date_from') && $request->has('date_to')) {
                $queryServiciosTech->whereBetween('created_at', [$startChart, $endChart]);
                $serviciosEnCurso = $queryServiciosTech->get();
            } else {
                $serviciosEnCurso = $queryServiciosTech->latest()->limit(10)->get();
            }
            
            // Chart Data (My services this month)
            $queryChart = Asignaciones::whereTecnicoId(Auth::user()->id)->whereBetween('created_at', [$startChart, $endChart]);
            $chartData = $this->getChartData($queryChart);

            return view('admin.dashboard', compact(
                'serviciosAsignadosCount',
                'serviciosEnCursoCount',
                'serviciosEnCurso',
                'chartData',
                'monthLabel'
            ));
        }
    }

    private function getChartData($query)
    {
        // Clone query to avoid modifying original if it was passed by reference
        $data = $query->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        
        $formatted = [];
        foreach($data as $row) {
             // Flot expects [x, y]. We use day of month for X.
            $formatted[] = [ Carbon::parse($row->date)->day, $row->count ];
        }
        return $formatted;
    }

    private function getPercentageChange($old, $new)
    {
        if ($old == 0) return $new > 0 ? 100 : 0;
        return round((($new - $old) / $old) * 100, 2);
    }
}
