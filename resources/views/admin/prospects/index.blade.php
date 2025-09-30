@extends('layouts.app')
@section('title')
    Listado de Prospectos
@endsection
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Registro de Prospectos</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Registro de Prospectos</h4>
                <a href="{{ route('prospects.create') }}" class="btn btn-primary btn-sm">
                    <i data-feather="plus"></i> Nuevo Prospectos
                </a>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTableExample" class="w-100 table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Campaña</th>
                                    <th>Prospecto</th>
                                    <th>Empresa</th>
                                    <th>Potencial</th>
                                    <th>Estatus</th>
                                    <th>Vendedor asignado</th>
                                    <th>Ubicación</th>
                                    <th>Contacto</th>
                                    <th>Observaciones</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($prospects as $prosp)
                                    <tr>
                                        <td>
                                            <span
                                                class="badge bg-success text-white text-capitalize">{{ $prosp->name_company }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-secondary text-white text-capitalize">{{ $prosp->name_prospect }}</span>
                                        </td>
                                        <td>{{ $prosp->company }}</td>
                                        <td>
                                            @php
                                                switch ($prosp->potencial) {
                                                    case 'bajo':
                                                        $badgeClass = 'badge bg-danger';
                                                        break;
                                                    case 'medio':
                                                        $badgeClass = 'badge bg-warning';
                                                        break;
                                                    case 'alto':
                                                        $badgeClass = 'badge bg-success';
                                                        break;
                                                    default:
                                                        $badgeClass = 'badge bg-secondary';
                                                }
                                            @endphp
                                            <span
                                                class="{{ $badgeClass }} text-dark text-uppercase">{{ $prosp->potencial }}</span>
                                        </td>
                                        <td>
                                            @php
                                                /**
                                                 *
                                                 * 0 = rojo (sin atender),
                                                 * 1 = amarillo (en proceso y se puedan visualizar las observaciones del estatus),
                                                 * 2 = verde (proyecto concretado),
                                                 * 3 = morado (competencia o instaladores),
                                                 * 4 = gris (no funcional).
                                                 *
                                                 * */
                                                $statusOptions = [
                                                    0 => ['class' => 'danger', 'text' => 'Sin Atender'],
                                                    1 => ['class' => 'warning text-dark', 'text' => 'En Proceso'],
                                                    2 => ['class' => 'success', 'text' => 'Concretado'],
                                                    3 => ['class' => 'purple', 'text' => 'Competencia/Instaladores'],
                                                    4 => ['class' => 'secondary', 'text' => 'No Funcional'],
                                                ];

                                                switch ($prosp->status) {
                                                    case 0:
                                                        $statusClass = 'danger';
                                                        $statusText = 'Sin Atender';
                                                        break;
                                                    case 1:
                                                        $statusClass = 'warning text-dark';
                                                        $statusText = 'En Proceso';
                                                        break;
                                                    case 2:
                                                        $statusClass = 'success';
                                                        $statusText = 'Concretado';
                                                        break;
                                                    case 3:
                                                        $statusClass = 'purple';
                                                        $statusText = 'Competencia/Instaladores';
                                                        break;
                                                    case 4:
                                                        $statusClass = 'secondary';
                                                        $statusText = 'No Funcional';
                                                        break;
                                                    default:
                                                        $statusClass = 'secondary';
                                                        $statusText = 'Desconocido';
                                                }
                                            @endphp
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-{{ $statusOptions[$prosp->status]['class'] ?? 'secondary' }} dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{ $statusOptions[$prosp->status]['text'] ?? 'Desconocido' }}
                                                </button>
                                                <div class="dropdown-menu">
                                                    @foreach ($statusOptions as $key => $option)
                                                        @if ($key !== $prosp->status)
                                                            <a class="dropdown-item py-2" href="{{ route('prospects.status', ['id' => $prosp->id, 'status' => $key]) }}">
                                                                {{ $option['text'] }}
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{ $prosp->seller ? $prosp->seller->name : 'Sin Asignar' }}
                                                </button>
                                                <div class="dropdown-menu">
                                                    @foreach ($sellers as $sells)
                                                        @if ($sells['id'] !== $prosp->sellers_id)
                                                            <a class="dropdown-item py-2" href="{{ route('prospects.assign', ['id' => $prosp->id, 'seller' => $sells['id']]) }}">
                                                                {{ $sells['name'] }}
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                    <a class="dropdown-item py-2" href="{{ route('prospects.assign', ['id' => $prosp->id, 'seller' => 0]) }}">
                                                        Dejar sin asignar
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $prosp->location }}</td>
                                        <td>{{ $prosp->contacts }}</td>
                                        <td>
                                            <a href="javascript:void(0)" class="btn-sm btn-secondary text-white"
                                                        onclick="alertSwwet('Observaciones', '{{ $prosp->observations ?? 'Sin Obersaciones' }}')">Ver
                                                        observaciones</a>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">Opciones</button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('prospects.edit', $prosp->id) }}">Editar</a>
                                                    <a href="javascript:void(0)" class="dropdown-item"
                                                        onclick="alertSwwet('Observaciones', '{{ $prosp->observations ?? 'Sin Obersaciones' }}')">Ver
                                                        observaciones</a>
                                                    <hr />
                                                    <form action="{{ route('prospects.destroy', $prosp) }}" method="POST"
                                                        style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm dropdown-item"
                                                            type="submit">Eliminar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
@endsection
