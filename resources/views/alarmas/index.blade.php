@extends('layouts.app')

@section('title', 'Alarmas')
@section('header', 'Alarmas')

@section('actions')
<a href="{{ route('alarmas.create') }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-circle"></i> Nueva Alarma
</a>
<a href="{{ route('alarmas.estadisticas') }}?instalacion_id={{ request('instalacion_id') }}&fecha_inicio={{ request('fecha_inicio', now()->startOfMonth()->toDateString()) }}&fecha_fin={{ request('fecha_fin', now()->toDateString()) }}" class="btn btn-sm btn-info">
    <i class="bi bi-graph-up"></i> Estadísticas
</a>
<a href="{{ route('alarmas.activas') }}" class="btn btn-sm btn-warning">
    <i class="bi bi-exclamation-triangle"></i> Activas
</a>
<div class="btn-group">
    <a href="{{ route('alarmas.exportar', ['tipo' => 'excel']) }}" class="btn btn-sm btn-success" title="Exportar a Excel">
        <i class="bi bi-file-excel"></i> Excel
    </a>
    <a href="{{ route('alarmas.exportar', ['tipo' => 'pdf']) }}" class="btn btn-sm btn-danger" title="Exportar a PDF">
        <i class="bi bi-file-pdf"></i> PDF
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Filtros -->
        <form method="GET" action="{{ route('alarmas.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="componente_tipo" class="form-label">Tipo de Componente</label>
                <select class="form-select" id="componente_tipo" name="componente_tipo">
                    <option value="">Todos</option>
                    <option value="tanque" {{ request('componente_tipo') == 'tanque' ? 'selected' : '' }}>Tanque</option>
                    <option value="medidor" {{ request('componente_tipo') == 'medidor' ? 'selected' : '' }}>Medidor</option>
                    <option value="dispensario" {{ request('componente_tipo') == 'dispensario' ? 'selected' : '' }}>Dispensario</option>
                    <option value="manguera" {{ request('componente_tipo') == 'manguera' ? 'selected' : '' }}>Manguera</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="gravedad" class="form-label">Gravedad</label>
                <select class="form-select" id="gravedad" name="gravedad">
                    <option value="">Todas</option>
                    <option value="BAJA" {{ request('gravedad') == 'BAJA' ? 'selected' : '' }}>Baja</option>
                    <option value="MEDIA" {{ request('gravedad') == 'MEDIA' ? 'selected' : '' }}>Media</option>
                    <option value="ALTA" {{ request('gravedad') == 'ALTA' ? 'selected' : '' }}>Alta</option>
                    <option value="CRITICA" {{ request('gravedad') == 'CRITICA' ? 'selected' : '' }}>Crítica</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="atendida" class="form-label">Atendida</label>
                <select class="form-select" id="atendida" name="atendida">
                    <option value="">Todas</option>
                    <option value="1" {{ request('atendida') == '1' ? 'selected' : '' }}>Atendidas</option>
                    <option value="0" {{ request('atendida') == '0' ? 'selected' : '' }}>Pendientes</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label for="estado_atencion" class="form-label">Estado</label>
                <select class="form-select" id="estado_atencion" name="estado_atencion">
                    <option value="">Todos</option>
                    <option value="PENDIENTE" {{ request('estado_atencion') == 'PENDIENTE' ? 'selected' : '' }}>Pendiente</option>
                    <option value="EN_PROCESO" {{ request('estado_atencion') == 'EN_PROCESO' ? 'selected' : '' }}>En Proceso</option>
                    <option value="RESUELTA" {{ request('estado_atencion') == 'RESUELTA' ? 'selected' : '' }}>Resuelta</option>
                    <option value="IGNORADA" {{ request('estado_atencion') == 'IGNORADA' ? 'selected' : '' }}>Ignorada</option>
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
            
            <div class="col-md-3">
                <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control datepicker" id="fecha_inicio" 
                       name="fecha_inicio" value="{{ request('fecha_inicio') }}">
            </div>
            
            <div class="col-md-3">
                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                <input type="date" class="form-control datepicker" id="fecha_fin" 
                       name="fecha_fin" value="{{ request('fecha_fin') }}">
            </div>
            
            <div class="col-md-3">
                <label for="numero_registro" class="form-label">Número de Registro</label>
                <input type="text" class="form-control" id="numero_registro" 
                       name="numero_registro" value="{{ request('numero_registro') }}">
            </div>
            
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filtrar
                </button>
                <a href="{{ route('alarmas.index') }}" class="btn btn-secondary">
                    <i class="bi bi-eraser"></i> Limpiar
                </a>
            </div>
        </form>
        
        <!-- Tabla de alarmas -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>N° Registro</th>
                        <th>Fecha/Hora</th>
                        <th>Componente</th>
                        <th>Tipo Alarma</th>
                        <th>Gravedad</th>
                        <th>Estado</th>
                        <th>Requiere Atención</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alarmas as $alarma)
                        <tr>
                            <td>{{ $alarma['numero_registro'] }}</td>
                            <td>{{ $alarma['fecha_hora'] }}</td>
                            <td>
                                {{ $alarma['componente_tipo'] }}<br>
                                <small>{{ $alarma['componente_identificador'] }}</small>
                            </td>
                            <td>{{ $alarma['tipo_alarma']['nombre'] ?? $alarma['tipo_alarma_id'] }}</td>
                            <td>
                                @php
                                    $badgeClass = [
                                        'BAJA' => 'info',
                                        'MEDIA' => 'warning',
                                        'ALTA' => 'danger',
                                        'CRITICA' => 'dark'
                                    ][$alarma['gravedad']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}">{{ $alarma['gravedad'] }}</span>
                            </td>
                            <td>
                                @php
                                    $estadoClass = [
                                        'PENDIENTE' => 'danger',
                                        'EN_PROCESO' => 'warning',
                                        'RESUELTA' => 'success',
                                        'IGNORADA' => 'secondary'
                                    ][$alarma['estado_atencion']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $estadoClass }}">{{ $alarma['estado_atencion'] }}</span>
                            </td>
                            <td>
                                @if($alarma['requiere_atencion_inmediata'])
                                    <span class="badge bg-danger">Sí</span>
                                @else
                                    <span class="badge bg-success">No</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('alarmas.show', $alarma['id']) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($alarma['estado_atencion'] == 'PENDIENTE')
                                        <a href="{{ route('alarmas.atender.form', $alarma['id']) }}" class="btn btn-sm btn-warning" title="Atender">
                                            <i class="bi bi-check-circle"></i>
                                        </a>
                                    @endif
                                    @if(in_array($alarma['estado_atencion'], ['PENDIENTE', 'EN_PROCESO']))
                                        <a href="{{ route('alarmas.actualizar-estado.form', $alarma['id']) }}" class="btn btn-sm btn-secondary" title="Actualizar Estado">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay alarmas registradas</td>
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
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        language: 'es',
        autoclose: true
    });
});
</script>
@endpush