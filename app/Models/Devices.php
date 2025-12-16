<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Devices extends Model
{
    use HasFactory;

    protected $fillable = [
        'dispositivo',
        'marca',
        'camaras',
        'generacion',
        'imei',
        'garantia',
        'accesorios',
        'ia',
        'cliente_id',
        'unidad_id',
        'otra_empresa',
        'stock',
        'stock_min_alert',
        'observations'
    ];


    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function unidad()
    {
        return $this->belongsTo(Unidades::class, 'unidad_id');
    }

    public function imeis()
    {
        return $this->hasMany(DeviceImei::class, 'device_id');
    }


}
