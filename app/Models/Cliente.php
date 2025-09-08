<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'avatar',
        'direccion',
        'numero_contacto',
        'numero_alterno',
        'tipo_empresa',
        'empresa',
        'identificacion',
        'direccion_empresa',
        'usuario',
        'password'
    ];

    public function serviciosAgendados()
    {
        return $this->hasMany(ServiciosAgendado::class);
    }
}