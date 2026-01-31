<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidades extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'economico',
        'placa',
        'tipo_unidad',
        'fecha_instalacion',
        'fecha_cobro',
        'dispositivo_instalado',
        'simcontrol_id',
        'devices_id',
        'anio_unidad',
        'marca',
        'submarca',
        'numero_de_motor',
        'sensor',
        'costo_plataforma',
        'costo_sim',
        'pago_mensual',
        'name_empresa',
        'credenciales',
        'vin',
        'np_sim',
        'cuenta_con_apagado',
        'foto_unidad',
        'numero_de_emergencia',
        'observaciones',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class,'cliente_id');
    }

    public function simcontrol()
    {
        return $this->belongsTo(SimControl::class,'simcontrol_id');
    }

    public function inventario()
    {
        // Agregar un prefijo device as Inventario
        return $this->belongsTo(Devices::class,'devices_id');
    }

    static function checkCompleteness($unidadId) {
        $unidad = self::find($unidadId);
        $requiredFields = [
            'cliente_id',
            'economico',
            'placa',
            'tipo_unidad',
            'fecha_instalacion',
            'fecha_cobro',
            'dispositivo_instalado',
            'simcontrol_id',
            'anio_unidad',
            'marca',
            'submarca',
            'numero_de_motor',
            'costo_plataforma',
            'costo_sim',
            'pago_mensual',
            'vin',
            'imei',
            'cuenta_con_apagado',
            'foto_unidad',
        ];

        foreach ($requiredFields as $field) {
            if (empty($unidad->$field) || $unidad->costo_plataforma == '0.0' || $unidad->costo_sim == '0.0' || $unidad->pago_mensual == '0.0' ) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Generamos una funcion para obtener los dias faltantes a la fecha de cobro
     */
    static function diasFaltantesCobro($fecha_cobro) {
        // Carbon::parse()->format('Y-m-d')
        $fechaCobro = \Carbon\Carbon::parse($fecha_cobro);
        $fechaActual = \Carbon\Carbon::now();
        $days = $fechaActual->diffInDays($fechaCobro, false);
        
        // Debolvemos el bg-info si es mayor a 10 dias, bg-warning si es entre 5 y 10 dias, bg-danger si es menor a 5 dias
        if ($days > 10) {
            return  '<span class="badge bg-success text-white">'.$days.' días Faltantes</span>';
        } elseif ($days <= 10 && $days >= 5) {
            return  '<span class="badge bg-warning text-dark">'.$days.' días Faltantes</span>';
        } elseif($days < 0) {
            return  '<span class="badge bg-danger text-white">'.abs($days).' días de Retraso</span>';
        } else {
            return  '<span class="badge bg-danger text-white">'.$days.' días Faltantes</span>';
        }
    }
}