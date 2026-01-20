<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimControl extends Model
{
    use HasFactory;

    protected $table = 'simcontrol';

    public $fillable = [
        'compañia',
        'numero_sim',
        'numero_publico',
        'observaciones'
    ];
}
