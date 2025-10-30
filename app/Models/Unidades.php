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
        'dispositivo_instalado',
        'simcontrol_id',
        'devices_id',
        'anio_unidad',
        'marca',
        'submarca',
        'numero_de_motor',
        'costo_plataforma',
        'costo_sim',
        'pago_mensual',
        'name_empresa',
        'credenciales',
        'vin',
        'imei',
        'np_sim',
        'cuenta_con_apagado',
        'foto_unidad',
        'numero_de_emergencia',
        'observaciones',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class,'cliente_id');
    }

    public function simcontrol()
    {
        return $this->belongsTo(SimControl::class,'simcontrol_id');
    }

    public function device()
    {
        return $this->belongsTo(Devices::class,'devices_id');
    }
}
