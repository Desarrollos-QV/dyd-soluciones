<?php

namespace App\Imports;

use App\Models\Unidades;
use App\Models\Devices;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class UnidadesImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Helper date parsing (similar to DevicesImport)
        $fecha_instalacion = null;
        if (isset($row['fecha_instalacion'])) {
            try {
                if (is_numeric($row['fecha_instalacion'])) {
                     $fecha_instalacion = Date::excelToDateTimeObject($row['fecha_instalacion'])->format('Y-m-d');
                } else {
                     $fecha_instalacion = Carbon::parse($row['fecha_instalacion'])->format('Y-m-d');
                }
            } catch (\Exception $e) {
                // Fallback or leave null if parse fails
                $fecha_instalacion = date('Y-m-d'); 
            }
        }

        $fecha_cobro = null;
        if (isset($row['fecha_cobro'])) {
            try {
                if (is_numeric($row['fecha_cobro'])) {
                     $fecha_cobro = Date::excelToDateTimeObject($row['fecha_cobro'])->format('Y-m-d');
                } else {
                     $fecha_cobro = Carbon::parse($row['fecha_cobro'])->format('Y-m-d');
                }
            } catch (\Exception $e) {
                 $fecha_cobro = date('Y-m-d');
            }
        }

        $unidad = Unidades::create([
            'cliente_id'            => $row['cliente_id'],
            'economico'             => $row['economico'],
            'placa'                 => $row['placa'],
            'tipo_unidad'           => $row['tipo_unidad'],
            'dispositivo_instalado' => $row['dispositivo_instalado'] ?? 'Otro', 
            'fecha_instalacion'     => $fecha_instalacion,
            'fecha_cobro'           => $fecha_cobro,
            'anio_unidad'           => $row['anio_unidad'] ?? null,
            'marca'                 => $row['marca'] ?? null,
            'submarca'              => $row['submarca'] ?? null,
            'numero_de_motor'       => $row['numero_de_motor'] ?? null,
            'sensor'                => $row['sensor'] ?? null,
            'vin'                   => $row['vin'] ?? null,
            // 'imei'                  => $row['imei'] ?? null, // Removed field
            'np_sim'                => $row['np_sim'] ?? null,
            'cuenta_con_apagado'    => $row['cuenta_con_apagado'] ?? 'No',
            'numero_de_emergencia'  => $row['numero_de_emergencia'] ?? null,
            'observaciones'         => $row['observaciones'] ?? null,
            'costo_plataforma'      => $row['costo_plataforma'] ?? 0,
            'costo_sim'             => $row['costo_sim'] ?? 0,
            'pago_mensual'          => $row['pago_mensual'] ?? 0,
            'name_empresa'          => $row['name_empresa'] ?? null,
            'simcontrol_id'         => $row['simcontrol_id'],
            'devices_id'            => $row['devices_id'],
            'credenciales'          => $row['credenciales'] ?? json_encode(['user' => '', 'pass' => '']), // Default empty creds
        ]);

        // Decrement stock if device assigned
        if (!empty($row['devices_id']) && $row['devices_id'] != 0) {
            $device = Devices::find($row['devices_id']);
            if ($device) {
                $device->decrement('stock');
            }
        }

        return $unidad;
    }

    public function rules(): array
    {
        return [
            'cliente_id'        => 'required', // Assuming IDs are passed
            'economico'         => 'required',
            'placa'             => 'required',
            'simcontrol_id'     => 'required', // Assuming IDs
            'devices_id'        => 'required', // Assuming IDs
            'fecha_instalacion' => 'required',
            'fecha_cobro'       => 'required',
        ];
    }
}
