<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidades extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'economico',
        'placa',
        'tipo_unidad',
        'fecha_instalacion',
        'anio_unidad',
        'marca',
        'submarca',
        'numero_de_motor',
        'vin',
        'imei',
        'np_sim', //<-'sim_dvr',
        'cuenta_con_apagado',
        'foto_unidad',
        'numero_de_emergencia',
        'observaciones',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class,'cliente_id');
    }
}
