<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha', 
        'hora', 
        'autorizado_por', 
        'monto', 
        'descripcion', 
        'solicitado_por', 
        'motivo',
        'tipo'
    ];

    public function Autoriza()
    {
        return $this->hasOne(User::class, 'id','autorizado_por');
    }

    public function Solicita()
    {
        return $this->hasOne(User::class, 'id','solicitado_por');
    }

}
