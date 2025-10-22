<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialCaja extends Model
{
    use HasFactory;

     use HasFactory;

    protected $table = 'historial_caja';

    protected $fillable = [
        'user_id',
        'fecha',
        'hora',
        'tipo',
        'concepto',
        'monto',
        'metodo_pago',
        'descripcion',
        'autorizado_por',
        'referencia',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Autoriza()
    {
        return $this->hasOne(User::class, 'id','autorizado_por');
    }

}
