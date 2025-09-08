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
        'observations'
    ];

    public function seller()
    {
        return $this->belongsTo(Sellers::class, 'sellers_id');
    }
}
