<?php

namespace App\Http\Controllers;

use App\Traits\NotifiesUsers;
use App\Models\{
    Unidades,
    Cliente,
    Devices,
    SimControl
};
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UnidadesImport;

class UnidadesController extends Controller
{
    
    use NotifiesUsers;

    public function index()
    {
        $unidades = Unidades::with('cliente','simcontrol','inventario')->orderBy('id', 'desc')->get();
        $clientes = Cliente::all();
        /**
         * Agregamos validacion para mostrar unicamente Sims que no esten asignadas a una unidad
         */
        
        $Allsimcontrols    = SimControl::all();
        $simcontrolsList = [];
        foreach ($unidades as $unit) {
            foreach($Allsimcontrols as $sim){
                if ($unit->simcontrol_id != $sim->id) {
                    $simcontrolsList[] = $sim->id;
                }
            }
        }

        $simcontrols = SimControl::whereIn('id', $simcontrolsList)->get();

        $devicesList    = Devices::all();
        $devices        = [];
        
        foreach ($devicesList as $device) {
            if ($device->stock >= $device->stock_min_alert) {
                $devices[] = $device;
            }
        }

        return view('admin.unidades.index', compact('unidades','clientes', 'simcontrols' ,'devices'));
    }

    public function show(Unidades $unidade)
    {
        $unidad = $unidade;
        return view('admin.unidades.show', compact('unidad'));
    }

