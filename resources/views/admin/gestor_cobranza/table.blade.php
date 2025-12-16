<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th>Cliente</th>
            <th>Unidad</th>
            <th>Fecha de cobro</th>
            <th>Monto</th>
            <th>Status</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $item)
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="ms-2">
                            <h6 class="mb-0 fw-bold">{{ ucWords($item->cliente->nombre) }}</h6>
                            <small class="text-muted">{{ $item->cliente->email ?? 'Sin correo' }}</small>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge bg-light text-dark border">{{ $item->unidad->economico }}</span>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <i class="feather icon-calendar me-2 text-muted"></i>
                        {{ \Carbon\Carbon::parse($item->due_date)->format('d/m/Y') }}
                    </div>
                </td>
                <td class="fw-bold text-dark">
                    ${{ $item->amount }}
                </td>
                <td>
                    @if($item->status == 'pending')
                        <span class="badge bg-warning text-dark">Pendiente</span>
                    @elseif($item->status == 'paid')
                        <span class="badge bg-success">Pagado</span>
                    @elseif($item->status == 'overdue')
                        <span class="badge bg-danger">Vencido</span>
                    @elseif($item->status == 'notified')
                        <span class="badge bg-info">Notificado</span>
                    @else
                        <span class="badge bg-secondary">{{ $item->status }}</span>
                    @endif
                </td>
                <td>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary btn-sm" data-item="{{ json_encode($item) }}"
                            onclick="abrirModalCliente(this)">
                            <i class="feather icon-eye"></i>
                        </button>

                        @if($item->status != 'paid')
                            <button type="button" class="btn btn-outline-info btn-sm" data-item="{{ json_encode($item) }}"
                                onclick="abrirModalNotificacion(this)">
                                <i class="feather icon-bell"></i>
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm"
                                onclick="checkToPaid('{{ $item->id }}')">
                                <i class="feather icon-check-square"></i>
                            </button>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center py-5">
                    <i class="feather icon-inbox text-muted mb-2" style="font-size: 2rem;"></i>
                    <p class="text-muted mb-0">No se encontraron registros</p>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>