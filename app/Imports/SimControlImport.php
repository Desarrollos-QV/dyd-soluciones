<?php

namespace App\Imports;

use App\Models\SimControl;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SimControlImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new SimControl([
            'type' => $row['type'],
            'compañia'     => $row['compania'], // Intentionally mapped to 'compania' (no tilde) from excel header
            'numero_sim'    => $row['numero_sim'], 
            'numero_publico' => $row['numero_publico'],
            'observaciones' => $row['observaciones'],
        ]);
    }

    public function rules(): array
    {
        return [
            'type' => 'required',
            'compania' => 'required',
            'numero_sim' => 'required|unique:simcontrol,numero_sim',
            'numero_publico' => 'required',
            'observaciones' => 'required',
        ];
    }
}
