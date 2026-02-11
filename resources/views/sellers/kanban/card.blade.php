<div class="card mb-3 shadow-sm border" data-id="{{ $prospect->id }}" style="cursor: pointer;" onclick="openProspectModal({{ $prospect->id }})">
    <div class="card-body p-3">
        <h6 class="card-title font-weight-bold mb-1">{{ $prospect->name_company }}</h6>
        <p class="card-subtitle mb-2 text-muted small">{{ $prospect->name_prospect }}</p>
        <p class="card-text mb-2 small">
            <i data-feather="phone" class="icon-sm"></i> {{ $prospect->contacts }}
        </p>
        
        <div class="d-flex justify-content-end align-items-center mt-3">
            @if($prospect->status == 2)
            <button class="btn btn-xs btn-outline-success" onclick="event.stopPropagation(); convertToClient({{ $prospect->id }})" title="Convertir a Cliente">
                <i data-feather="check-circle" class="icon-sm"></i>
            </button>
            @endif
        </div>

        @if($prospect->notes->count() > 0)
        <div class="mt-2 border-top pt-2">
            <p class="small font-weight-bold mb-1">Última nota:</p>
            <p class="small text-muted mb-0 text-truncate">{{ $prospect->notes->first()->note }}</p>
        </div>
        @endif

        @if($prospect->events->where('start', '>=', now())->count() > 0)
        <div class="mt-2 border-top pt-2 bg-light rounded p-1">
            <p class="small font-weight-bold mb-1 text-warning">Próximo Evento:</p>
            <p class="small text-muted mb-0">
                {{ $prospect->events->where('start', '>=', now())->first()->start->format('d/m H:i') }} - 
                {{ $prospect->events->where('start', '>=', now())->first()->title }}
            </p>
        </div>
        @endif
    </div>
</div>
