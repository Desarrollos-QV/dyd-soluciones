@extends('layouts.app')
@section('title', 'Gestor de cobranza')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Gestor de cobranza</li>
        </ol>
    </nav>

    <form action="{{ route('collections.store') }}" method="POST" id="update_collect" class="row"
        enctype="multipart/form-data">
        @csrf
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <h5>
                            Gestión de cobranza
                        </h5>
                    </h4>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="recordatorios">Tipo de recordatorios</label>
                                <select name="recordatorios" id="recordatorios" class="form-select" required>
                                    <option value="sms" @if ($collection->recordatorio == 'sms') selected @endif>SMS</option>
                                    <option value="whatsapp" @if ($collection->recordatorio == 'whatsapp') selected @endif>Whatsapp
                                    </option>
                                    <option value="email" @if ($collection->recordatorio == 'email') selected @endif>Email</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="mensajes_automaticos">Tipo de Manejo de mensajeria</label>
                                <select name="mensajes_automaticos" id="mensajes_automaticos" class="form-select" required>
                                    <option value="si" @if ($collection->mensajes_automaticos == 'si') selected @endif>Automaticos
                                    </option>
                                    <option value="no" @if ($collection->mensajes_automaticos == 'no') selected @endif>Manuales
                                    </option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label for="dias_tolerancia">Días de tolerancia</label>
                                <input type="number" name="dias_tolerancia" class="form-control mb-4 mb-md-0"
                                    value="{{ $collection->dias_tolerancia ?? old('dias_tolerancia') }}" required />
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <label for="mensaje_personalizado">Mensaje Automatico a enviar</label>
                                <textarea name="mensaje_personalizado" id="mensaje_personalizado" class="form-control" rows="8"
                                    placeholder="Mensaje que sera enviado a todos los usuarios">{!! $collection->mensaje_personalizado !!}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <h5>
                            Gestor de credenciales para proveedor
                        </h5>
                    </h4>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="TWILIO_SID">Account SID</label>
                                <input type="text" name="TWILIO_SID" id="TWILIO_SID" class="form-control"
                                    value="{{ $collection->TWILIO_SID }}" required>
                            </div>
                            <div class="col-lg-12 mt-4">
                                <label for="TWILIO_AUTH_TOKEN">Auth Token</label>
                                <input type="password" name="TWILIO_AUTH_TOKEN" class="form-control"
                                    value="{{ $collection->TWILIO_AUTH_TOKEN ?? old('TWILIO_AUTH_TOKEN') }}" required>
                            </div>

                            <div class="col-lg-12 mt-4">
                                <label for="TWILIO_PHONE">Twilio phone number</label>
                                <input type="tel" name="TWILIO_PHONE" id="TWILIO_PHONE" class="form-control"
                                    value="{{ $collection->TWILIO_PHONE ?? old('TWILIO_PHONE') }}" required>
                            </div>
                        </div>

                        <hr />
                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <h7>
                                    Pruebas de envios
                                </h7>
                            </div>
                            <div class="col-lg-12 mt-4">
                                <label for="phone_test">Telefono para Test</label>
                                <input type="tel" id="phone_test" class="form-control" placeholder="Ej.= +5216361229546">
                            </div>
                            <div class="col-lg-12 mt-4">
                                <label for="email_test">Email para Test</label>
                                <input type="tel" id="email_test" class="form-control">
                            </div>
                            <div class="col-lg-12 mt-4">
                                <a href="javascript:void(0)" class="btn btn-info" id="sendDemoNotify">
                                    Realizar Prueba
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary mr-4">Actualizar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection

@section('js')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Envio de formulario para actualizacion
            const form = document.getElementById('update_collect');
            form.addEventListener('submit', async function(event) {
                event.preventDefault(); // Evita el envío tradicional del formulario

                const formData = new FormData(form);

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: formData
                    });

                    if (response.ok) {
                        const data = await response.json();
                        // alert('Elemento del inventario creado exitosamente.');
                        // Redirigir o realizar alguna acción adicional 
                        window.location.href =
                            `${data.redirect}?success=${encodeURIComponent(data.message)}`;
                    } else {
                        const errorData = await response.json();
                        console.error('Errores:', errorData);
                        // alert('Hubo un error al enviar el formulario.');
                        Swal.fire({
                            type: 'error',
                            title: 'Oops... Hubo un error al enviar el formulario.',
                            text: errorData.message
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: "Ocurrió un error inesperado."
                    }).then(() => {
                        window.location.reload();
                    });
                }
            });


            // Envio de prueba de testeo de SMS y Whatsapp
            const btnTest = document.getElementById('sendDemoNotify');
            btnTest.addEventListener('click', async function(ev) {
                let recordatoriosInput = document.getElementById('recordatorios').value;
                let phone_testInput = document.getElementById('phone_test').value; 
                let email_testInput = document.getElementById('email_test').value; 
                let route = `{{ url('sendTestNotify') }}/${recordatoriosInput}`;
                route += `?phone_test=${encodeURIComponent(phone_testInput)}&email_test=${encodeURIComponent(email_testInput)}`;

                const response = await fetch(route, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    console.log(data);
                } else {
                    const errorData = await response.json();
                    console.error('Errores:', errorData);
                    // alert('Hubo un error al enviar el formulario.');
                    Swal.fire({
                        type: 'error',
                        title: 'Oops... Hubo un error al enviar el formulario.',
                        text: errorData.message
                    }).then(() => {
                        window.location.reload();
                    });
                }
            });
        });
    </script>
@endsection