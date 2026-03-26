@extends('layouts.app')

@section('title', 'Contribuyentes')
@section('header', 'Administración de Contribuyentes')

@section('actions')
@if(hasPermission('contribuyentes.manage'))
<a href="{{ route('contribuyentes.create') }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-circle"></i> Nuevo Contribuyente
</a>
@endif
<div class="btn-group">
    <a href="{{ route('contribuyentes.exportar', ['tipo' => 'excel']) }}" class="btn btn-sm btn-success" title="Exportar a Excel">
        <i class="bi bi-file-excel"></i> Excel
    </a>
    <a href="{{ route('contribuyentes.exportar', ['tipo' => 'pdf']) }}" class="btn btn-sm btn-danger" title="Exportar a PDF">
        <i class="bi bi-file-pdf"></i> PDF
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Filtros -->
        <form method="GET" action="{{ route('contribuyentes.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="rfc" class="form-label">RFC</label>
                <input type="text" class="form-control" id="rfc" name="rfc" 
                       value="{{ request('rfc') }}" placeholder="Ej: ABC123456789">
            </div>
            
            <div class="col-md-4">
                <label for="razon_social" class="form-label">Razón Social</label>
                <input type="text" class="form-control" id="razon_social" name="razon_social" 
                       value="{{ request('razon_social') }}" placeholder="Buscar por razón social">
            </div>
            
            <div class="col-md-2">
                <label for="regimen_fiscal" class="form-label">Régimen Fiscal</label>
                <input type="text" class="form-control" id="regimen_fiscal" name="regimen_fiscal" 
                       value="{{ request('regimen_fiscal') }}" placeholder="Régimen">
            </div>
            
            <div class="col-md-2">
                <label for="numero_permiso" class="form-label">N° Permiso</label>
                <input type="text" class="form-control" id="numero_permiso" name="numero_permiso" 
                       value="{{ request('numero_permiso') }}" placeholder="Permiso">
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
                <label for="proxima_verificacion" class="form-label">Próx. Verificación</label>
                <select class="form-select" id="proxima_verificacion" name="proxima_verificacion">
                    <option value="">Todos</option>
                    <option value="proximos" {{ request('proxima_verificacion') == 'proximos' ? 'selected' : '' }}>Próximos 30 días</option>
                    <option value="vencidos" {{ request('proxima_verificacion') == 'vencidos' ? 'selected' : '' }}>Vencidos</option>
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
                <a href="{{ route('contribuyentes.index') }}" class="btn btn-secondary">
                    <i class="bi bi-eraser"></i> Limpiar
                </a>
            </div>
        </form>
        
        <!-- Tabla de contribuyentes -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>RFC</th>
                        <th>Razón Social</th>
                        <th>Régimen Fiscal</th>
                        <th>Permiso</th>
                        <th>Instalaciones</th>
                        <th>Estatus Verificación</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contribuyentes as $contribuyente)
                        <tr>
                            <td><strong>{{ $contribuyente['rfc'] }}</strong></td>
                            <td>
                                {{ $contribuyente['razon_social'] }}
                                @if(!empty($contribuyente['nombre_comercial']))
                                    <br><small class="text-muted">{{ $contribuyente['nombre_comercial'] }}</small>
                                @endif
                            </td>
                            <td>{{ $contribuyente['regimen_fiscal'] }}</td>
                            <td>
                                @if($contribuyente['numero_permiso'])
                                    {{ $contribuyente['numero_permiso'] }}
                                    <br><small>{{ $contribuyente['tipo_permiso'] ?? '' }}</small>
                                @else
                                    <span class="text-muted">No asignado</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $contribuyente['instalaciones_count'] ?? 0 }}</span>
                            </td>
                            <td>
                                @if(isset($contribuyente['estatus_verificacion']))
                                    @php
                                        $verifClass = [
                                            'ACREDITADO' => 'success',
                                            'NO_ACREDITADO' => 'danger',
                                            'PENDIENTE' => 'warning',
                                            'EN_PROCESO' => 'info'
                                        ][$contribuyente['estatus_verificacion']] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $verifClass }}">{{ $contribuyente['estatus_verificacion'] }}</span>
                                @else
                                    <span class="badge bg-secondary">No definido</span>
                                @endif
                            </td>
                            <td>
                                @if($contribuyente['activo'])
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('contribuyentes.show', $contribuyente['id']) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('contribuyentes.edit', $contribuyente['id']) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('contribuyentes.instalaciones', $contribuyente['id']) }}" class="btn btn-sm btn-secondary" title="Instalaciones">
                                        <i class="bi bi-building"></i>
                                    </a>
                                    <a href="{{ route('contribuyentes.cumplimiento', $contribuyente['id']) }}" class="btn btn-sm btn-success" title="Cumplimiento">
                                        <i class="bi bi-check-circle"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay contribuyentes registrados</td>
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
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
});
</script>
@endpush