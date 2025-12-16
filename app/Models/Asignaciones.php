<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignaciones extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'unidad_id',
        'tecnico_id',
        'tipo_servicio',
        'tel_contact',
        'encargado_recibir',
        'location',
        'lat',
        'lng',
        'viaticos',
        'tipo_vehiculo',
        'marca',
        'modelo',
        'devices_id',
        'placa',
        'observaciones',
        'status'
    ];


    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', );
    }

    public function device()
    {
        return $this->belongsTo(Devices::class, 'devices_id', );
    }

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }

    public function unidad()
    {
        return $this->belongsTo(Unidades::class, 'unidad_id');
    }

    public function getFirma()
    {
        return $this->hasOne(FirmaServicio::class, 'servicio_id');
    }

    static function checkCompleteService($clientId)
    {
        $assign = self::find($clientId);

        $inputs = [
            'cliente_id',
            'tecnico_id',
            'tel_contact',
            'tipo_vehiculo',
            'marca',
            'modelo',
            'placa',
        ];

        foreach ($inputs as $input) {
            if (empty($assign->$input) || is_null($assign->$input) || $assign->$input == '') {
                return false;
            }
        }

        return true;
    }
}
