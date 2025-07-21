<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirmaServicio extends Model
{
    use HasFactory;

    protected $table = "firmas_servicio";

    protected $fillable = ['servicio_id', 'firma', 'questions'];

    public function servicio()
    {
        return $this->belongsTo(ServiciosAgendado::class);
    }
}
