<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceImei extends Model
{
    use HasFactory;

    protected $fillable = ['device_id', 'imei'];

    public function device()
    {
        return $this->belongsTo(Devices::class, 'device_id');
    }
}
