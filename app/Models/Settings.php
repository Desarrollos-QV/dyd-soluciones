<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recordatorios',
        'mensaje_personalizado',
        'mensajes_automaticos',
        'dias_tolerancia',
        'TWILIO_SID',
        'TWILIO_AUTH_TOKEN',
        'TWILIO_PHONE'
    ];
}