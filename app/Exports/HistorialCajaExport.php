<?php

namespace App\Exports;

use App\Models\HistorialCaja;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HistorialCajaExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $fechaInicio;
    protected $fechaFin;
    protected $tipo;

    public function __construct($fechaInicio = null, $fechaFin = null, $tipo = null)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->tipo = $tipo;
    }

    public function query()
    {
        $query = HistorialCaja::query();

        if ($this->fechaInicio && $this->fechaFin) {
            $query->whereBetween('fecha', [$this->fechaInicio, $this->fechaFin]);
        }

        if ($this->tipo) {
            $query->where('tipo', $this->tipo);
        }

        return $query->orderByDesc('fecha');
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Hora',
            'Tipo',
            'Concepto',
            'Método de Pago',
            'Monto',
            'Descripción',
            'Autorizado por',
            'Referencia',
            'Creado por',
            'Fecha de Creación',
        ];
    }

    public function map($row): array
    {
        return [
            $row->fecha,
            $row->hora,
            ucfirst($row->tipo),
            $row->concepto,
            $row->metodo_pago ?? '-',
            $row->monto,
            $row->descripcion,
            $row->Autoriza ? ucfirst($row->Autoriza->name . ' ' . $row->Autoriza->lastname) : '-',
            $row->referencia,
            $row->usuario ? ucfirst($row->usuario->name . ' ' . $row->usuario->lastname) : '-',
            $row->created_at,
        ];
    }
}
