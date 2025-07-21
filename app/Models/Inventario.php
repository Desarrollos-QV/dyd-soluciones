<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;
    protected $table = "inventario";
    protected $fillable = ['nombre_completo', 'direccion', 'telefono', 'telefono_alterno', 'evaluacion_calidad', 'ine_comprobante'];
}
