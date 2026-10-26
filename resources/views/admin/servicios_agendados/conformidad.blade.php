<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Hoja de Conformidad</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .seccion { margin-bottom: 10px; }
        .label { font-weight: bold; }
        .firma { margin-top: 40px; }
    </style>
</head>
<body>
    <h2>HOJA DE CONFORMIDAD DE REPARACIÓN</h2>
    
    <div class="seccion"><span class="label">Fecha de Instalación:</span> {{ $firmaService->created_at ?? now() }}</div>
    <div class="seccion"><span class="label">Técnico:</span> {{ $service->tecnico->name ?? 'No asignado' }}</div>
    <div class="seccion"><span class="label">Lugar:</span> {{ $service->location ?? 'N/A' }}</div>
    <div class="seccion"><span class="label">Tipo de Servicio:</span> {{ $service->tipo_servicio ?? 'N/A' }}</div>

    @if(isset($service->unidades) && $service->unidades->count() > 0)
        <div class="seccion"><span class="label">Unidades Intervenidas ({{ $service->unidades->count() }}):</span></div>
        <table style="width:100%; border-collapse: collapse; margin-bottom: 12px; font-size: 11px;" border="1">
            <thead>
                <tr style="background: #f2f2f2;">
                    <th style="padding: 4px; text-align: center;">#</th>
                    <th style="padding: 4px;">Económico</th>
                    <th style="padding: 4px;">Placas</th>
                    <th style="padding: 4px;">Vehículo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($service->unidades as $idx => $u)
                <tr>
                    <td style="padding: 4px; text-align: center;">{{ $idx + 1 }}</td>
                    <td style="padding: 4px;">{{ $u->economico ?? 'S/E' }}</td>
                    <td style="padding: 4px;">{{ $u->placa ?? 'S/P' }}</td>
                    <td style="padding: 4px;">{{ $u->marca ?? '' }} {{ $u->submarca ?? '' }} {{ $u->modelo ?? '' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @elseif(!empty($service->placa) || !empty($service->marca))
        <div class="seccion"><span class="label">Vehículo:</span> {{ $service->marca ?? '' }} {{ $service->modelo ?? '' }} (Placas: {{ $service->placa ?? 'N/A' }})</div>
    @endif
    <hr>
    @if(!empty($firmaService?->questions))
        @foreach(json_decode($firmaService->questions, true) ?: [] as $label => $valor)
            @if($label != 'lugar_instalacion' || $label != 'equipo_encendido')
                <div class="seccion"><span class="label">{{ ucwords(str_replace("_"," ",$label)) }}:</span> {{ $valor }}</div>
            @endif
        @endforeach
    @endif

    <hr>

    <div class="firma">
        <p><strong>Nombre y firma del usuario:</strong></p>
        @if($firmaService && $firmaService->firma && file_exists(public_path($firmaService->firma)))
            <img src="{{ public_path($firmaService->firma) }}" style="width: 400px; height: auto;">
        @else
            <p>No hay firma registrada.</p>
        @endif
    </div>
</body>
</html>
