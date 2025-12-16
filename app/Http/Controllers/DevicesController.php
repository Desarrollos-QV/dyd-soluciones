<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Devices, Cliente, Unidades, DeviceImei};
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
        $device = new Devices;
        $clientes = Cliente::all();
        $unidades = Unidades::all();
        return view($this->folder . '.create', compact('device', 'clientes', 'unidades'));
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
            'generacion' => 'required|string|max:100',
            'imei' => 'required|array',
            'imei.*' => 'string|max:100', // Validate each IMEI
            'garantia' => 'required|string|max:100',
            'ia' => 'nullable|string',
            'otra_empresa' => 'nullable|string',
            'stock_min_alert' => 'required|integer',
            // 'stock' => 'required|integer' // Handled automatically
        ]);

        $data = $request->all();
        $data['garantia'] = \Carbon\Carbon::parse($request->garantia)->format('Y-m-d H:i:s');

        $imeis = $request->input('imei', []);
        $data['stock'] = count($imeis);
        // Save first IMEI to legacy column for compatibility, or null
        $data['imei'] = $imeis[0] ?? null;

        try {
            $device = Devices::create($data);

            // Save IMEIs
            foreach ($imeis as $imei) {
                if (!empty($imei)) {
                    DeviceImei::create([
                        'device_id' => $device->id,
                        'imei' => $imei
                    ]);
                }
            }

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
        // Load imeis just in case accessing it doesn't auto-load (though it usually lazy loads)
        // But better to be explicit or rely on View.
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
            'generacion' => 'required|string|max:100',
            'imei' => 'required|array',
            'imei.*' => 'string|max:100',
            'garantia' => 'required|string|max:100',
            'ia' => 'nullable|string',
            'otra_empresa' => 'nullable|string',
            'stock_min_alert' => 'required|integer',
            // 'stock' => 'required|integer'
        ]);

        $data = $request->all();
        $data['garantia'] = \Carbon\Carbon::parse($request->garantia)->format('Y-m-d H:i:s');

        $imeis = $request->input('imei', []);
        $data['stock'] = count($imeis);
        $data['imei'] = $imeis[0] ?? null;

        try {
            $device->update($data);

            // Sync IMEIs
            $device->imeis()->delete();
            foreach ($imeis as $imei) {
                if (!empty($imei)) {
                    DeviceImei::create([
                        'device_id' => $device->id,
                        'imei' => $imei
                    ]);
                }
            }

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

    /**
     * Summary of deleteSelected
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteSelected(Request $request)
    {
        $ids = $request->input('ids', []);

        try {
            Devices::whereIn('id', $ids)->delete();
            return response()->json([
                'ok' => true,
                'message' => 'Dispositivos eliminados exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al eliminar los dispositivos: ' . $e->getMessage(),
            ]);
        }
    }
}