<table>
    <thead>
        <tr>
            <th colspan="5" style="text-align: center;"><strong>Reporte de Ingresos y Gastos</strong></th>
        </tr>
        <tr>
            <th colspan="5">Fecha Inicio: {{ request()->input('fecha_inicio') }} | Fecha Fin: {{ request()->input('fecha_fin') }}</th>
        </tr>
        <tr>
            <th>Tipo</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Monto</th>
            <th>Descripción</th>
        </tr>
    </thead>
    <tbody>
        @foreach($gastos as $gasto)
        <tr>
            <td>Gasto</td>
            <td>{{ $gasto->fecha }}</td>
            <td>{{ $gasto->hora }}</td>
            <td>${{ number_format($gasto->monto, 2) }}</td>
            <td>{{ $gasto->descripcion }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3">Total Gastos</th>
            <th>${{ number_format($totalGastos, 2) }}</th>
            <th></th>
        </tr>
    </tfoot>
</table>
