<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProspectNote extends Model
{
    use HasFactory;

    protected $fillable = ['prospect_id', 'note'];

    public function prospect()
    {
        return $this->belongsTo(Prospects::class);
    }
}
