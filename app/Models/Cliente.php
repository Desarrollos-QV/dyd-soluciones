<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', // Obligatorio
        'avatar', 
        'sex',
        'direccion',// Obligatorio
        'numero_contacto',// Obligatorio
        'numero_alterno',
        'email',
        'tipo_empresa',
        'empresa',
        'identificacion',
        'identificacion_trasera',
        'direccion_empresa',// Obligatorio
        'comprobante_domicilio',
        'copa_factura',
        'tarjeta_circulacion',
        'copia_consesion',
        'contrato',
        'anexo',
        'usuario',
        'password'
    ];

    public function serviciosAgendados()
    {
        return $this->hasMany(ServiciosAgendado::class);
    }

    public function asignaciones()
    {
        return $this->hasMany(Asignaciones::class, 'cliente_id');
    }

    public function unidades()
    {
        return $this->hasMany(Unidades::class, 'cliente_id');
    }

    static function checkDocuments($clientId)
    {
        $cliente = self::find($clientId);
        if (!$cliente) {
            return false;
        }

        $requiredFields = [
            'nombre',
            'direccion',
            'numero_contacto',
            'email',
            'empresa',
        ];

        foreach ($requiredFields as $field) {
            if (empty($cliente->$field)) {
                return false;
            }
        }

        // Requiere al menos una identificación oficial válida cargada en el servidor
        $hasFront = !empty($cliente->identificacion) && File::exists(public_path($cliente->identificacion));
        $hasBack  = !empty($cliente->identificacion_trasera) && File::exists(public_path($cliente->identificacion_trasera));

        if (!$hasFront && !$hasBack) {
            return false;
        }

        return true;
    }

    // Funcion para Eliminar las imagenes asociadas al cliente
    public static function deleteClientDocuments($clientOrId)
    {
        $cliente = $clientOrId instanceof self ? $clientOrId : self::find($clientOrId);
        if (!$cliente) {
            return;
        }

        $documents = [
            'avatar',
            'identificacion',
            'identificacion_trasera',
            'comprobante_domicilio',
            'copa_factura',
            'tarjeta_circulacion',
            'copia_consesion',
            'contrato',
            'anexo'
        ];

        foreach ($documents as $document) {
            if (!empty($cliente->$document) && File::exists(public_path($cliente->$document))) {
                File::delete(public_path($cliente->$document));
            }
        }
    }
}