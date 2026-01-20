<table>
    <thead>
        <tr>
            <th colspan="4" style="text-align: center;background:#000000;color:#ffffff;"><strong>Datos del Cliente</strong></th>
        </tr>
    </thead>
    <tbody> 
        <tr>
            <th>Nombre</th>
            <th>Contacto</th>
            <th>Alterno</th>
            <th>Dirección</th>
        </tr>
        <tr>
            <td style="text-align: left;">
                <b>{{ ucwords($cliente->nombre) }}</b>
            </td>
            <td style="text-align: left;">
                <b>{{ $cliente->numero_contacto }}</b>
            </td>
            <td style="text-align: left;">
                <b>{{ $cliente->numero_alterno }}</b>
            </td>
            <td style="text-align: left;">
                <b>{{ $cliente->direccion }}</b>
            </td>
        </tr>
         
        <tr>
            <th>Empresa</th>
            <th>Tipo Empresa</th> 
        </tr>
        <tr> 
            <td style="text-align: left;">
            <b>{{ $cliente->empresa }}</b>
            </td>
            <td style="text-align: left;">
                <b>{{ $cliente->tipo_empresa }}</b>
            </td>
        </tr>
    </tbody>
</table>

<br>

<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th colspan="14" style="text-align: center;background:#000000;color:#ffffff;"><strong>Unidades Asignadas</strong></th>
        </tr>
        <tr>
            <th>Económico</th>
            <th>Placa</th>
            <th>Tipo Unidad</th>
            <th>Fecha Instalación</th>
            <th>Dispositivo Instalado</th>
            <th>Año Unidad</th>
            <th>Marca</th>
            <th>Submarca</th>
            <th>N° Motor</th>
            <th>VIN</th>
            <th>IMEI</th>
            <th>Apagado</th>
            <th>Fotos</th>
            <th>N° Emergencia</th>
            <th>Observaciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cliente->unidades as $unidad)
        <tr>
            <td>{{ $unidad->economico }}</td>
            <td>{{ $unidad->placa }}</td>
            <td>{{ $unidad->tipo_unidad }}</td>
            <td>{{ $unidad->fecha_instalacion }}</td>
            <td>{{ $unidad->dispositivo_instalado }}</td>
            <td>{{ $unidad->anio_unidad }}</td>
            <td>{{ $unidad->marca }}</td>
            <td>{{ $unidad->submarca }}</td>
            <td>{{ $unidad->numero_de_motor }}</td>
            <td>{{ $unidad->vin }}</td>
            <td>{{ $unidad->imei }}</td>
            <td>{{ $unidad->cuenta_con_apagado ? 'Sí' : 'No' }}</td>
            <td>
                @if($unidad->foto_unidad)
                    @php
                        $unitPics = json_decode($unidad->foto_unidad);
                        foreach ($unitPics as $pic) {
                            if (file_exists(public_path('uploads/fotos_unidades/'.$pic))) {
                                echo '<img src="'.public_path('uploads/fotos_unidades/'.$pic).'" alt="Foto Unidad" width="50" height="50" style="object-fit: cover; margin-right: 5px;">';
                            }
                        }
                    @endphp
                @else
                    No disponible
                @endif
            </td>
            <td>{{ $unidad->numero_de_emergencia }}</td>
            <td>{{ $unidad->observaciones }}</td>
        </tr>
        @endforeach
    </tbody>
</table>