@extends('layouts.app')
@section('title', 'Editar Unidad')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
@endsection
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar Unidad</li>
        </ol>
    </nav>

    <form action="{{ route('unidades.update', $unidad) }}" method="POST" class="row" enctype="multipart/form-data"
        id="edit_unity">
        @csrf
        @method('PUT')
        @include('admin.unidades.form')
    </form>
@endsection
@section('js')
    <script src="{{ asset('assets/vendors/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('assets/js/inputmask.js') }}"></script>

    <script>
        // DropZone y envio de Formulario
        const form = document.getElementById('edit_unity');
        // Configura Dropzone
        Dropzone.autoDiscover = false;
        // Variables de control
        let loadingExistingFiles = false;
        let formSubmitted = false;
        let processingFiles = false;
        let formSent = false;
        let id_unidad = "{{ $unidad->id }}";
        let lastImages = [];
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'foto_unidad';

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
                
               

                // Cargar imágenes existentes
                function loadExistingFiles() {
                    loadingExistingFiles = true; // Indicamos que estamos cargando archivos existentes
                    fetch(`{{ url('unidades/fetchUploads/${id_unidad}') }}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.images) {
                                data.images.forEach(function(image) {
                                    // Crear un archivo mock para cada imagen existente
                                    let mockFile = {
                                        name: image.name,
                                        size: image.size,
                                        accepted: true,
                                        status: Dropzone.ADDED
                                    };
                                    
                                    lastImages.push(image.name);
                                    // Con estas:
                                    dz.emit("addedfile", mockFile);
                                    dz.emit("thumbnail", mockFile, image.url);
                                    dz.emit("complete", mockFile);
                                    dz.files.push(mockFile); // Añade el archivo a la lista de archivos
                                    
                                    // Opcional: si quieres que parezca que ya está subido
                                    mockFile.status = Dropzone.ADDED;
                                    
                                    let element = dz.element.querySelector(
                                        `[data-dz-name="${image.name}"]`);
                                    if (element) {
                                        let removeButton = element.parentElement.querySelector(
                                            '.dz-remove');
                                        if (removeButton) {
                                            removeButton.addEventListener('click', function(e) {
                                                e.preventDefault();
                                                deleteExistingFile(image.id);
                                            });
                                        }
                                    }
                                });
                            }

                            
                        });
                }

                // Función para eliminar archivo existente
                function deleteExistingFile(imageId) {
                    fetch(`{{ url('unidades/deleteUploads/${id_unidad}') }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify({
                                id_unidad: id_unidad,
                                pos: imageId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.ok) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Eliminado',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        });
                }

                // Cargar imágenes existentes al inicializar
                loadExistingFiles();

                // Eventos de depuración
                this.on("addedfile", function(file) {
                    console.log("Archivo añadido:", file.name);
                });

                this.on('removedfile', function(file) {
                    console.log("Archivo eliminado:", file.name);
                    if(lastImages.length > 0){
                        // Si el archivo eliminado está en lastImages, lo removemos de ahí también
                        const index = lastImages.indexOf(file.name);
                        console.log("Index a eliminar: ", {
                                id_unidad: id_unidad,
                                name: file.name
                            });
                        $.ajax({
                            url: `{{ url('unidades/deleteUploads/${id_unidad}') }}`,
                            data: {
                                id_unidad: id_unidad,
                                name: file.name
                            },
                            success: function(data) {
                                console.log(data);

                                if (data.ok) {
                                    if (index > -1) {
                                        lastImages.splice(index, 1);
                                    }
                                }   
                            }
                        });
                    }
                });

                dz.on("sending", function(file) {
                    console.log("Enviando archivo:", file.name);
                });

                this.on("processing", function(file) {
                    console.log("Procesando archivo:", file.name);
                });

                this.on("uploadprogress", function(file, progress) {
                    console.log("Progreso de subida:", file.name, progress + "%");
                });

                this.on("success", function(file) {
                    console.log("Archivo subido con éxito:", file.name);
                });


                this.on("successmultiple", function(files, response) {
                    input.value = JSON.stringify(response.file_name);
                    if(lastImages.length > 0){
                        console.log("Combinando lastImages con nuevos archivos subidos.", lastImages);
                        let allFiles = lastImages.concat(response.file_name);
                        input.value = JSON.stringify(allFiles);
                    }
                    form.appendChild(input);
                    submitForm(); // Ahora sí enviamos el form principal
                });

                
                // Evento que se ejecuta una sola vez cuando todas las subidas terminan
                dz.on("queuecomplete", function(data) {
                    if (!loadingExistingFiles) {
                        processingFiles = false;
                        console.log("Archivos procesados, enviando formulario...", loadingExistingFiles);
                        if (!formSent) {
                            form.appendChild(input);
                            submitForm(); // Ahora sí enviamos el form principal
                        }
                    }

                });

                this.on("error", function(file, response) {
                    console.error("Error al subir:", response);
                });

            }
        }).on('queuecomplete', function() {
            console.log('Todas las subidas han finalizado.');
        });

        function load_images() {
            $.ajax({
                url: `{{ url('unidades/fetchUploads/${id_unidad}') }}`,
                success: function(data) {
                    $('#dropzoneForm').html(data.html);
                }
            });
        }

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
                    if(lastImages.length > 0){
                        console.log("Combinando lastImages con nuevos archivos subidos.", lastImages);
                        input.value = JSON.stringify(lastImages);
                    }
                    form.appendChild(input);
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
                    if (data.ok) {
                        window.location.href =
                            `${data.redirect}?success=${encodeURIComponent(data.message)}`;
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

        // Dropzone y envio de Formulario

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
