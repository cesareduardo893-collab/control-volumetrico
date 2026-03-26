@extends('layouts.app')

@section('title', 'Dispensarios')
@section('header', 'Dispensarios de Combustible')

@section('actions')
@if(canManageInfrastructure())
<a href="{{ route('dispensarios.create') }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-circle"></i> Nuevo Dispensario
</a>
@endif
<div class="btn-group">
    <a href="{{ route('dispensarios.exportar', ['tipo' => 'excel']) }}" class="btn btn-sm btn-success" title="Exportar a Excel">
        <i class="bi bi-file-excel"></i> Excel
    </a>
    <a href="{{ route('dispensarios.exportar', ['tipo' => 'pdf']) }}" class="btn btn-sm btn-danger" title="Exportar a PDF">
        <i class="bi bi-file-pdf"></i> PDF
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Filtros -->
        <form method="GET" action="{{ route('dispensarios.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="instalacion_id" class="form-label">Instalación</label>
                <select class="form-select select2" id="instalacion_id" name="instalacion_id">
                    <option value="">Todas</option>
                    @foreach($instalaciones ?? [] as $instalacion)
                        <option value="{{ $instalacion['id'] }}" {{ request('instalacion_id') == $instalacion['id'] ? 'selected' : '' }}>
                            {{ $instalacion['nombre'] }}
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
                <label for="modelo" class="form-label">Modelo</label>
                <input type="text" class="form-control" id="modelo" name="modelo" 
                       value="{{ request('modelo') }}" placeholder="Modelo">
            </div>
            
            <div class="col-md-2">
                <label for="fabricante" class="form-label">Fabricante</label>
                <input type="text" class="form-control" id="fabricante" name="fabricante" 
                       value="{{ request('fabricante') }}" placeholder="Fabricante">
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
                <a href="{{ route('dispensarios.index') }}" class="btn btn-secondary">
                    <i class="bi bi-eraser"></i> Limpiar
                </a>
            </div>
        </form>
        
        <!-- Resumen -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6>Total Dispensarios</h6>
                        <h3>{{ $resumen['total'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6>Operativos</h6>
                        <h3>{{ $resumen['operativos'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h6>En Mantenimiento</h6>
                        <h3>{{ $resumen['mantenimiento'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6>Fuera de Servicio</h6>
                        <h3>{{ $resumen['fuera_servicio'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla de dispensarios -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Instalación</th>
                        <th>Descripción</th>
                        <th>Modelo</th>
                        <th>Fabricante</th>
                        <th>Estado</th>
                        <th>Activo</th>
                        <th>Mangueras</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dispensarios as $dispensario)
                        <tr>
                            <td>{{ $dispensario['clave'] }}</td>
                            <td>
                                @if(isset($dispensario['instalacion']))
                                    {{ $dispensario['instalacion']['nombre'] }}<br>
                                    <small class="text-muted">{{ $dispensario['instalacion']['clave_instalacion'] }}</small>
                                @else
                                    {{ $dispensario['instalacion_id'] }}
                                @endif
                            </td>
                            <td>{{ $dispensario['descripcion'] ?? '-' }}</td>
                            <td>{{ $dispensario['modelo'] ?? '-' }}</td>
                            <td>{{ $dispensario['fabricante'] ?? '-' }}</td>
                            <td>
                                @php
                                    $estadoClass = [
                                        'OPERATIVO' => 'success',
                                        'MANTENIMIENTO' => 'warning',
                                        'FUERA_SERVICIO' => 'danger'
                                    ][$dispensario['estado']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $estadoClass }}">{{ $dispensario['estado'] }}</span>
                            </td>
                            <td>
                                @if($dispensario['activo'])
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $dispensario['mangueras_count'] ?? 0 }}</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('dispensarios.show', $dispensario['id']) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('dispensarios.edit', $dispensario['id']) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('dispensarios.mangueras', $dispensario['id']) }}" class="btn btn-sm btn-secondary" title="Mangueras">
                                        <i class="bi bi-pip"></i>
                                    </a>
                                    <a href="{{ route('dispensarios.verificar-estado', $dispensario['id']) }}" class="btn btn-sm btn-primary" title="Verificar Estado">
                                        <i class="bi bi-check-circle"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No hay dispensarios registrados</td>
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