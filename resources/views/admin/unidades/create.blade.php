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

        
        // let myDropzone = new Dropzone("#dropzoneForm", {
        //     url: "{{ route('unidades.uploads') }}",
        //     headers: {
        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        //     },
        //     autoProcessQueue: false, // Importante: deshabilita el procesamiento automático
        //     uploadMultiple: true,
        //     parallelUploads: 5,
        //     maxFiles: 5,
        //     acceptedFiles: "image/*",
        //     addRemoveLinks: true,
        //     init: function() {
        //         let dz = this;
        //         let input = document.createElement('input');
        //         this.on("complete", function(data){
        //             // Evento que se ejecuta una sola vez cuando todas las subidas terminan
        //             dz.on("queuecomplete", function(data) {
        //                 processingFiles = false;
        //                 if (!formSent) {
        //                     form.appendChild(input);
                            
        //                     submitForm(); // Ahora sí enviamos el form principal
        //                 }
                        
        //             });

        //             dz.on("successmultiple", function(files, response) {
        //                 input.type = 'hidden';
        //                 input.name = 'foto_unidad';
        //                 input.value = JSON.stringify(response.file_name);
        //                 form.appendChild(input);
        //                 console.log("Archivos subidos:", response.file_name);
        //             });

        //             dz.on("error", function(file, response) {
        //                 console.error("Error al subir:", response);
        //             });

        //         });
        //     }
        // });

        form.addEventListener('submit', async function(event) {
            event.preventDefault(); // Evita el envío tradicional del formulario
            console.log("Enviando FORM");
            if (formSubmitted || formSent) return; // Evita múltiples envíos
            formSubmitted = true; // Marca como enviado
            
            try {
                submitForm(); // Procesamos el formulario
            } catch (error) {
                console.error('Error:', error);
                formSubmitted = false;
                formSent = false;
                processingFiles = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Ocurrió un error inesperado al procesar el formulario."
                });
            }
        });

        async function submitForm() {
            formSent = true;
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

                const data = await response.json();
                console.log('Response:', data);

                if (response.ok && data.ok) {
                    window.location.href =
                        `${data.redirect}?success=${encodeURIComponent(data.message)}`;
                } else {
                    formSubmitted = false;
                    formSent = false;

                    let errorHtml = data.message || 'Hubo un error al procesar el formulario.';
                    if (data.errors) {
                        errorHtml += '<ul class="text-left mt-2" style="text-align: left;">';
                        for (const field in data.errors) {
                            data.errors[field].forEach(err => {
                                errorHtml += `<li>${err}</li>`;
                            });
                        }
                        errorHtml += '</ul>';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'No se pudo crear la unidad',
                        html: errorHtml
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                formSubmitted = false;
                formSent = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: error.message || 'Hubo un error al enviar el formulario.'
                });
            }
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
        function filterOptions(type) {
            if (typeof window.filterSelects === 'function') {
                window.filterSelects();
            } else {
                const simSelect = document.getElementById('simcontrol_id');
                const deviceSelect = document.getElementById('devices_id');

                const filter = (select, type) => {
                    let firstVisible = null;
                    Array.from(select.options).forEach(option => {
                        const optionType = option.getAttribute('data-type');
                        if (!optionType) { 
                            return; 
                        }

                        if (type === 'Otro') {
                             if (optionType === 'Otro') {
                                 option.hidden = false;
                                 option.disabled = false;
                                 if(!firstVisible) firstVisible = option;
                             } else {
                                 option.hidden = true;
                                 option.disabled = true;
                             }
                        } else {
                            if (optionType === type) {
                                option.hidden = false;
                                option.disabled = false;
                                if(!firstVisible) firstVisible = option;
                            } else {
                                 option.hidden = true;
                                 option.disabled = true;
                            }
                        }
                    });
                    
                    const selected = select.selectedOptions[0];
                    if (selected && selected.hidden && firstVisible && select.value !== "") {
                        select.value = firstVisible.value;
                    } else if(selected && selected.hidden) {
                        select.value = ""; 
                    }
                };
                if(simSelect) filter(simSelect, type);
                if(deviceSelect) filter(deviceSelect, type);
            }
        }
        // Run on load
        if(dispositivo_instalado.value) {
            filterOptions(dispositivo_instalado.value);
        }

        dispositivo_instalado.addEventListener('change', (ev) => {
            let value = ev.target.value;
            filterOptions(value); // Filter on change

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
