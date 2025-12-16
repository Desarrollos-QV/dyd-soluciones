@extends('layouts.app')

@section('title', 'Registro Fotográfico Completo')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="feather icon-camera"></i> Registro Fotográfico Completo
                    </h3>
                    <a href="{{ route('assignements.performed') }}" class="btn btn-light btn-sm">
                        <i class="feather icon-arrow-left"></i> Volver
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Cliente:</strong> {{ $service->cliente->nombre ?? 'N/A' }}</p>
                            <p><strong>Técnico:</strong> {{ $service->tecnico->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p><strong>Fecha de Servicio:</strong> {{ $service->fecha ?? 'N/A' }}</p>
                            <p><strong>Tipo de Servicio:</strong> {{ $service->tipo_servicio ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fotos y Video -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="feather icon-image"></i> Multimedia</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $fileLabels = [
                                '4_fotos_lados' => '4 Fotos de Lados',
                                'video_estado_unidad' => 'Video Estado de Unidad',
                                'foto_ubicacion_dispositivo' => 'Foto Ubicación del Dispositivo',
                                'foto_toma_corriente' => 'Foto Toma de Corriente',
                                'foto_toma_tierra' => 'Foto Toma de Tierra',
                                'foto_coloca_relevador' => 'Foto Colocación de Relevador'
                            ];
                        @endphp

                        @forelse($registroFotografico as $key => $filename)
                            @if($filename)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">{{ $fileLabels[$key] ?? ucwords(str_replace('_', ' ', $key)) }}</h6>
                                        </div>
                                        <div class="card-body p-0" style="min-height: 200px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                            @if(strpos($key, 'video') !== false)
                                                <!-- Video -->
                                                <video class="w-50 h-50" style="object-fit: contain;" controls>
                                                    <source src="{{ asset('uploads/servicios/registro_fotografico/' . $filename) }}" type="video/mp4">
                                                    Tu navegador no soporta la etiqueta de video.
                                                </video>
                                            @else
                                                <!-- Foto -->
                                                <img src="{{ asset('uploads/servicios/registro_fotografico/' . $filename) }}" 
                                                     alt="{{ $fileLabels[$key] ?? $key }}"
                                                     class="img-fluid"
                                                     style="max-width: 100%; max-height: 250px; object-fit: contain;"
                                                     data-bs-toggle="modal" 
                                                     data-bs-target="#imageModal"
                                                     onclick="showImageModal(this)">
                                            @endif
                                        </div>
                                        <div class="card-footer bg-light">
                                            <small class="text-muted d-block text-truncate" title="{{ $filename }}">
                                                📁 {{ $filename }}
                                            </small>
                                            <a href="{{ asset('uploads/servicios/registro_fotografico/' . $filename) }}" 
                                               download class="btn btn-sm btn-primary mt-2 w-100">
                                                <i class="feather icon-download"></i> Descargar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <i class="feather icon-alert-circle"></i> No hay archivos multimedia disponibles.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ubicación Geográfica -->
    @if($service->getFirma && ($service->getFirma->lat || $service->getFirma->lng))
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="feather icon-map-pin"></i> Ubicación Geográfica</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <p><strong>Latitud:</strong> {{ $service->getFirma->lat ?? 'N/A' }}</p>
                            <p><strong>Longitud:</strong> {{ $service->getFirma->lng ?? 'N/A' }}</p>
                            
                            @if($service->getFirma->lat && $service->getFirma->lng)
                                <a href="https://maps.google.com/?q={{ $service->getFirma->lat }},{{ $service->getFirma->lng }}" 
                                   target="_blank" class="btn btn-sm btn-info">
                                    <i class="feather icon-map"></i> Ver en Google Maps
                                </a>
                            @endif
                        </div>
                        <div class="col-md-10">
                            @if($service->getFirma->lat && $service->getFirma->lng)
                                <div id="map" style="height: 500px; border-radius: 5px;"></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Preguntas y Respuestas -->
    @if($service->getFirma && $service->getFirma->questions)
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="feather icon-help-circle"></i> Preguntas y Respuestas</h5>
                </div>
                <div class="card-body">
                    @php
                        $questions = json_decode($service->getFirma->questions, true);
                    @endphp

                    @if(is_array($questions) && count($questions) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="70%">Pregunta</th>
                                        <th width="30%" class="text-center">Respuesta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($questions as $key => $value)
                                        <tr>
                                            <td>{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                            <td class="text-center">
                                                @if($value == 'yes')
                                                    <span class="badge bg-success"><i class="feather icon-check"></i> Sí</span>
                                                @elseif($value == 'no')
                                                    <span class="badge bg-danger"><i class="feather icon-x"></i> No</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $value }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="feather icon-info"></i> No hay preguntas registradas.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Firma Digital -->
    @if($service->getFirma && $service->getFirma->firma)
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="feather icon-pen-tool"></i> Firma Digital</h5>
                </div>
                <div class="card-body text-center">
                    <div style="padding: 20px; background: #f8f9fa; border-radius: 5px;">
                        <img src="{{ asset($service->getFirma->firma) }}" 
                             alt="Firma Digital"
                             class="img-fluid"
                             style="max-width: 650px; border: 1px solid #ddd; border-radius: 3px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Comentarios -->
    @if($service->getFirma && $service->getFirma->comentarios)
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="feather icon-message-square"></i> Comentarios</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $service->getFirma->comentarios }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Información de Fecha -->
    @if($service->getFirma)
    <div class="row">
        <div class="col-md-12">
            <div class="card bg-light">
                <div class="card-body">
                    <p class="mb-0">
                        <small class="text-muted">
                            Registrado el: <strong>{{ $service->getFirma->created_at->format('d/m/Y H:i:s') }}</strong>
                            @if($service->getFirma->updated_at != $service->getFirma->created_at)
                                | Actualizado el: <strong>{{ $service->getFirma->updated_at->format('d/m/Y H:i:s') }}</strong>
                            @endif
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal para ver imágenes en grande -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Vista Ampliada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Imagen ampliada" class="img-fluid">
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    // Función para mostrar imagen en modal
    function showImageModal(img) {
        const modalImage = document.getElementById('modalImage');
        modalImage.src = img.src;
    }

    // Mostrar mapa si hay coordenadas
    @if($service->getFirma && $service->getFirma->lat && $service->getFirma->lng)
        document.addEventListener('DOMContentLoaded', function() {
            const lat = parseFloat('{{ $service->getFirma->lat }}');
            const lng = parseFloat('{{ $service->getFirma->lng }}');
            
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: { lat: lat, lng: lng }
            });

            new google.maps.Marker({
                position: { lat: lat, lng: lng },
                map: map,
                title: 'Ubicación del Servicio'
            });
        });
    @endif
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqYcfefGddEiqR-OlfaLMSWP5m2RdMk18"></script>
@endsection
