@extends('layouts.app')

@section('title', 'Mangueras')
@section('header', 'Mangueras')

@section('actions')
@if(canManageInfrastructure())
<a href="{{ route('mangueras.create') }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-circle"></i> Nueva Manguera
</a>
@endif
<div class="btn-group">
    <a href="{{ route('mangueras.exportar', ['tipo' => 'excel']) }}" class="btn btn-sm btn-success" title="Exportar a Excel">
        <i class="bi bi-file-excel"></i> Excel
    </a>
    <a href="{{ route('mangueras.exportar', ['tipo' => 'pdf']) }}" class="btn btn-sm btn-danger" title="Exportar a PDF">
        <i class="bi bi-file-pdf"></i> PDF
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Filtros -->
        <form method="GET" action="{{ route('mangueras.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="dispensario_id" class="form-label">Dispensario</label>
                <select class="form-select select2" id="dispensario_id" name="dispensario_id">
                    <option value="">Todos</option>
                    @foreach($dispensarios ?? [] as $dispensario)
                        <option value="{{ $dispensario['id'] }}" {{ request('dispensario_id') == $dispensario['id'] ? 'selected' : '' }}>
                            {{ $dispensario['clave'] }} - {{ $dispensario['instalacion']['nombre'] ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="medidor_id" class="form-label">Medidor</label>
                <select class="form-select select2" id="medidor_id" name="medidor_id">
                    <option value="">Todos</option>
                    @foreach($medidores ?? [] as $medidor)
                        <option value="{{ $medidor['id'] }}" {{ request('medidor_id') == $medidor['id'] ? 'selected' : '' }}>
                            {{ $medidor['clave'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="clave" class="form-label">Clave</label>
                <input type="text" class="form-control" id="clave" name="clave" 
                       value="{{ request('clave') }}" placeholder="Buscar por clave">
            </div>
            
            <div class="col-md-2">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="">Todos</option>
                    <option value="OPERATIVO" {{ request('estado') == 'OPERATIVO' ? 'selected' : '' }}>Operativo</option>
                    <option value="MANTENIMIENTO" {{ request('estado') == 'MANTENIMIENTO' ? 'selected' : '' }}>Mantenimiento</option>
                    <option value="FUERA_SERVICIO" {{ request('estado') == 'FUERA_SERVICIO' ? 'selected' : '' }}>Fuera de Servicio</option>
                </select>
            </div>
            
            <div class="col-md-1">
                <label for="activo" class="form-label">Activo</label>
                <select class="form-select" id="activo" name="activo">
                    <option value="">Todos</option>
                    <option value="1" {{ request('activo') == '1' ? 'selected' : '' }}>Sí</option>
                    <option value="0" {{ request('activo') == '0' ? 'selected' : '' }}>No</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="per_page" class="form-label">Registros por página</label>
                <select class="form-select" id="per_page" name="per_page">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
            
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filtrar
                </button>
                <a href="{{ route('mangueras.index') }}" class="btn btn-secondary">
                    <i class="bi bi-eraser"></i> Limpiar
                </a>
            </div>
        </form>
        
        <!-- Tabla de mangueras -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Descripción</th>
                        <th>Dispensario</th>
                        <th>Medidor Asignado</th>
                        <th>Estado</th>
                        <th>Activo</th>
                        <th>Última Calibración</th>
                        <th>Próxima Calibración</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mangueras as $manguera)
                        <tr>
                            <td>{{ $manguera['clave'] }}</td>
                            <td>{{ $manguera['descripcion'] ?? '-' }}</td>
                            <td>
                                @if(isset($manguera['dispensario']))
                                    {{ $manguera['dispensario']['clave'] }}<br>
                                    <small class="text-muted">{{ $manguera['dispensario']['instalacion']['nombre'] ?? '' }}</small>
                                @else
                                    {{ $manguera['dispensario_id'] }}
                                @endif
                            </td>
                            <td>
                                @if(isset($manguera['medidor']))
                                    <strong>{{ $manguera['medidor']['clave'] }}</strong><br>
                                    <small class="text-muted">{{ $manguera['medidor']['numero_serie'] }}</small>
                                    <span class="badge bg-{{ $manguera['medidor']['estado'] == 'OPERATIVO' ? 'success' : 'warning' }}">
                                        {{ $manguera['medidor']['estado'] }}
                                    </span>
                                @else
                                    <span class="text-muted">No asignado</span>
                                    <a href="{{ route('mangueras.edit', $manguera['id']) }}" class="btn btn-sm btn-primary mt-1">
                                        Asignar
                                    </a>
                                @endif
                            </td>
                            <td>
                                @php
                                    $estadoClass = [
                                        'OPERATIVO' => 'success',
                                        'MANTENIMIENTO' => 'warning',
                                        'FUERA_SERVICIO' => 'danger'
                                    ][$manguera['estado']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $estadoClass }}">{{ $manguera['estado'] }}</span>
                            </td>
                            <td>
                                @if($manguera['activo'])
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>{{ $manguera['fecha_ultima_calibracion'] ?? 'No registrada' }}</td>
                            <td>
                                @if(isset($manguera['fecha_proxima_calibracion']))
                                    @php
                                        $dias = now()->diffInDays($manguera['fecha_proxima_calibracion'], false);
                                        $badgeClass = $dias < 7 ? 'danger' : ($dias < 15 ? 'warning' : 'success');
                                    @endphp
                                    {{ $manguera['fecha_proxima_calibracion'] }}
                                    <span class="badge bg-{{ $badgeClass }}">{{ round($dias) }} días</span>
                                @else
                                    No programada
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('mangueras.show', $manguera['id']) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('mangueras.edit', $manguera['id']) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if(isset($manguera['medidor']))
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="confirmarQuitarMedidor({{ $manguera['id'] }})" title="Quitar Medidor">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No hay mangueras registradas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        @if(isset($meta))
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Mostrando {{ $meta['from'] ?? 0 }} - {{ $meta['to'] ?? 0 }} de {{ $meta['total'] ?? 0 }} registros
            </div>
            <nav>
                <ul class="pagination">
                    @if(isset($links['prev']))
                        <li class="page-item">
                            <a class="page-link" href="{{ $links['prev'] }}" aria-label="Anterior">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    @endif
                    
                    @for($i = 1; $i <= ($meta['last_page'] ?? 1); $i++)
                        <li class="page-item {{ $i == ($meta['current_page'] ?? 1) ? 'active' : '' }}">
                            <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    
                    @if(isset($links['next']))
                        <li class="page-item">
                            <a class="page-link" href="{{ $links['next'] }}" aria-label="Siguiente">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
        @endif
    </div>
</div>

<!-- Modal para quitar medidor -->
<div class="modal fade" id="quitarMedidorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Quitar Medidor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="quitarMedidorForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>¿Está seguro de quitar el medidor de esta manguera?</p>
                    <p class="text-warning">
                        <i class="bi bi-exclamation-triangle"></i> 
                        Esta acción desasociará el medidor de la manguera.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Quitar Medidor</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
});

function confirmarQuitarMedidor(id) {
    $('#quitarMedidorForm').attr('action', "{{ url('mangueras') }}/" + id + "/quitar-medidor");
    new bootstrap.Modal(document.getElementById('quitarMedidorModal')).show();
}
</script>
@endpush