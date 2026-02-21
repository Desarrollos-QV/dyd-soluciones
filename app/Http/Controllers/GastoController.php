<?php 

namespace App\Http\Controllers;

use App\Models\{
    User,
    Gasto,
    HistorialCaja
};
use Illuminate\Http\Request;

class GastoController extends Controller
{
    public function index()
    {
        $gastos = Gasto::orderBy('fecha', 'desc')->where('tipo','egreso')->with(['Autoriza','Solicita'])->paginate(10);
      
        return view('admin.gastos.index', compact('gastos'));
    }

    public function create()
    {
        
        $tecnicos = User::whereRole('tecnico')->whereIsActive('1')->get();
        $listPerms = ['gastos.index','gastos.create','gastos.edit','historial-caja.index','historial-caja.create','collections.index','collections.create'];
 
        $administradores = User::whereRole('subadmin')
        ->whereIsActive('1') // Que este activo
        ->where('id','!=',1) // Que no sea el super admin
        // Validamos si cuenta con los permisos para gestion de caja
        ->where(function ($query) use ($listPerms) {
            foreach ($listPerms as $perm) {
                $query->orWhere('permissions', 'like', "%{$perm}%");
            }
        })
        ->get();
        return view('admin.gastos.create', compact('tecnicos','administradores'));
    }

    public function store(Request $request)
    {

        try {
            $request->validate([
                'fecha' => 'required|date',
                'hora' => 'required',
                'autorizado_por' => 'nullable|string|max:255',
                'monto' => 'required|min:0',
                'descripcion' => 'required|string',
                'solicitado_por' => 'nullable|string|max:255',
                'motivo' => 'required|string|max:255',
                'tipo' => 'required|string|max:255',
            ]);

            $data  = $request->all();
            $monto = floatval(str_replace(',', '', $request->get('monto')));
            $data['monto'] = $monto;

            $gasto = Gasto::create($data);

            // Registrar en el historial de caja
            HistorialCaja::create([
                'fecha' => $gasto->fecha,
                'hora' => $gasto->hora,
                'tipo' => $gasto->tipo,
                'concepto' => $gasto->descripcion,
                'monto' => $gasto->monto,
                'autorizado_por' => $gasto->autorizado_por,
                'descripcion' => $gasto->motivo,
                'user_id' => auth()->id(),
            ]);


            return response()->json([
                'data' => $data,
                'ok' => true,
                'message' => 'Elemento creado con éxito.',
                'code' => 200,
                'redirect' => route('gastos.index')
            ]);
            // return redirect()->route('gastos.index')->with('success', 'Gasto registrado correctamente.');
        } catch (\Exception $e) {
            // return redirect()->route('inventarios.index')->with('error', 'Error al crear el inventario: ' . $e->getMessage());
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear el movimiento: ' . $e->getMessage(),
            ]);
        }
    }

    public function edit(Gasto $gasto)
    {
        
        $tecnicos = User::whereRole('tecnico')->whereIsActive('1')->get();
        $listPerms = ['gastos.index','gastos.create','gastos.edit','historial-caja.index','historial-caja.create','collections.index','collections.create'];
 
        $administradores = User::whereRole('subadmin')
        ->whereIsActive('1') // Que este activo
        ->where('id','!=',1) // Que no sea el super admin
        // Validamos si cuenta con los permisos para gestion de caja
        ->where(function ($query) use ($listPerms) {
            foreach ($listPerms as $perm) {
                $query->orWhere('permissions', 'like', "%{$perm}%");
            }
        })
        ->get();
        return view('admin.gastos.edit', compact('gasto','tecnicos','administradores'));
    }

    public function update(Request $request, Gasto $gasto)
    {
       
        try {
            $request->validate([
                'fecha' => 'required|date',
                'hora' => 'required',
                'autorizado_por' => 'required|string|max:255',
                'monto' => 'required|min:0',
                'descripcion' => 'required|string',
                'solicitado_por' => 'required|string|max:255',
                'motivo' => 'required|string|max:255',
                'tipo' => 'required|string|max:255',
            ]);

            $data  = $request->all();
            $monto = floatval(str_replace(',', '', $request->get('monto')));
            $data['monto'] = $monto;
            

            $gasto->update($data);

            // Actualizar en el historial de caja
            $historial = HistorialCaja::where('concepto', $gasto->descripcion)
                ->where('monto', $gasto->monto)
                ->where('fecha', $gasto->fecha)
                ->first();
            if ($historial) {
                $historial->update([
                    'fecha' => $gasto->fecha,
                    'hora' => $gasto->hora,
                    'tipo' => $gasto->tipo,
                    'concepto' => $gasto->descripcion,
                    'monto' => $gasto->monto,
                    'autorizado_por' => $gasto->autorizado_por,
                    'descripcion' => $gasto->motivo,
                ]);
            }


            return response()->json([
                'data' => $data,
                'ok' => true,
                'message' => 'Elemento actualizado con éxito.',
                'code' => 200,
                'redirect' => route('gastos.index')
            ]);
        }catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear el movimiento: ' . $e->getMessage(),
            ]);
        }
    }

    public function destroy(Gasto $gasto)
    {
        $gasto->delete();
        return redirect()->route('gastos.index')->with('success', 'Gasto eliminado correctamente.');
    }

    public function deleteSelected(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json([
                'ok' => false,
                'message' => 'No hay elementos seleccionados para eliminar.',
            ]);
        }

        try {
            Gasto::whereIn('id', $ids)->delete();

            return response()->json([
                'ok' => true,
                'message' => 'Elementos eliminados correctamente.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al eliminar los elementos: ' . $e->getMessage(),
            ]);
        }
    }
}
