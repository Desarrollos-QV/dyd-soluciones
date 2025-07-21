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
                        <form action="{{ route('firmar.conformidad.guardar') }}" method="POST">
                            @csrf
                            <input type="hidden" name="service_id" value="{{ $service->id }}">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label>Fecha de instalación</label>
                                    <input type="date" name="fecha_instalacion" class="form-control" required value="{{$service->fecha}}">
                                </div>
                                <div class="col-md-4">
                                    <label>Tipo de servicio</label>
                                    <select name="tipo_servicio" class="form-control" required>
                                        <option value="Instalación" @if($service->tipo_servicio == 'Instalación') selected @endif>Instalación</option>
                                        <option value="Reparación" @if($service->tipo_servicio == 'Reparación') selected @endif>Reparación</option>
                                        <option value="Mantenimiento" @if($service->tipo_servicio == 'Mantenimiento') selected @endif>Mantenimiento</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Lugar de instalación</label>
                                    <input type="text" name="lugar_instalacion" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Nombre del técnico</label>
                                    <input type="text" name="tecnico" class="form-control" required value="{{ $service->tecnico->name }}">
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
                                    'manipulaciones_aceptadas' => '¿Acepta que se le realicen manipulaciones al equipo?',
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
                                <textarea class="form-control" name="acuerdo" rows="3" readonly>
                                    SE INFORMA QUE POR INSTRUCCIONES DE C5 (CENTRO DE CONTROL, COMANDO, COMUNICACIÓN, 
                                    CÓMPUTO Y DE LA MISMA MANERA SE INFORMA QUE DAÑOS QUE SEAN PROVOCADOS POR EL MAL USO 
                                    DESPUÉS DE HABER RECIBIDO EL SERVICIO NO SERÁN RESPONSABILIDAD DE LA EMPRESA.
                                </textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label>Nombre del usuario</label>
                                <input type="text" name="nombre_usuario" class="form-control" required value="{{ $service->titular }}">
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

        document.querySelector('form').addEventListener('submit', function(e) {
            if (signaturePad.isEmpty()) {
                alert("Por favor firma antes de enviar.");
                e.preventDefault();
                return false;
            }

            const firmaBase64 = signaturePad.toDataURL('image/png');
            document.getElementById('firmaInput').value = firmaBase64;
        });
    </script>
@endsection
