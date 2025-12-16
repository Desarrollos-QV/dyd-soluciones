<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionPayment extends Model
{
    protected $table = 'collections_payments';

    protected $fillable = [
        'collection_id',
        'paid_by',
        'amount'
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }
}