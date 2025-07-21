<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Tecnico;
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
    public function index()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // 🔢 Clientes
        $clientesEsteMes = Cliente::whereBetween('created_at', [$startOfMonth, $now])->count();
        $clientesMesPasado = Cliente::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $clientesCambioPorcentaje = $this->getPercentageChange($clientesMesPasado, $clientesEsteMes);

        // 🔧 Técnicos
        $tecnicosEsteMes = User::whereRole('tecnico')->whereBetween('created_at', [$startOfMonth, $now])->count();
        $tecnicosMesPasado = User::whereRole('tecnico')->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $tecnicosCambioPorcentaje = $this->getPercentageChange($tecnicosMesPasado, $tecnicosEsteMes);

        if(Auth::user()->isAdmin()) {
            // 🛠 Servicios
            $serviciosEsteMes = ServiciosAgendado::whereBetween('created_at', [$startOfMonth, $now])->count();
            $serviciosMesPasado = ServiciosAgendado::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
            $serviciosCambioPorcentaje = $this->getPercentageChange($serviciosMesPasado, $serviciosEsteMes);

            // 🟡 Servicios en curso
            $serviciosEnCurso = ServiciosAgendado::where('firma_cliente', null)->with(['cliente', 'tecnico'])->latest()->limit(10)->get();
    
        }else {
            // 🛠 Servicios
            $serviciosEsteMes = ServiciosAgendado::whereUserId(Auth::user()->id)->whereBetween('created_at', [$startOfMonth, $now])->count();
            $serviciosMesPasado = ServiciosAgendado::whereUserId(Auth::user()->id)->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
            $serviciosCambioPorcentaje = $this->getPercentageChange($serviciosMesPasado, $serviciosEsteMes);
    
            // 🟡 Servicios en curso
            $serviciosEnCurso = ServiciosAgendado::whereUserId(Auth::user()->id)->where('firma_cliente', null)->with(['cliente', 'tecnico'])->latest()->limit(10)->get();
        
        }

        // 📅 Servicios últimos 3 meses
        $servicios3Meses = ServiciosAgendado::where('created_at', '>=', now()->subMonths(3))->get();
        $serviciosFinalizados = $servicios3Meses->where('firma_cliente', '!=', null)->count();
        $serviciosTotales3Meses = $servicios3Meses->count();

       
        // return response()->json([
        //    compact( 
        //     'clientesEsteMes', 
        //     'clientesMesPasado', 
        //     'clientesCambioPorcentaje',
        //     'tecnicosEsteMes', 
        //     'tecnicosMesPasado', 
        //     'tecnicosCambioPorcentaje',
        //     'serviciosEsteMes', 
        //     'serviciosMesPasado', 
        //     'serviciosCambioPorcentaje',
        //     'serviciosTotales3Meses', 
        //     'serviciosFinalizados',
        //     'serviciosEnCurso'
        //     )
        // ]);

        return view('admin.dashboard', compact(
            'clientesEsteMes', 'clientesMesPasado', 'clientesCambioPorcentaje',
            'tecnicosEsteMes', 'tecnicosMesPasado', 'tecnicosCambioPorcentaje',
            'serviciosEsteMes', 'serviciosMesPasado', 'serviciosCambioPorcentaje',
            'serviciosTotales3Meses', 'serviciosFinalizados',
            'serviciosEnCurso'
        ));
    }


    private function getPercentageChange($old, $new)
    {
        if ($old == 0) return $new > 0 ? 100 : 0;
        return round((($new - $old) / $old) * 100, 2);
    }
}
