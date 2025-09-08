<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'otra_empresa'
    ];


    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function unidad()
    {
        return $this->belongsTo(Unidades::class, 'unidad_id');
    }
}
