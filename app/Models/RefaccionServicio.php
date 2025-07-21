<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefaccionServicio extends Model
{
    use HasFactory;

    protected $fillable = ['servicio_id', 'refaccion', 'cantidad'];

    public function servicio()
    {
        return $this->belongsTo(ServicioAgendado::class);
    }
}
