@extends('layouts.app')
@section('title', 'Gestor de cobranza y notificaciones')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Gestor de cobranza y notificaciones</li>
        </ol>
    </nav>

    <!-- FORMULARIO DE CONFIGURACIÓN GENERAL -->
    <form action="{{ route('settings.store') }}" method="POST" id="update_collect" class="row">
        @csrf
        <!-- COLUMNA IZQUIERDA: PARÁMETROS DE COBRANZA -->
        <div class="col-lg-7 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title mb-0">
                        <i data-feather="sliders" class="mr-2 text-primary"></i>
                        Parámetros de Cobranza
                    </h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="recordatorios" class="form-label font-weight-bold">Canal activo en Producción</label>
                            <select name="recordatorios" id="recordatorios" class="form-select" required>
                                <option value="sms" @if (($collection->recordatorios ?? $collection->recordatorio ?? '') == 'sms') selected @endif>SMS (Twilio)</option>
                                <option value="whatsapp" @if (($collection->recordatorios ?? $collection->recordatorio ?? '') == 'whatsapp') selected @endif>WhatsApp (Twilio)</option>
                                <option value="email" @if (($collection->recordatorios ?? $collection->recordatorio ?? '') == 'email') selected @endif>Correo Electrónico (SMTP)</option>
                            </select>
                            <small class="text-muted">Canal predeterminado para los recordatorios automáticos.</small>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="mensajes_automaticos" class="form-label font-weight-bold">Modo de Envío</label>
                            <select name="mensajes_automaticos" id="mensajes_automaticos" class="form-select" required>
                                <option value="si" @if ($collection->mensajes_automaticos == 'si') selected @endif>Automático</option>
                                <option value="no" @if ($collection->mensajes_automaticos == 'no') selected @endif>Manual</option>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="dias_tolerancia" class="form-label font-weight-bold">Días Tolerancia</label>
                            <input type="number" name="dias_tolerancia" class="form-control" min="0"
                                value="{{ $collection->dias_tolerancia ?? old('dias_tolerancia', 3) }}" required />
                        </div>
                    </div>

                    <div class="row mt-3" id="custom_message_container">
                        <div class="col-12">
                            <label for="mensaje_personalizado" class="form-label font-weight-bold">Mensaje Predeterminado (SMS / Email)</label>
                            <textarea name="mensaje_personalizado" id="mensaje_personalizado" class="form-control" rows="6"
                                placeholder="Escribe el mensaje que será enviado a los clientes...">{!! $collection->mensaje_personalizado !!}</textarea>
                            <small class="text-muted">Este texto se usará para envíos de SMS y el cuerpo de recordatorios por Correo.</small>
                        </div>
                    </div>

                    <div class="row mt-3" id="whatsapp_template_container" style="display: none;">
                        <div class="col-12">
                            <label class="form-label font-weight-bold">Plantilla de WhatsApp en uso</label>
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center mb-2">
                                    <i data-feather="info" class="mr-2"></i>
                                    <strong>Plantilla Twilio: reminder_pay</strong>
                                </div>
                                <p class="mb-2">Por políticas de Meta/WhatsApp, los mensajes iniciales se envían mediante una plantilla previamente aprobada por Twilio y Facebook.</p>
                                <a href="https://business.facebook.com/latest/whatsapp_manager/template_details/?business_id=1474122813130436&tab=template-details&childRoute=CAPI&id=831040500045195&nav_ref=whatsapp_manager&asset_id=1461526848422681" target="_blank" class="btn btn-sm btn-primary">
                                    <i data-feather="external-link" class="mr-1"></i> Ver plantilla en WhatsApp Manager
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: CREDENCIALES DE PROVEEDORES -->
        <div class="col-lg-5 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title mb-0">
                        <i data-feather="key" class="mr-2 text-primary"></i>
                        Proveedores de Envío
                    </h5>
                </div>

                <div class="card-body">
                    <h6 class="text-muted text-uppercase font-weight-bold mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">
                        <i data-feather="message-circle" class="mr-1"></i> Twilio (SMS & WhatsApp)
                    </h6>
                    <div class="mb-3">
                        <label for="TWILIO_SID" class="form-label font-weight-bold">Account SID</label>
                        <input type="text" name="TWILIO_SID" id="TWILIO_SID" class="form-control"
                            value="{{ $collection->TWILIO_SID }}" placeholder="ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx">
                    </div>
                    <div class="mb-3">
                        <label for="TWILIO_AUTH_TOKEN" class="form-label font-weight-bold">Auth Token</label>
                        <input type="password" name="TWILIO_AUTH_TOKEN" id="TWILIO_AUTH_TOKEN" class="form-control"
                            value="{{ $collection->TWILIO_AUTH_TOKEN }}" placeholder="••••••••••••••••••••••••••••••••">
                    </div>
                    <div class="mb-3">
                        <label for="TWILIO_PHONE" class="form-label font-weight-bold">Twilio Phone Number (WhatsApp / SMS)</label>
                        <input type="tel" name="TWILIO_PHONE" id="TWILIO_PHONE" class="form-control"
                            value="{{ $collection->TWILIO_PHONE }}" placeholder="Ej: +14155238886">
                    </div>

                    <hr />

                    <h6 class="text-muted text-uppercase font-weight-bold mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">
                        <i data-feather="mail" class="mr-1"></i> Servidor de Correo (SMTP / .env)
                    </h6>
                    <div class="p-3 bg-light rounded border">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Servidor:</span>
                            <span class="font-weight-bold text-dark">{{ config('mail.mailers.smtp.host') ?: 'No definido' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Puerto / Cifrado:</span>
                            <span class="font-weight-bold text-dark">{{ config('mail.mailers.smtp.port') }} ({{ strtoupper(config('mail.mailers.smtp.encryption') ?: 'Sin cifrado') }})</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Cuenta Remitente:</span>
                            <span class="font-weight-bold text-primary">{{ config('mail.from.address') ?: 'No definida' }}</span>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-block w-100" id="btnSaveConfig">
                        <i data-feather="save" class="mr-1"></i> Guardar Configuración
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- CENTRO DE PRUEBAS INDEPENDIENTES -->
    <div class="row">
        <div class="col-12">
            <div class="card border-primary shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white font-weight-bold">
                        <i data-feather="send" class="mr-2"></i>
                        Centro de Pruebas de Notificaciones
                    </h5>
                    <span class="badge badge-light text-primary font-weight-bold">Pruebas en Tiempo Real</span>
                </div>

                <div class="card-body">
                    <p class="text-muted mb-4">
                        Prueba individualmente el envío de cada canal para verificar la conexión de tus proveedores sin alterar la configuración del sistema.
                    </p>

                    <div class="row">
                        <!-- CANAL 1: SMS (TWILIO) -->
                        <div class="col-lg-4 mb-4">
                            <div class="card h-100 border shadow-none" style="background: #fafbff;">
                                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="p-2 rounded-circle bg-primary text-white mr-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                            <i data-feather="message-square" style="width: 18px; height: 18px;"></i>
                                        </div>
                                        <h6 class="mb-0 font-weight-bold text-primary">1. Mensaje SMS</h6>
                                    </div>
                                    <span class="badge badge-outline-primary">Twilio SMS</span>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-3">
                                        <label for="test_phone_sms" class="form-label font-weight-bold">Teléfono de Prueba</label>
                                        <input type="tel" id="test_phone_sms" class="form-control" placeholder="+526361234567">
                                        <small class="text-muted">Con código de país (ej. +52...)</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="test_message_sms" class="form-label font-weight-bold">Mensaje a Enviar</label>
                                        <textarea id="test_message_sms" class="form-control" rows="3" placeholder="Mensaje de prueba...">Prueba de notificación SMS desde DYD Soluciones.</textarea>
                                    </div>
                                    <div class="mt-auto pt-2">
                                        <button type="button" class="btn btn-primary btn-block w-100" id="btnTestSMS">
                                            <i data-feather="send" class="mr-1"></i> Probar Envío SMS
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CANAL 2: WHATSAPP (TWILIO TEMPLATE) -->
                        <div class="col-lg-4 mb-4">
                            <div class="card h-100 border shadow-none" style="background: #f7fbf8;">
                                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="p-2 rounded-circle bg-success text-white mr-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                            <i data-feather="message-circle" style="width: 18px; height: 18px;"></i>
                                        </div>
                                        <h6 class="mb-0 font-weight-bold text-success">2. WhatsApp</h6>
                                    </div>
                                    <span class="badge badge-outline-success">Plantilla Twilio</span>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-3">
                                        <label for="test_phone_wa" class="form-label font-weight-bold">Teléfono de Prueba</label>
                                        <input type="tel" id="test_phone_wa" class="form-control" placeholder="+526361234567">
                                        <small class="text-muted">Con código de país (ej. +52...)</small>
                                    </div>
                                    <div class="alert alert-success py-2 px-3 mb-3" style="font-size: 0.85rem;">
                                        <strong>Plantilla: reminder_pay</strong><br>
                                        Enviará un mensaje con datos demo:
                                        <ul class="mb-0 pl-3 mt-1">
                                            <li>Cliente: Usuario De prueba</li>
                                            <li>Empresa: DYD Soluciones</li>
                                            <li>Monto: $2,000.00 MXN</li>
                                        </ul>
                                    </div>
                                    <div class="mt-auto pt-2">
                                        <button type="button" class="btn btn-success btn-block w-100" id="btnTestWA">
                                            <i data-feather="send" class="mr-1"></i> Probar Envío WhatsApp
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CANAL 3: EMAIL (SMTP GMAIL) -->
                        <div class="col-lg-4 mb-4">
                            <div class="card h-100 border shadow-none" style="background: #fbfbfc;">
                                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="p-2 rounded-circle bg-info text-white mr-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                            <i data-feather="mail" style="width: 18px; height: 18px;"></i>
                                        </div>
                                        <h6 class="mb-0 font-weight-bold text-info">3. Correo Electrónico</h6>
                                    </div>
                                    <span class="badge badge-outline-info">SMTP (.env)</span>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-3">
                                        <label for="test_email_addr" class="form-label font-weight-bold">Correo Destinatario de Prueba</label>
                                        <input type="email" id="test_email_addr" class="form-control" placeholder="destinatario@gmail.com">
                                        <small class="text-muted">Introduce tu correo para verificar la recepción.</small>
                                    </div>
                                    <div class="alert alert-info py-2 px-3 mb-3" style="font-size: 0.85rem;">
                                        <strong>Remitente configurado:</strong><br>
                                        <span class="text-truncate d-block">{{ config('mail.from.address') ?: 'Sin configurar' }}</span>
                                        <small class="text-muted">Servidor: {{ config('mail.mailers.smtp.host') }}</small>
                                    </div>
                                    <div class="mt-auto pt-2">
                                        <button type="button" class="btn btn-info btn-block w-100" id="btnTestEmail">
                                            <i data-feather="send" class="mr-1"></i> Probar Envío Correo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.feather) feather.replace();

            // Sincronización visual de tipo de recordatorio
            const recordatoriosSelect = document.getElementById('recordatorios');
            const customMessageContainer = document.getElementById('custom_message_container');
            const whatsappTemplateContainer = document.getElementById('whatsapp_template_container');

            function toggleMessageView() {
                if (recordatoriosSelect.value === 'whatsapp') {
                    customMessageContainer.style.display = 'none';
                    whatsappTemplateContainer.style.display = '';
                } else {
                    customMessageContainer.style.display = '';
                    whatsappTemplateContainer.style.display = 'none';
                }
            }

            recordatoriosSelect.addEventListener('change', toggleMessageView);
            toggleMessageView();

            // Persistencia local de datos de prueba
            const savedPhone = localStorage.getItem('dyd_test_phone') || '';
            const savedEmail = localStorage.getItem('dyd_test_email') || '';
            if (savedPhone) {
                document.getElementById('test_phone_sms').value = savedPhone;
                document.getElementById('test_phone_wa').value = savedPhone;
            }
            if (savedEmail) {
                document.getElementById('test_email_addr').value = savedEmail;
            }

            // Sincronizar inputs de teléfono entre SMS y WA
            document.getElementById('test_phone_sms').addEventListener('input', function(e) {
                document.getElementById('test_phone_wa').value = e.target.value;
                localStorage.setItem('dyd_test_phone', e.target.value);
            });
            document.getElementById('test_phone_wa').addEventListener('input', function(e) {
                document.getElementById('test_phone_sms').value = e.target.value;
                localStorage.setItem('dyd_test_phone', e.target.value);
            });
            document.getElementById('test_email_addr').addEventListener('input', function(e) {
                localStorage.setItem('dyd_test_email', e.target.value);
            });

            // Enviar Formulario de Configuración General
            const form = document.getElementById('update_collect');
            const btnSave = document.getElementById('btnSaveConfig');
            form.addEventListener('submit', async function(event) {
                event.preventDefault();

                const originalBtnHtml = btnSave.innerHTML;
                btnSave.disabled = true;
                btnSave.innerHTML = `<span class="spinner-border spinner-border-sm mr-1"></span> Guardando...`;

                const formData = new FormData(form);

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Configuración Guardada!',
                            text: data.message || 'Los cambios se han guardado correctamente.'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al guardar',
                            text: data.message || 'Verifica los campos ingresados.'
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error inesperado',
                        text: 'No se pudo conectar con el servidor.'
                    });
                } finally {
                    btnSave.disabled = false;
                    btnSave.innerHTML = originalBtnHtml;
                    if (window.feather) feather.replace();
                }
            });

            // Función genérica para ejecutar pruebas independientes
            async function executeTest(type, payload, buttonElement) {
                const originalHtml = buttonElement.innerHTML;
                buttonElement.disabled = true;
                buttonElement.innerHTML = `<span class="spinner-border spinner-border-sm mr-1"></span> Enviando...`;

                const formData = new FormData();
                formData.append('type', type);
                for (const key in payload) {
                    formData.append(key, payload[key]);
                }

                try {
                    const response = await fetch(`{{ route('sendTestNoify') }}`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Prueba Exitosa!',
                            text: data.message || `El mensaje de prueba se envió correctamente vía ${type.toUpperCase()}.`
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: `Error en prueba ${type.toUpperCase()}`,
                            text: data.message || 'No se pudo completar el envío de prueba.'
                        });
                    }
                } catch (error) {
                    console.error('Error en prueba:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'Ocurrió un error al procesar la petición con el servidor.'
                    });
                } finally {
                    buttonElement.disabled = false;
                    buttonElement.innerHTML = originalHtml;
                    if (window.feather) feather.replace();
                }
            }

            // Botón 1: Prueba SMS
            document.getElementById('btnTestSMS').addEventListener('click', function() {
                const phone = document.getElementById('test_phone_sms').value.trim();
                const message = document.getElementById('test_message_sms').value.trim();

                if (!phone) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Teléfono requerido',
                        text: 'Ingresa un número de teléfono con lada internacional (ej. +526361234567).'
                    });
                    document.getElementById('test_phone_sms').focus();
                    return;
                }

                executeTest('sms', {
                    phone_test: phone,
                    mensaje_personalizado: message
                }, this);
            });

            // Botón 2: Prueba WhatsApp
            document.getElementById('btnTestWA').addEventListener('click', function() {
                const phone = document.getElementById('test_phone_wa').value.trim();

                if (!phone) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Teléfono requerido',
                        text: 'Ingresa un número de teléfono con lada internacional (ej. +526361234567).'
                    });
                    document.getElementById('test_phone_wa').focus();
                    return;
                }

                executeTest('whatsapp', {
                    phone_test: phone
                }, this);
            });

            // Botón 3: Prueba Email
            document.getElementById('btnTestEmail').addEventListener('click', function() {
                const email = document.getElementById('test_email_addr').value.trim();

                if (!email) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Correo requerido',
                        text: 'Ingresa una dirección de correo electrónico válida para recibir la prueba.'
                    });
                    document.getElementById('test_email_addr').focus();
                    return;
                }

                executeTest('email', {
                    email_test: email
                }, this);
            });
        });
    </script>
@endsection
