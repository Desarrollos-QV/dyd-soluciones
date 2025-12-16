@extends('layouts.app')
@section('title')
    Hoja de Conformidad de Reparación
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-4">Hoja de Conformidad de Reparación</h4>
                </div>
                <div class="card-body">
                    <div class="container">
                        <form action="{{ route('firmar.conformidad.guardar') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="service_id" value="{{ $service->id }}">
                            <input type="hidden" name="location" id="location" value="">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label>Fecha de instalación</label>
                                    <input type="date" name="fecha_instalacion" class="form-control" required
                                        value="{{ $service->cliente->fecha_instalacion }}">
                                </div>
                                <div class="col-md-4">
                                    <label>Tipo de servicio</label>
                                    <input type="text" name="tipo_servicio" class="form-control" disabled
                                        value="{{ $service->tipo_servicio }}">
                                </div>
                                <div class="col-md-4">
                                    <label>Lugar de instalación</label>
                                    <input type="text" name="location" class="form-control" disabled
                                        value="{{ $service->location }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Nombre del técnico</label>
                                    <input type="text" name="tecnico" class="form-control" disabled
                                        value="{{ ucwords($service->tecnico->name) }}">
                                </div>
                                <div class="col-md-6">
                                    <label>¿Encendido correcto tras cerrar switch?</label>
                                    <select name="equipo_encendido" class="form-control">
                                        <option value="SI">SI</option>
                                        <option value="NO">NO</option>
                                    </select>
                                </div>
                            </div>

                            @php
                                $preguntas = [
                                    'estado_vehiculo' => 'Verificación previa del estado del vehículo',
                                    'cableado_bien' => '¿El cableado está en buen estado y bien colocado?',
                                    'fotografias_respaldo' => '¿Hay fotografías de respaldo? (B. de pánico, relay)',
                                    'camaras_bien' => '¿Las cámaras están bien colocadas y fijas?',
                                    'equipo_discreto' => '¿El equipo está colocado de manera discreta y bien fijado?',
                                    'boton_panico' => '¿Está conforme con la posición del botón de pánico?',
                                    'pruebas_conectividad' => '¿Se realizaron pruebas de conectividad?',
                                    'satisfaccion' => '¿Está usted satisfecho con su servicio?',
                                    'aditamento' => '¿Se realizó la instalación de algún aditamento especial?',
                                    'tablero_armado' => '¿Tablero bien armado?',
                                    'manipulaciones_aceptadas' =>
                                        '¿Acepta que se le realicen manipulaciones al equipo?',
                                ];
                            @endphp

                            @foreach ($preguntas as $name => $label)
                                <div class="form-group mb-2">
                                    <label>{{ $label }}</label>
                                    <select name="{{ $name }}" class="form-control" required>
                                        <option value="">Selecciona</option>
                                        <option value="SI">SI</option>
                                        <option value="NO">NO</option>
                                    </select>
                                </div>
                            @endforeach

                            <div class="form-group mb-3">
                                <label for="acuerdo">Acuerdo jurídico</label>
                                <textarea class="form-control" name="acuerdo" rows="3"
                                    readonly>SE INFORMA QUE POR INSTRUCCIONES DE C5 (CENTRO DE CONTROL, COMANDO, COMUNICACIÓN, CÓMPUTO Y DE LA MISMA MANERA SE INFORMA QUE DAÑOS QUE SEAN PROVOCADOS POR EL MAL USO DESPUÉS DE HABER RECIBIDO EL SERVICIO NO SERÁN RESPONSABILIDAD DE LA EMPRESA.</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <textarea class="form-control" name="acuerdo" rows="10"
                                    readonly>SE INFORMA QUE POR INSTRUCCIONES DE C5(CENTRO DE CONTROL, COMANDO, COMUNICACION, COMPUTO Y CALIDAD), NO SE PROPORCIONARAN IMAGENES DE ECHOS DELICTIVOS, ESTO DEBIDO A QUE LA INFORMACION ES RESERVADA POR LO ESTIPULADO EN LA FEDERAL DE PROTECCION DE DATOS PERSONALES.

            DE LA MISMA MANERA SE INFORMA QUE DAÑOS QUE SEAN PROVOCADOS POR EL MAL USO DESPUES DE HABER RECIBIDO DE CONFORMIDAD SU INSTALACION SERAN CUBIERTOS POR EL USUARIO, ADEMAS DE QUE COMO FUE MENCIONADO CON ANTERIORIDAD PERDERA LA GARANTIA DEL EQUIPO. ACEPTO Y ME DOY POR ENTERADO DE CONFORMIDAD DE LO ANTERIORMENTE EXPUESTO</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label>Nombre del usuario</label>
                                <input type="text" name="nombre_usuario" class="form-control" required
                                    value="{{ ucwords($service->cliente->nombre) }}">
                            </div>

                            <div class="form-group mb-3 mt-4 border p-3">
                                <div class="row mb-3 ml-1">
                                    <h4>Registro Fotografico</h4>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label style="display: flex;align-items: center;">
                                            4 Fotografias de cada lado &nbsp;
                                        </label>
                                        <input type="file" name="images_sides" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label style="display: flex;align-items: center;">
                                            Video de máximo 2 minutos del estado de la unidad antes de desarmar &nbsp;
                                        </label>
                                        <input type="file" name="video_state_unit" accept="video/*" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label style="display: flex;align-items: center;">
                                            Fotografia de lugar donde queda colocado el dispositivo &nbsp;
                                        </label>
                                        <input type="file" name="foto_ubicacion_dispositivo" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label style="display: flex;align-items: center;">
                                            Fotografia de donde toma la corriente &nbsp;
                                        </label>
                                        <input type="file" name="foto_toma_corriente" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label style="display: flex;align-items: center;">
                                            Fotografia de donde queda la tierra &nbsp;
                                        </label>
                                        <input type="file" name="foto_toma_tierra" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label style="display: flex;align-items: center;">
                                            Fotografia de donde coloca el relevador &nbsp;
                                        </label>
                                        <input type="file" name="foto_coloca_relevador" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>Agrega algún comentario extra <small class="text-gray">(Opcional)</small> </label>
                                <textarea class="form-control" name="comentarios" rows="3"
                                    placeholder="Comentarios Extra"></textarea>
                            </div>

                            <label for="firma">Firma digital</label>
                            <div class="signature-pad border mb-3" style="width:100%; height:200px;">
                                <canvas id="signatureCanvas" width="600" height="200"
                                    style="width: 100%; height: 100%"></canvas>
                            </div>
                            <input type="hidden" name="firma" id="firmaInput">

                            <section class="d-flex justify-content-end align-items-end">
                                <button type="button" class="btn btn-secondary" onclick="clearSignature()">
                                    Borrar firma
                                </button>
                            </section>

                            <button type="submit" class="btn btn-primary">Guardar y generar PDF</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js"></script>
    <script>
        let canvas = document.getElementById('signatureCanvas');

        const signaturePad = new SignaturePad(canvas);

        function resizeCanvas() {
            // When zoomed out to less than 100%, for some very strange reason,
            // some browsers report devicePixelRatio as less than 1
            // and only part of the canvas is cleared then.
            const ratio = Math.max(window.devicePixelRatio || 1, 1);

            // This part causes the canvas to be cleared
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);

            // This library does not listen for canvas changes, so after the canvas is automatically
            // cleared by the browser, SignaturePad#isEmpty might still return false, even though the
            // canvas looks empty, because the internal data of this library wasn't cleared. To make sure
            // that the state of this library is consistent with visual state of the canvas, you
            // have to clear it manually.
            //signaturePad.clear();

            // If you want to keep the drawing on resize instead of clearing it you can reset the data.
            signaturePad.fromData(signaturePad.toData());
        }

        // On mobile devices it might make more sense to listen to orientation change,
        // rather than window resize events.
        window.onresize = resizeCanvas;
        resizeCanvas();

        function clearSignature() {
            // Clears the canvas
            signaturePad.clear();
        }


        /**
         * 
         * Obtencion de Ubicacion..
         *  
         **/
        // Obtener ubicación del usuario y guardarla en formato JSON
        document.addEventListener('DOMContentLoaded', function () {
            getLocationAndSave();
        });

        function getLocationAndSave() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        // Éxito: obtuvimos la ubicación
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        // Crear objeto con las coordenadas
                        const locationData = {
                            lat: lat,
                            lng: lng
                        };

                        // Guardar en el input como JSON string
                        const locationInput = document.querySelector('input[name="location"]');
                        if (locationInput) {
                            locationInput.value = JSON.stringify(locationData);
                            console.log('Ubicación guardada:', locationData);
                        } else {
                            console.warn('Input con name="location" no encontrado');
                        }
                    },
                    function (error) {
                        // Error: el usuario rechazó o no está disponible
                        console.error('Error al obtener ubicación:', error.message);

                        // Opcional: mostrar un mensaje al usuario
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                console.warn('El usuario rechazó el permiso de ubicación');
                                break;
                            case error.POSITION_UNAVAILABLE:
                                console.warn('La información de ubicación no está disponible');
                                break;
                            case error.TIMEOUT:
                                console.warn('La solicitud de ubicación tardó demasiado');
                                break;
                        }
                    }, {
                    enableHighAccuracy: false, // No necesitamos alta precisión
                    timeout: 10000, // Esperar max 10 segundos
                    maximumAge: 0 // No usar caché
                }
                );
            } else {
                console.error('Geolocalización no soportada por el navegador');
            }
        }


        document.querySelector('form').addEventListener('submit', function (e) {
            // Validar que todos los campos de Registro Fotográfico estén completos
            const fotosFields = [
                'images_sides',
                'video_state_unit',
                'foto_ubicacion_dispositivo',
                'foto_toma_corriente',
                'foto_toma_tierra',
                'foto_coloca_relevador'
            ];

            let fotosIncompletas = false;
            for (let fieldName of fotosFields) {
                const field = document.querySelector(`input[name="${fieldName}"]`);
                if (!field || !field.value) {
                    fotosIncompletas = true;
                    break;
                }
            }

            if (fotosIncompletas) {
                alert("Por favor completa todos los campos del Registro Fotográfico antes de enviar.");
                e.preventDefault();
                return false;
            }

            // Validar firma digital
            if (signaturePad.isEmpty()) {
                alert("Por favor firma antes de enviar.");
                e.preventDefault();
                return false;
            }

            const firmaBase64 = signaturePad.toDataURL('image/png');
            document.getElementById('firmaInput').value = firmaBase64;

            // Enviar formulario con fetch para manejar la descarga y cierre de ventana
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    // Obtener el nombre del archivo del header Content-Disposition
                    const contentDisposition = response.headers.get('Content-Disposition');
                    let filename = 'conformidad.pdf';
                    if (contentDisposition) {
                        const filenameMatch = contentDisposition.match(/filename[^;=\n]*=(["\']?)([^"\';\n]*)/);
                        if (filenameMatch) {
                            filename = filenameMatch[2];
                        }
                    }

                    // Descargar el PDF
                    return response.blob().then(blob => {
                        const url = window.URL.createObjectURL(blob);
                        const link = document.createElement('a');
                        link.href = url;
                        link.download = filename;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        window.URL.revokeObjectURL(url);

                        // Esperar un segundo y cerrar la ventana
                        setTimeout(() => {
                            window.close();
                        }, 800);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un error al guardar el formulario. Por favor intenta de nuevo.');
                });
        });
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqYcfefGddEiqR-OlfaLMSWP5m2RdMk18&libraries=places">
    </script>
@endsection