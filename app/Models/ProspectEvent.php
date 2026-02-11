<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProspectEvent extends Model
{
    use HasFactory;

    protected $fillable = ['prospect_id', 'title', 'start', 'end', 'description'];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    public function prospect()
    {
        return $this->belongsTo(Prospects::class);
    }
}
