<?php

namespace App\Imports;

use App\Models\Devices;
use App\Models\DeviceImei;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;

class DevicesImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Handle garanty date parsing. Excel dates are sometimes numbers.
        $garantia = null;
        try {
            if (is_numeric($row['garantia'])) {
                 $garantia = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['garantia']);
            } else {
                 $garantia = Carbon::parse($row['garantia']);
            }
        } catch (\Exception $e) {
            $garantia = Carbon::now(); // Default fallback or could be nullable
        }

        $device = Devices::create([
            'type'           => $row['type'],
            'dispositivo'    => $row['dispositivo'],
            'marca'          => $row['marca'], 
            'generacion'     => $row['generacion'],
            'imei'           => $row['imei'], // Legacy primary imei
            'garantia'       => $garantia,
            'ia'             => $row['ia'] ?? 'no',
            'otra_empresa'   => $row['otra_empresa'] ?? 'no',
            'stock_min_alert'=> $row['stock_min_alert'] ?? 0,
            'stock'          => $row['stock'] ?? 1, 
            'observations'   => $row['observations'] ?? '',
        ]);

        // Create the DeviceImei record
        DeviceImei::create([
            'device_id' => $device->id,
            'imei'      => $row['imei'],
        ]);

        return $device;
    }

    public function rules(): array
    {
        return [
            'type' => 'required',
            'dispositivo' => 'required',
            'marca' => 'required',
            'generacion' => 'required',
            'imei' => 'required|unique:devices,imei', 
            'garantia' => 'required',
        ];
    }
}
