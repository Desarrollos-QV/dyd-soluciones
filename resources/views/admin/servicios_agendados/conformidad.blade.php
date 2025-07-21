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
    
    <div class="seccion"><span class="label">Fecha de Instalación:</span> {{ $firmaService->fecha }}</div>
    <div class="seccion"><span class="label">Técnico:</span> {{ $service->tecnico->name }}</div>
    <div class="seccion"><span class="label">Lugar:</span> {{ json_decode($firmaService->questions, true)['lugar_instalacion'] }}</div>
    <div class="seccion"><span class="label">Tipo de Servicio:</span> {{ $service->tipo_servicio }}</div>
    <hr>
    @foreach(json_decode($firmaService->questions, true) as $label => $valor)
        @if($label != 'lugar_instalacion' || $label != 'equipo_encendido')
            <div class="seccion"><span class="label">{{ ucwords(str_replace("_"," ",$label)) }}:</span> {{ $valor }}</div>
        @endif
    @endforeach

    <hr>

    <div class="firma">
        <p><strong>Nombre y firma del usuario:</strong></p>
        @if($firmaService->firma)
            <img src="{{ public_path($firmaService->firma) }}" style="width: 400px; height: auto;">
        @else
            <p>No hay firma registrada.</p>
        @endif
    </div>
</body>
</html>
