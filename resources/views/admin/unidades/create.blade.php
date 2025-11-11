@extends('layouts.app')
@section('title', 'Crear nueva Unidad')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
@endsection
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Agregar Nueva Unidad</li>
        </ol>
    </nav>

    <form action="{{ route('unidades.store') }}" method="POST" id="crate_unity" class="row " enctype="multipart/form-data">
        @csrf
        @include('admin.unidades.form')
    </form>

@endsection

@section('js')
<script src="{{ asset('assets/vendors/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script src="{{ asset('assets/vendors/inputmask/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('assets/js/inputmask.js') }}"></script>

<script type="text/javascript">
        const form = document.getElementById('crate_unity');
        // Configura Dropzone
        Dropzone.autoDiscover = false;
        // Variables de control
        let formSubmitted = false;
        let processingFiles = false;
        let formSent = false;

        
        let myDropzone = new Dropzone("#dropzoneForm", {
            url: "{{ route('unidades.uploads') }}",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            autoProcessQueue: false, // Importante: deshabilita el procesamiento automático
            uploadMultiple: true,
            parallelUploads: 5,
            maxFiles: 5,
            acceptedFiles: "image/*",
            addRemoveLinks: true,
            init: function() {
                let dz = this;
                let input = document.createElement('input');
                this.on("complete", function(data){
                    // Evento que se ejecuta una sola vez cuando todas las subidas terminan
                    dz.on("queuecomplete", function(data) {
                        processingFiles = false;
                        if (!formSent) {
                            form.appendChild(input);
                            
                            submitForm(); // Ahora sí enviamos el form principal
                        }
                        
                    });

                    dz.on("successmultiple", function(files, response) {
                        input.type = 'hidden';
                        input.name = 'foto_unidad';
                        input.value = JSON.stringify(response.file_name);
                        form.appendChild(input);
                        console.log("Archivos subidos:", response.file_name);
                    });

                    dz.on("error", function(file, response) {
                        console.error("Error al subir:", response);
                    });

                });
            }
        });

        form.addEventListener('submit', async function(event) {
            event.preventDefault(); // Evita el envío tradicional del formulario
            if (formSubmitted || formSent) return; // Evita múltiples envíos
            formSubmitted = true; // Marca como enviado

            try {
                // Si hay archivos en Dropzone, procesa la cola
                if (myDropzone.getQueuedFiles().length > 0) { 
                    processingFiles = true;
                    myDropzone.processQueue();
                } else {
                    // Si no hay archivos, envía el formulario normalmente 
                    submitForm();
                }
            } catch (error) {
                console.error('Error:', error);
                ormSubmitted = false;
                processingFiles = false;
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: "Ocurrió un error inesperado."
                }).then(() => {
                    window.location.reload();
                });
            }
        });


        async function submitForm() {
            if (formSent || (processingFiles && myDropzone.getQueuedFiles().length > 0)) {
                return;
            }

            formSent = true;
            const formData = new FormData(form);

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                if (data.ok) {
                    window.location.href =
                        `${data.redirect}?success=${encodeURIComponent(data.message)}`;
                }else {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops... Hubo un error al enviar el formulario.',
                        text: data.message
                    }).then(() => {
                        window.location.reload();
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // alert('Hubo un error al enviar el formulario.');
                Swal.fire({
                    type: 'error',
                    title: 'Oops... Hubo un error al enviar el formulario.',
                    text: error.message
                }).then(() => {
                    window.location.reload();
                });
            });            
        }


        const dispositivo_instalado = document.getElementById('dispositivo_instalado');
        const other_dispositivo_instalado = document.getElementById('other_dispositivo_instalado');
        const inner_other_disp = document.getElementById('inner_other_disp');
        const NewInput = document.getElementById('input_dispositivo_instalado');
        const Unity = "{{ isset($unidad->id) ? 1 : 0 }}";

        if (Unity == 1) {
            if (dispositivo_instalado.selectedIndex == 0) // No coincide ninguna opcion y no esta seleccionado
            {
                other_dispositivo_instalado.style.display = "block";
                NewInput.setAttribute('name', 'dispositivo_instalado');
                NewInput.focus();
                dispositivo_instalado.removeAttribute('name');
            }
        }


        dispositivo_instalado.addEventListener('change', (ev) => {
            let value = ev.target.value;
            if (value == 'Otro') {
                other_dispositivo_instalado.style.display = "block";
                NewInput.setAttribute('name', 'dispositivo_instalado');
                NewInput.value = "";
                NewInput.focus();
                dispositivo_instalado.removeAttribute('name');
            } else {

                other_dispositivo_instalado.style.display = "none";
                dispositivo_instalado.setAttribute('name', 'dispositivo_instalado');
                NewInput.removeAttribute('name');
            }
        });

</script>
@endsection
