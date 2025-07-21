 @extends('layouts.app')
 @section('title')
     Editar servicio agendado
 @endsection
 @section('css')
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" />
 @endsection
 @section('content')
     <nav class="page-breadcrumb">
         <ol class="breadcrumb">
             <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
             <li class="breadcrumb-item active" aria-current="page">Agregar nuevo servicio</li>
         </ol>
     </nav>

     <form action="{{ route('servicios_agendados.update', $servicios_agendado) }}" method="POST" class="row" 
        id="form-services" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        @include('admin.servicios_agendados.form', ['servicio' => $servicios_agendado])
     </form>


     <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
     <script>
        Dropzone.autoDiscover = false;

        const archivosExistentes = [
            // Ejemplo de archivos existentes. Puedes generar esto desde tu backend.
            @foreach ($servicios_agendado->fotos as $archivo)
                {
                    idService: "{{ $servicios_agendado->id }}",
                    name : "{{ $archivo }}",
                    url  : "{{ asset('uploads/servicios/registro_fotografico/'.$archivo) }}"
                },
            @endforeach
        ];


         const myDropzone = new Dropzone("#registro-fotografico-dropzone", {
            url: "{{ route('servicios_agendados.update', $servicios_agendado) }}", // Mismo endpoint que el formulario
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 10,
            maxFiles: 10,
            paramName: "fotos", // Importante: nombre del input para archivos
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
         });

         // Cargar archivos existentes como "mock files"
         myDropzone.on("addedfile", function(file) {
             if (file.url) {
                myDropzone.emit("thumbnail", file, file.url);
                file.previewElement.classList.add('dz-success', 'dz-complete');
             }
         });

         archivosExistentes.forEach(file => {
            myDropzone.emit("addedfile", file);
            myDropzone.emit("thumbnail", file, file.url);
            myDropzone.emit("complete", file);
         });

         // Opcional: manejar la eliminación de archivos existentes
         myDropzone.on("removedfile", function(file) { 
             if (file.url) {
                // Envía una petición AJAX para eliminar el archivo del servidor si es necesario
                fetch("{{ route('servicios_agendados.delete-file') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ filename: file.name, idService: file.idService })
                });
             }
         });

         // Al hacer submit, evita el envío normal y procesa la cola de Dropzone
         document.getElementById('form-services').addEventListener('submit', function(e) {
             e.preventDefault();
             // Si hay archivos, usa Dropzone para enviar todo
             if (myDropzone.getQueuedFiles().length > 0) {
                 myDropzone.processQueue();
             } else {
                // Si no hay archivos, envía el formulario normalmente
                const form = e.target;
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Maneja la respuesta del backend
                    if (data.success) {
                        window.location.href =  `${data.redirect}?success=${encodeURIComponent(data.message)}`
                        // Redirige o actualiza la vista si es necesario
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error en la petición');
                });
             }
         });

         // Adjunta los campos del formulario a la petición de Dropzone
         myDropzone.on('sendingmultiple', function(data, xhr, formData) {
             // Agrega todos los campos del formulario
             let form = document.getElementById('form-services');
             for (let i = 0; i < form.elements.length; i++) {
                 let el = form.elements[i];
                 if (el.name && el.type !== 'file') {
                    formData.append(el.name, el.value);
                 }
             }
         });

         // Maneja la respuesta del backend
         myDropzone.on('successmultiple', function(files, response) {
             // Redirige o muestra mensaje de éxito
            console.log(response);
            window.location.href =  `${response.redirect}?success=${encodeURIComponent(response.message)}` || "{{ route('servicios_agendados.index') }}";
         });

         myDropzone.on('errormultiple', function(files, response) {
             alert('Error al guardar: ' + (response.message || ''));
         });
     </script>
 @endsection

 @section('js')
     <script src="{{ asset('assets/vendors/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
     <script src="{{ asset('assets/vendors/inputmask/jquery.inputmask.min.js') }}"></script>
     <script src="{{ asset('assets/js/inputmask.js') }}"></script>
     <script>
         function addRow() {
             const tbody = document.getElementById('rows-container');
             const tr = document.createElement('tr');

             tr.innerHTML = `
                <td><input type="text" name="refacciones[]" /></td>
                <td><input type="number" name="refacciones_cantidad[]"  value="1" /></td>
                <td>
                    <button type="button" class="btn btn-outline-primary d-flex justify-content-center align-items-center" onclick="removeRow(this)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="cursor:pointer;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        &nbsp;<span class="mt-1">Eliminar</span>
                    </button>
                </td>
            `;
             tbody.appendChild(tr);


             // let html = `<div class="row mb-2">
        //     <div class="col-6"><input name="refacciones[]" class="form-control"/></div>
        //     <div class="col-5"><input name="refacciones_cantidad[]" type="number" value="1" class="form-control"/></div>
        //     <div class="col-1"> <svg xmlns="http://www.w3.org/2000/svg" style="cursor:pointer;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> </div>
        // </div>`;

             // document.getElementById('repuestos-list').insertAdjacentHTML('beforeend', html);

         }

         function removeRow(btn) {
             // Elimina la fila (tr) que contiene el botón presionado
             btn.closest('tr').remove();
         }
     </script>
 @endsection
