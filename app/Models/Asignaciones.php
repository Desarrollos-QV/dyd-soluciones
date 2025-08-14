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
        'unidad_id',
        'coban_dvr',
        'pago_mensual',
        'fecha_inicio',
        'ultima_fecha_pago',
        'costo_plataforma',
        'costo_sim',
        'descuento',
        'ganancia',
        'cobro_adicional',        
        'fecha_ultimo_mantenimiento',
        'observaciones',
        'observaciones_mantenimiento'
    ];


    public function cliente()
    {
        return $this->belongsTo(Cliente::class,'cliente_id','id');
    }

    public function unidad()
    {
        return $this->belongsTo(Unidades::class,'unidad_id','id');
    }

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_id','id');
    }
}
