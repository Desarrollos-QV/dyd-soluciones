<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Devices, Cliente, Unidades};
use Illuminate\Http\Request;

class DevicesController extends Controller
{
    protected $folder = 'admin.devices';

    /**
     * Summary of index
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $devices = Devices::with(['cliente', 'unidad'])->get();
        return view($this->folder . '.index', compact('devices'));
    }

    /**
     * Summary of create
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $device   = new Devices;
        $clientes = Cliente::all();
        $unidades = Unidades::all();
        return view($this->folder . '.create', compact('device','clientes', 'unidades'));
    }

    /**
     * Summary of store
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'dispositivo' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'camaras' => 'required|string|max:100',
            'generacion' => 'required|string|max:100',
            'imei' => 'required|string|max:100',
            'garantia' => 'required|string|max:100',
            'accesorios' => 'nullable|string',
            'ia' => 'nullable|string',
            'otra_empresa' => 'nullable|string',
            'stock_min_alert' => 'required|integer',
            'stock' => 'required|integer'
        ]);

        $data = $request->all();
        $data['garantia'] = \Carbon\Carbon::parse($request->garantia)->format('Y-m-d H:i:s');

        try {
            Devices::create($data); 
            return response()->json([
                'ok' => true,
                'message' => 'Dispositivo creado exitosamente',
                'code' => 200,
                'redirect' => route('devices.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear el Dispositivo: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Summary of edit
     * @param \App\Models\Devices $device
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Devices $device)
    {
        $clientes = Cliente::all();
        $unidades = Unidades::all();
        return view($this->folder . '.edit', compact('device', 'clientes', 'unidades'));
    }

    /**
     * Summary of update
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Devices $device
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Devices $device)
    {
        $request->validate([
            'dispositivo' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'camaras' => 'required|string|max:100',
            'generacion' => 'required|string|max:100',
            'imei' => 'required|string|max:100',
            'garantia' => 'required|string|max:100',
            'accesorios' => 'nullable|string',
            'ia' => 'nullable|string',
            'otra_empresa' => 'nullable|string',
            'stock_min_alert' => 'required|integer',
            'stock' => 'required|integer'
        ]);

        $data = $request->all();
        $data['garantia'] = \Carbon\Carbon::parse($request->garantia)->format('Y-m-d H:i:s');
    
        try {
            $device->update($data);
            return response()->json([
                'ok' => true,
                'message' => 'Dispositivo Actualizado exitosamente',
                'code' => 200,
                'redirect' => route('devices.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear el Dispositivo: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Summary of destroy
     * @param \App\Models\Devices $device
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Devices $device)
    {
        try {
            $device->delete();
            return redirect()->route('devices.index')->with('success', 'Dispositivo eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el dispositivo: ' . $e->getMessage());
        }
    }
}