<?php 

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\User;
use Illuminate\Http\Request;

class GastoController extends Controller
{
    public function index()
    {
        $gastos = Gasto::orderBy('fecha', 'desc')->with(['Autoriza','Solicita'])->paginate(10);

        return view('admin.gastos.index', compact('gastos'));
    }

    public function create()
    {
        
        $tecnicos = User::whereRole('tecnico')->whereIsActive('1')->get();
        return view('admin.gastos.create', compact('tecnicos'));
    }

    public function store(Request $request)
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
            ]);

            $data  = $request->all();
            $monto = floatval(str_replace(',', '', $request->get('monto')));
            $data['monto'] = $monto;

            Gasto::create($data);
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
        return view('admin.gastos.edit', compact('gasto','tecnicos'));
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
            ]);

            $data  = $request->all();
            $monto = floatval(str_replace(',', '', $request->get('monto')));
            $data['monto'] = $monto;
            

            $gasto->update($data);

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
}
