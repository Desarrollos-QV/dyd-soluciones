<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignaciones extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'tecnico_id',
        'tipo_servicio',
        'tel_contact',
        'encargado_recibir',
        'location',
        'lat',
        'lng',
        'viaticos',
        'tipo_vehiculo',
        'marca',
        'modelo',
        'devices_id',
        'placa',
        'observaciones',
    ];


    public function cliente()
    {
        return $this->belongsTo(Cliente::class,'cliente_id',);
    }

    public function device()
    {
        return $this->belongsTo(Devices::class,'devices_id',);
    }

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }
}
