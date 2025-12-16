<?php
// app/Models/Collection.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
     protected $fillable = [
        'cliente_id',
        'unidad_id',
        'due_date',
        'amount',
        'status',
        'paid_at',
        'notified_at'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function unidad()
    {
        return $this->belongsTo(Unidades::class);
    }

    public function pagos()
    {
        return $this->hasMany(CollectionPayment::class);
    }
}
