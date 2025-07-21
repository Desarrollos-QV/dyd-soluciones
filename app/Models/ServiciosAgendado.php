<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiciosAgendado extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo','fecha','user_id','titular','contacto','unidad',
        'falla_reportada','reparacion_realizada',
        'refacciones','refacciones_cantidad','fotos','firma_cliente',
        'costo_instalador','gasto_adicional','saldo_favor'
    ];

    protected $casts = [
        'refacciones' => 'array',
        'refacciones_cantidad' => 'array',
        'fotos' => 'array',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }

    public function refacciones()
    {
        return $this->hasMany(RefaccionServicio::class);
    }

    public function firma()
    {
        return $this->hasOne(FirmaServicio::class,'id','firma_cliente');
    }
}
