<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidades extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_unidad',
        'fecha_instalacion',
        'precio',
        'economico',
        'placa',
        'anio_unidad',
        'vin',
        'imei',
        'sim_dvr',
        'marca_submarca',
        'numero_de_motor',
        'usuario',
        'password',
        'cuenta_con_apagado',
        'numero_de_emergencia',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
