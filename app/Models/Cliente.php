<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'direccion', 'numero_contacto', 'numero_alterno',
        'pertenece_ruta', 'pago_mensual', 'fecha_inicio', 'fecha_vencimiento',
        'recordatorio', 'mensaje_personalizado', 'mensaje_general',
        'costo_plataforma', 'costo_sim', 'descuento', 'ganancia', 'cobro_adicional'
    ];

    public function serviciosAgendados()
    {
        return $this->hasMany(ServicioAgendado::class);
    }
}
