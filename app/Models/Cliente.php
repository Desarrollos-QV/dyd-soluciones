<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
        'numero_contacto',
        'numero_alterno',
        'pertenece_ruta', 
        'mensaje_personalizado',
    ];

    public function serviciosAgendados()
    {
        return $this->hasMany(ServiciosAgendado::class);
    }
}