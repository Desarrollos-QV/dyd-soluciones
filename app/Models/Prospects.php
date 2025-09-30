<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prospects extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_company',
        'name_prospect',
        'company',
        'potencial',
        'sellers_id',
        'location',
        'contacts',
        'observations',
        'status' // 0 = rojo (sin atender), 1 = amarillo (en proceso y se puedan visualizar las observaciones del estatus), 2 = verde (proyecto concretado), 3 = morado (competencia o instaladores), 4 = gris (no funcional).
    ];

    public function seller()
    {
        return $this->belongsTo(Sellers::class, 'sellers_id');
    }
}
