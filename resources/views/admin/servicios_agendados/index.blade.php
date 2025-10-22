@extends('layouts.app')
@section('title')
    Servicios
@endsection
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Servicios</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between mb-3">
                <h4>Servicios Agendados</h4>
                 
            </div>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">
                    <form method="GET" class="mb-3">
                        <input name="search" value="{{ request('search') }}" placeholder="Buscar titular o técnico"
                            class="form-control" />
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTableExample" class="w-100 table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Técnico</th>
                                    <th>Titular</th>
                                    <th>Contacto</th>
                                    <th>Unidad</th>
                                    <th>Firma</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($servicios as $s)
                                    <tr>
                                        <td>{{ 
                                            \Carbon\Carbon::parse($s->fecha_servicio)->format('d/m/Y')
                                            }}</td>
                                        <td>{{ $s->tipo_servicio }}</td>
                                        <td>{{ ucwords($s->tecnico->name) }}</td>
                                        <td>{{ ucwords($s->cliente->nombre) }}</td>
                                        <td>{{ $s->cliente->numero_contacto.' | '.$s->cliente->numero_alterno }}</td>
                                        <td>{{ $s->device->dispositivo }}</td>
                                        <td>
                                            @if ($s->firma_cliente != null)
                                                <span class="badge badge-success">Firmado</span>
                                            @else
                                                <span class="badge badge-warning">Sin Firmar</span>
                                            @endif
                                        </td>
                                        <td>

                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">Opciones</button>
                                                <div class="dropdown-menu">
                                                    <!-- <a class="dropdown-item"
                                                        href="{{ route('servicios_agendados.edit', $s->id) }}">Editar</a>-->
                                                    @if ($s->firma_cliente != null)
                                                        <a class="dropdown-item" target="_blank"
                                                            href="{{ route('servicios_agendados.generarPDF', ['id' => base64_encode($s->id)]) }}">Descargar
                                                            Reporte</a>
                                                        <a class="dropdown-item" href="javascript:void(0)"
                                                            onclick="ShareLink()">Compartir Link</a>
                                                    @else
                                                        <a class="dropdown-item" target="_blank"
                                                            href="{{ route('servicios_agendados.firmar', ['id' => base64_encode($s->id)]) }}">Solicitar
                                                            Firma</a>
                                                    @endif
                                                    <!-- <a class="dropdown-item" href="#">
                                                        <form action="{{ route('servicios_agendados.destroy', $s) }}"
                                                            method="POST">
                                                            @csrf @method('DELETE')
                                                            <button
                                                                style="background: none;border:none;margin:0 !important;padding;padding: 0 !important;"
                                                                onclick="return confirm('¿Eliminar Servicio?')">Eliminar</button>
                                                        </form>
                                                    </a>-->
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $servicios->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
    <!-- custom js for this page -->
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <!-- end custom js for this page -->
    <script>
        function ShareLink() {
            let route = ""; 

            Swal.fire({
                title: "<strong>Descarga tu <u>Reporte</u></strong>",
                icon: "success",
                html: `
                    Envia este <b>Enlace</b> para que pueda descargar el reporte firmado,<br /><br />
                    <h5>
                        <a href="`+route+`" onclick="copiarAlPortapapeles(event, '`+route+`')" style="cursor:pointer;">
                            Copiar Link
                        </a>
                    </h5>
                `,
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: `<i class="fa fa-thumbs-up"></i> Listo!`,
                confirmButtonAriaLabel: "Thumbs up, great!",
                cancelButtonText: `<i class="fa fa-thumbs-down"></i> Cancelar`,
                cancelButtonAriaLabel: "Thumbs down"
            });
        }

        function copiarAlPortapapeles(e, texto) {
            e.preventDefault();
            navigator.clipboard.writeText(texto).then(function() {
                // Opcional: notificación de éxito
                Swal.close();
                Swal.fire({
                    title: "Copiado!",
                    text: "El link esta en tu portapapeles.",
                    icon: "success",
                    showCloseButton: false,
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 1500,
                });
            }, function() {
                alert('No se pudo copiar el enlace');
            });
        }
    </script>
@endsection
