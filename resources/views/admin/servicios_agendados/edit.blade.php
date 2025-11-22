 @extends('layouts.app')
 @section('title')
     Visualizando servicio agendado
 @endsection
 @section('css')
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" />
 @endsection
 @section('content')
     <nav class="page-breadcrumb">
         <ol class="breadcrumb">
             <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
             <li class="breadcrumb-item active" aria-current="page">Visualizando Servicio #{{ $servicios_agendado->id }}</li>
         </ol>
     </nav>
     <form action="{{ route('servicios_agendados.update', $servicios_agendado->id) }}" method="POST" 
         id="form-services" enctype="multipart/form-data">
         @method('PUT')
         @csrf
         @include('admin.servicios_agendados.form', ['servicio' => $servicios_agendado])
     </form>
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


        /** Funciones del mapa */
        function initMap() {
            var markers = [];
            var latitud = parseFloat(document.getElementById('lat').value);
            var longitud = parseFloat(document.getElementById('lng').value);

            var map = new google.maps.Map(
            document.getElementById('map'), {
                center: {
                    lat: latitud,
                    lng: longitud
                },
                zoom: 13
            });

                var geocoder = new google.maps.Geocoder;
                var marker = new google.maps.Marker({
                    map: map,
                    draggable: false,
                    position: {
                        lat: latitud,
                        lng: longitud
                    }
                });

            markers.push(marker);
        }
        /** Funciones del mapa */
     </script>
     <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqYcfefGddEiqR-OlfaLMSWP5m2RdMk18&libraries=places&callback=initMap"></script>
 @endsection
