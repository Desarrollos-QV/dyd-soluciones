<?php
namespace App\Exports;

use App\Models\Gasto;
use App\Models\Cliente;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UnidadesExport implements FromView, WithTitle, ShouldAutoSize
{
    protected $ClienteId;

    public function __construct($ClienteId)
    {
        $this->ClienteId = $ClienteId;
    }
    
    public function title(): string
    {
        $cliente  = Cliente::find($this->ClienteId);
        return 'Unidades de '.$cliente->nombre;
    }

    public function view(): View
    {
        $cliente  = Cliente::with('unidades')->find($this->ClienteId);
        
        return view('admin.unidades.ExportClient', [
            'cliente' => $cliente
        ]);
    }
    
}