    public function create()
    {
        $unidad     = new Unidades;
        $clientes   = Cliente::all();
        
        /**
         * Agregamos validacion para mostrar unicamente Sims que no esten asignadas a una unidad
         */
        $unidades = Unidades::with('cliente','simcontrol','inventario')->orderBy('id', 'desc')->get();
        $Allsimcontrols    = SimControl::all();
        $simcontrolsList = [];
        foreach ($unidades as $unit) {
            foreach($Allsimcontrols as $sim){
                if ($unit->simcontrol_id != $sim->id) {
                    $simcontrolsList[] = $sim->id;
                }
            }
        }
        $simcontrols = SimControl::whereIn('id', $simcontrolsList)->get();

        

        $devicesList    = Devices::all();
        $sensores       = Devices::where('stock', '>', 0)
            ->where(function($q) {
                $q->where('type', 'sensor')->orWhere('type', 'SENSOR');
            })->get();
        $devices        = Devices::where('stock', '>', 0)->get();

        return view('admin.unidades.create', compact('unidad', 'clientes', 'simcontrols' ,'devices', 'sensores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id'            => 'required|string|max:100', 
            'economico'             => 'required|string|max:50',
            'placa'                 => 'required|string|max:50',
            'tipo_unidad'           => 'required|string|max:100',
            'fecha_instalacion'     => 'required|string',
            'fecha_cobro'           => 'required|date',
            'anio_unidad'           => 'nullable|string|max:50',
            'marca'                 => 'nullable|string|max:100',
            'submarca'              => 'nullable|string|max:100',
            'numero_de_motor'       => 'nullable|string|max:100',
            'sensor'                => 'nullable|string|max:100',
            'vin'                   => 'nullable|string|max:100',
            'np_sim'                => 'nullable|string|max:100',
            'cuenta_con_apagado'    => 'nullable|string', 
            'numero_de_emergencia'  => 'nullable|string',
            'observaciones'         => 'nullable|string',
            'costo_plataforma'      => 'nullable|string',
            'costo_sim'             => 'nullable|string',
            'pago_mensual'          => 'nullable|string',
            'name_empresa'          => 'nullable|string',
            'credenciales_user'     => 'nullable|array',
            'credenciales_pass'     => 'nullable|array',
            'simcontrol_id'         => 'required|string',
            'devices_id'            => 'required|string',
        ]);

        try {
            $data = $request->all();

            $data['credenciales'] = json_encode([
                'user' => $request->credenciales_user,
                'pass' => $request->credenciales_pass,
            ]);

            $data['fecha_instalacion'] = \Carbon\Carbon::parse($request->fecha_instalacion)->format('Y-m-d');
            $data['fecha_cobro'] = \Carbon\Carbon::parse($request->fecha_cobro)->format('Y-m-d');

            // 1. Validar existencia y stock del dispositivo ANTES de insertar
            $device = Devices::find($data['devices_id']);
            if (!$device) {
                return response()->json([
                    'ok' => false,
                    'message' => 'El dispositivo seleccionado no existe en el inventario.',
                ], 422);
            }

            if ($device->stock < 1) {
                return response()->json([
                    'ok' => false,
                    'message' => 'El dispositivo "' . $device->dispositivo . '" no cuenta con stock disponible (stock actual: ' . $device->stock . '). Por favor seleccione o registre otro dispositivo.',
                ], 422);
            }

            // 2. Validar stock del sensor si fue seleccionado
            $sensorDev = null;
            if (!empty($data['sensor']) && $data['sensor'] != 0) {
                $sensorDev = Devices::find($data['sensor']);
                if ($sensorDev && $sensorDev->stock < 1) {
                    return response()->json([
                        'ok' => false,
                        'message' => 'El sensor/accesorio "' . $sensorDev->dispositivo . '" no cuenta con stock disponible (stock actual: ' . $sensorDev->stock . ').',
                    ], 422);
                }
            }

            // 3. Crear la unidad y descontar stock dentro de una transacción segura
            $nuevaUnidad = DB::transaction(function () use ($data, $device, $sensorDev) {
                $unidad = Unidades::create($data);

                // Descontar 1 del stock del dispositivo
                $device->decrement('stock');
                $device->refresh();

                // Descontar 1 del stock del sensor si fue asignado
                if ($sensorDev) {
                    $sensorDev->decrement('stock');
                    $sensorDev->refresh();
                }

                return $unidad;
            });

            // 4. Si el stock llegó al umbral de alerta, notificar al administrador
            if ($device->stock <= $device->stock_min_alert) {
                $this->notifyUser(
                    1, // Administración
                    'stock_alert',
                    'Stock Alerta',
                    'El dispositivo ' . $device->dispositivo . ' ha alcanzado o está por debajo del stock mínimo (' . $device->stock . ' restantes).',
                    [],
                    route('devices.index'),
                    now()->addDays(7)
                );
            }

            if ($sensorDev && $sensorDev->stock <= $sensorDev->stock_min_alert) {
                $this->notifyUser(
                    1, // Administración
                    'stock_alert',
                    'Stock Alerta',
                    'El sensor/accesorio ' . $sensorDev->dispositivo . ' ha alcanzado o está por debajo del stock mínimo (' . $sensorDev->stock . ' restantes).',
                    [],
                    route('devices.index'),
                    now()->addDays(7)
                );
            }

            // Sincronizar cobranza para esta unidad de forma inmediata
            if (!empty($nuevaUnidad->fecha_cobro) && !empty($nuevaUnidad->cliente_id)) {
                $fechaCobro = \Carbon\Carbon::parse($nuevaUnidad->fecha_cobro);
                $hoy = \Carbon\Carbon::today();
                $estatus = $fechaCobro->lt($hoy) ? 'overdue' : 'pending';

                \App\Models\Collection::updateOrCreate(
                    [
                        'unidad_id' => $nuevaUnidad->id,
                        'status'    => $estatus == 'overdue' ? 'overdue' : 'pending',
                    ],
                    [
                        'cliente_id' => $nuevaUnidad->cliente_id,
                        'due_date'   => $fechaCobro->toDateString(),
                        'amount'     => $nuevaUnidad->pago_mensual ?? 0,
                        'status'     => $estatus,
                    ]
                );
            }

            return response()->json([
                'ok' => true,
                'message' => 'Elemento creado con éxito.',
                'code' => 200,
                'unidad' => $nuevaUnidad,
                'redirect' => route('unidades.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al crear la unidad: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function edit(Unidades $unidade)
    {
        $unidad = $unidade;
        $clientes = Cliente::all();
        $simcontrols = SimControl::all();
        $devicesList = Devices::all();

        // Mostrar dispositivos con stock > 0 o el que actualmente tenga asignado la unidad
        $devices = Devices::where(function($q) use ($unidade) {
            $q->where('stock', '>', 0);
            if ($unidade->devices_id) {
                $q->orWhere('id', $unidade->devices_id);
            }
        })->get();

        $sensores = Devices::where(function($q) use ($unidade) {
            $q->where('stock', '>', 0);
            if ($unidade->sensor) {
                $q->orWhere('id', $unidade->sensor);
            }
        })->where(function($q) {
            $q->where('type', 'sensor')->orWhere('type', 'SENSOR');
        })->get();

        return view('admin.unidades.edit', compact('unidad','clientes', 'simcontrols' ,'devices', 'sensores'));
    }

    public function update(Request $request, Unidades $unidade)
    {
         $request->validate([
            'cliente_id'            => 'required|string|max:100', 
            'economico'             => 'required|string|max:50',
            'placa'                 => 'required|string|max:50',
            'tipo_unidad'           => 'required|string|max:20',
            'fecha_instalacion'     => 'required|string',
            'fecha_cobro'           => 'required|string',
            'anio_unidad'           => 'nullable|string|max:50',
            'marca'                 => 'nullable|string|max:30',
            'submarca'              => 'nullable|string|max:30',
            'numero_de_motor'       => 'nullable|string|max:100',
            'sensor'                => 'nullable|string|max:100',
            'vin'                   => 'nullable|string|max:50',
            'np_sim'                => 'nullable|string|max:100',
            'cuenta_con_apagado'    => 'nullable|string', 
            'numero_de_emergencia'  =>  'nullable|string',
            'observaciones'         =>  'nullable|string',
            'costo_plataforma'      => 'nullable|string',
            'costo_sim'             => 'nullable|string',
            'pago_mensual'          => 'nullable|string',
            'name_empresa'          => 'nullable|string',
            'credenciales_user'     => 'nullable|array',
            'credenciales_pass'     => 'nullable|array',
            'simcontrol_id'         => 'required|string',
            'devices_id'            => 'required|string',
        ]);

        try {
            $data = $request->all();

            $data['credenciales'] = json_encode([
                'user' => $request->credenciales_user,
                'pass' => $request->credenciales_pass,
            ]);

            $data['fecha_instalacion'] = \Carbon\Carbon::parse($request->fecha_instalacion)->format('Y-m-d');
            $data['fecha_cobro'] = \Carbon\Carbon::parse($request->fecha_cobro)->format('Y-m-d');

            // Validar stock si se cambió el dispositivo
            $newDeviceId = $data['devices_id'] ?? null;
            $oldDeviceId = $unidade->devices_id;
            $newDevice = null;

            if ($newDeviceId && $newDeviceId != $oldDeviceId) {
                $newDevice = Devices::find($newDeviceId);
                if (!$newDevice) {
                    return response()->json([
                        'ok' => false,
                        'message' => 'El dispositivo seleccionado no existe en el inventario.',
                    ], 422);
                }
                if ($newDevice->stock < 1) {
                    return response()->json([
                        'ok' => false,
                        'message' => 'El dispositivo "' . $newDevice->dispositivo . '" no cuenta con stock disponible (stock actual: ' . $newDevice->stock . ').',
                    ], 422);
                }
            }

            // Validar stock si se cambió el sensor
            $newSensorId = !empty($data['sensor']) && $data['sensor'] != 0 ? $data['sensor'] : null;
            $oldSensorId = $unidade->sensor;
            $newSensor = null;

            if ($newSensorId && $newSensorId != $oldSensorId) {
                $newSensor = Devices::find($newSensorId);
                if ($newSensor && $newSensor->stock < 1) {
                    return response()->json([
                        'ok' => false,
                        'message' => 'El sensor/accesorio "' . $newSensor->dispositivo . '" no cuenta con stock disponible (stock actual: ' . $newSensor->stock . ').',
                    ], 422);
                }
            }

            DB::transaction(function () use ($unidade, $data, $oldDeviceId, $newDeviceId, $newDevice, $oldSensorId, $newSensorId, $newSensor) {
                $unidade->update($data);

                // Ajustar inventario si cambió de dispositivo
                if ($newDeviceId && $newDeviceId != $oldDeviceId) {
                    if ($oldDeviceId) {
                        Devices::where('id', $oldDeviceId)->increment('stock');
                    }
                    if ($newDevice) {
                        $newDevice->decrement('stock');
                    }
                }

                // Ajustar inventario si cambió de sensor
                if ($newSensorId != $oldSensorId) {
                    if ($oldSensorId) {
                        Devices::where('id', $oldSensorId)->increment('stock');
                    }
                    if ($newSensor) {
                        $newSensor->decrement('stock');
                    }
                }
            });

            // Sincronizar cobranza para esta unidad de forma inmediata
            if (!empty($unidade->fecha_cobro) && !empty($unidade->cliente_id)) {
                $fechaCobro = \Carbon\Carbon::parse($unidade->fecha_cobro);
                $hoy = \Carbon\Carbon::today();
                $estatus = $fechaCobro->lt($hoy) ? 'overdue' : 'pending';

                \App\Models\Collection::where('unidad_id', $unidade->id)
                    ->where('status', '!=', 'paid')
                    ->update([
                        'cliente_id' => $unidade->cliente_id,
                        'due_date'   => $fechaCobro->toDateString(),
                        'amount'     => $unidade->pago_mensual ?? 0,
                        'status'     => $estatus,
                    ]);
            }

            return response()->json([
                'ok' => true,
                'message' => 'Elemento actualizado con éxito.',
                'code' => 200,
                'redirect' => route('unidades.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error al actualizar la unidad: ' . $e->getMessage(),
            ], 500);
        }
    }

    protected function deleteUnidadCompletely(Unidades $unidad): void
    {
        DB::transaction(function () use ($unidad) {
            // Eliminar las imágenes asociadas si existen
            if ($unidad->foto_unidad) {
                $fotos = json_decode($unidad->foto_unidad, true);
                if (is_array($fotos)) {
                    foreach ($fotos as $foto) {
                        $filePath = public_path('uploads/fotos_unidades/' . $foto);
                        if (File::exists($filePath)) {
                            File::delete($filePath);
                        }
                    }
                }
            }

            // Desvincular dispositivos y asignaciones que referencien esta unidad
            DB::table('devices')->where('unidad_id', $unidad->id)->update(['unidad_id' => null]);
            DB::table('asignaciones')->where('unidad_id', $unidad->id)->update(['unidad_id' => null]);
            DB::table('asignacion_unidades')->where('unidad_id', $unidad->id)->delete();

            // Eliminar registros de cobranza asociados a esta unidad
            $collectionIds = DB::table('collections')->where('unidad_id', $unidad->id)->pluck('id');
            if ($collectionIds->isNotEmpty()) {
                DB::table('collections_payments')->whereIn('collection_id', $collectionIds)->delete();
                DB::table('collections')->whereIn('id', $collectionIds)->delete();
            }

            $unidad->delete();
        });
    }

    public function destroy($id)
    {
        try {
            $unidad = Unidades::findOrFail($id);
            $this->deleteUnidadCompletely($unidad);

            return redirect()->route('unidades.index')->with('success', 'Unidad eliminada exitosamente.');
        } catch (QueryException $e) {
            $mensaje = $e->getCode() == '23000'
                ? 'No se puede eliminar la unidad porque tiene registros vinculados (cobranza, servicios o dispositivos).'
                : 'Ocurrió un problema en la base de datos al intentar eliminar la unidad.';
            return redirect()->route('unidades.index')->with('error', $mensaje);
        } catch (\Exception $e) {
            return redirect()->route('unidades.index')->with('error', 'No se pudo eliminar la unidad seleccionada. Intente nuevamente.');
        }
    }

    public function AssignClient($id , $client)
    {
        $prospect = Unidades::findOrFail($id);
        $prospect->cliente_id = $client;
        $prospect->save();

        return redirect()->route('unidades.index')->with('success', 'Cliente asignado a la Unidad exitosamente');
    }

    public function AssignDisp($id , $disp)
    {
        $prospect = Unidades::findOrFail($id);
        $prospect->dispositivo_instalado = $disp;
        $prospect->save();

        return redirect()->route('unidades.index')->with('success', 'Dispositivo asignado a la Unidad exitosamente');
    }

    public function assignDevice($id , $device)
    {
        $unidad = Unidades::findOrFail($id);

        // Verificamos si el device = 0 para dejar sin asignar y devolver el stock
        if($device == 0)
        {
            // Devolvemos el stock del dispositivo anterior si existía
            if($unidad->devices_id != null && $unidad->devices_id != 0)
            {
                Devices::find($unidad->devices_id)->increment('stock');
            }

            $unidad->devices_id = null;
            $unidad->save();

            return redirect()->route('unidades.index')->with('success', 'Dispositivo desasignado de la Unidad exitosamente');
        }else{
            // Si se está asignando un nuevo dispositivo, decrementamos el stock
            if($unidad->devices_id != $device)
            {
                // Devolvemos el stock del dispositivo anterior
                Devices::find($unidad->devices_id)?->increment('stock');
            }

            /**
             * Validamos si el stock es mayor a 1 como minimo
             */
            $stockDevice = Devices::find($device)->stock;
            if($stockDevice <= 1){
                return response()->json([
                    'ok' => false,
                    'message' => 'El stock es menor a 1, no se puede asignar el dispositivo.',
                ]);
            }

            /**
             * Decrementamos el stock del nuevo dispositivo asignado
             */
            Devices::find($device)?->decrement('stock');
            // Si el stock es menor al minimo para alerta notificamos
            if(Devices::find($device)?->stock <= Devices::find($device)?->stock_min_alert){
                // Generamos la notificación para el técnico asignado
                $this->notifyUser(
                    1, // Administracion
                    'stock_alert',
                    'Stock Alerta',
                    'Se ha creado un nuevo dispositivo con stock menor al minimo para alerta.',
                    [],
                    route('devices.index'),
                    now()->addDays(7)
                );
            }
        }

        $unidad->devices_id = $device;
        $unidad->save();

        return redirect()->route('unidades.index')->with('success', 'Dispositivo asignado a la Unidad exitosamente');
    }

    public function assignSIM($id , $sim)
    {
        $unidad = Unidades::findOrFail($id);
        $unidad->simcontrol_id = $sim == 0 ? null : $sim;
        $unidad->save();

        return redirect()->route('unidades.index')->with('success', 'SIM asignada a la Unidad exitosamente');
    }

    public function uploads(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $names = [];
            foreach ($file as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/fotos_unidades'), $filename);
                $names[] = $filename;
            }

            return response()->json([
                'ok' => true,
                'message' => 'Archivo subido con éxito.',
                'file_name' => $names
            ]);
        } else {
            return response()->json([
                'ok' => false,
                'message' => 'No se ha subido ningún archivo.'
            ]);
        }
    }

    function fetchUploads($id)
    {
        $unidad = Unidades::findOrFail($id);
        $images = [];
        
        if ($unidad->foto_unidad) {
            $fotos = json_decode($unidad->foto_unidad);
            foreach ($fotos as $key => $foto) {
                $images[] = [
                    'id' => $key,
                    'name' => basename($foto),
                    'size' => file_exists(public_path('uploads/fotos_unidades/' . $foto)) ? filesize(public_path('uploads/fotos_unidades/' . $foto)) : 0,
                    'url' => asset('uploads/fotos_unidades/' . $foto)
                ];
            }
        }

        return response()->json([
            'images' => $images
        ]);
    }

    function deleteUploads(Request $request)
    {
        $unidad = Unidades::find($request->get('id_unidad'));
        $images = $unidad->foto_unidad ? json_decode($unidad->foto_unidad) : [];
        $images_to_keep = [];
        
        foreach($images as $index => $image)
        {
            if($image != $request->get('name'))
            {
                $images_to_keep[] = $image;
            }
            else
            {
                // Eliminar el archivo de la carpeta
                $file_path = public_path('uploads/fotos_unidades/' . $image);
                if(File::exists($file_path))
                {
                    File::delete($file_path);
                }
            }
        }

        $unidad->foto_unidad = json_encode($images_to_keep);;
        $unidad->save();

        return response()->json([
            'ok' => true,
            'message' => 'Imagen eliminada con éxito.'
        ]);
      
    }

    public function deleteSelected(Request $request)
    {
        $ids = $request->input('ids', []);

        try {
            foreach ($ids as $id) {
                $unidad = Unidades::findOrFail($id);
                $this->deleteUnidadCompletely($unidad);
            }

            return response()->json([
                'ok' => true,
                'message' => 'Unidades eliminadas con éxito.',
            ]);
        } catch (QueryException $e) {
            $mensaje = $e->getCode() == '23000'
                ? 'No se pueden eliminar una o más unidades porque tienen registros vinculados (cobranza, servicios o dispositivos).'
                : 'Ocurrió un problema en la base de datos al intentar eliminar las unidades.';
            return response()->json([
                'ok' => false,
                'message' => $mensaje,
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'No se pudieron eliminar las unidades seleccionadas. Intente nuevamente.',
            ], 500);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new UnidadesImport, $request->file('file'));
            return back()->with('success', 'Unidades importadas correctamente.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $messages = [];
            foreach ($failures as $failure) {
                $messages[] = 'Fila ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return back()->withErrors($messages);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al importar: ' . $e->getMessage());
        }
    }
}