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
        'direccion',// Obligatorio
        'numero_contacto',// Obligatorio
        'numero_alterno',
        'tipo_empresa',
        'empresa',
        'identificacion',
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

    public function unidades()
    {
        return $this->hasMany(Unidades::class, 'cliente_id');
    }

    static function checkDocuments($clientId)
    {
        $cliente = self::find($clientId);

        $documents = [
            'identificacion',
            'comprobante_domicilio',
            'copa_factura',
            'tarjeta_circulacion',
            'copia_consesion',
            'contrato',
            'anexo'
        ];

        foreach ($documents as $document) {
            if (empty($cliente->$document) || !File::exists(public_path($cliente->$document))) {
                return false;
            }
        }

        return true;
    }

    // Funcion para Eliminar las imagenes asociadas al cliente
    public static function deleteClientDocuments($clientId)
    {
        $cliente = self::find($clientId);

        $documents = [
            'avatar',
            'identificacion',
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