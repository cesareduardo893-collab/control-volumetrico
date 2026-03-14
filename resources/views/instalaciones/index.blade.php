@extends('layouts.app')

@section('title', 'Instalaciones')
@section('header', 'Instalaciones')

@section('actions')
<a href="{{ route('instalaciones.create') }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-circle"></i> Nueva Instalación
</a>
<div class="btn-group">
    <a href="{{ route('instalaciones.exportar', ['tipo' => 'excel']) }}" class="btn btn-sm btn-success" title="Exportar a Excel">
        <i class="bi bi-file-excel"></i> Excel
    </a>
    <a href="{{ route('instalaciones.exportar', ['tipo' => 'pdf']) }}" class="btn btn-sm btn-danger" title="Exportar a PDF">
        <i class="bi bi-file-pdf"></i> PDF
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Filtros -->
        <form method="GET" action="{{ route('instalaciones.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="contribuyente_id" class="form-label">Contribuyente</label>
                <select class="form-select select2" id="contribuyente_id" name="contribuyente_id">
                    <option value="">Todos</option>
                    @foreach($contribuyentes ?? [] as $contribuyente)
                        <option value="{{ $contribuyente['id'] }}" {{ request('contribuyente_id') == $contribuyente['id'] ? 'selected' : '' }}>
                            {{ $contribuyente['razon_social'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="clave_instalacion" class="form-label">Clave</label>
                <input type="text" class="form-control" id="clave_instalacion" name="clave_instalacion" 
                       value="{{ request('clave_instalacion') }}" placeholder="Buscar por clave">
            </div>
            
            <div class="col-md-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" 
                       value="{{ request('nombre') }}" placeholder="Nombre de la instalación">
            </div>
            
            <div class="col-md-2">
                <label for="tipo_instalacion" class="form-label">Tipo</label>
                <select class="form-select" id="tipo_instalacion" name="tipo_instalacion">
                    <option value="">Todos</option>
                    <option value="estacion_servicio" {{ request('tipo_instalacion') == 'estacion_servicio' ? 'selected' : '' }}>Estación de Servicio</option>
                    <option value="terminal" {{ request('tipo_instalacion') == 'terminal' ? 'selected' : '' }}>Terminal</option>
                    <option value="planta" {{ request('tipo_instalacion') == 'planta' ? 'selected' : '' }}>Planta</option>
                    <option value="almacen" {{ request('tipo_instalacion') == 'almacen' ? 'selected' : '' }}>Almacén</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="estatus" class="form-label">Estatus</label>
                <select class="form-select" id="estatus" name="estatus">
                    <option value="">Todos</option>
                    <option value="OPERACION" {{ request('estatus') == 'OPERACION' ? 'selected' : '' }}>Operación</option>
                    <option value="SUSPENDIDA" {{ request('estatus') == 'SUSPENDIDA' ? 'selected' : '' }}>Suspendida</option>
                    <option value="CANCELADA" {{ request('estatus') == 'CANCELADA' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="municipio" class="form-label">Municipio</label>
                <input type="text" class="form-control" id="municipio" name="municipio" 
                       value="{{ request('municipio') }}" placeholder="Municipio">
            </div>
            
            <div class="col-md-2">
                <label for="estado" class="form-label">Estado</label>
                <input type="text" class="form-control" id="estado" name="estado" 
                       value="{{ request('estado') }}" placeholder="Estado">
            </div>
            
            <div class="col-md-2">
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
                <a href="{{ route('instalaciones.index') }}" class="btn btn-secondary">
                    <i class="bi bi-eraser"></i> Limpiar
                </a>
            </div>
        </form>
        
        <!-- Resumen -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6>Total Instalaciones</h6>
                        <h3>{{ $resumen['total'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6>En Operación</h6>
                        <h3>{{ $resumen['operacion'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h6>Suspendidas</h6>
                        <h3>{{ $resumen['suspendidas'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6>Canceladas</h6>
                        <h3>{{ $resumen['canceladas'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla de instalaciones -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Nombre</th>
                        <th>Contribuyente</th>
                        <th>Tipo</th>
                        <th>Domicilio</th>
                        <th>Estatus</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($instalaciones as $instalacion)
                        <tr>
                            <td>{{ $instalacion['clave_instalacion'] }}</td>
                            <td>{{ $instalacion['nombre'] }}</td>
                            <td>
                                @if(isset($instalacion['contribuyente']))
                                    {{ $instalacion['contribuyente']['razon_social'] }}<br>
                                    <small class="text-muted">{{ $instalacion['contribuyente']['rfc'] }}</small>
                                @else
                                    {{ $instalacion['contribuyente_id'] }}
                                @endif
                            </td>
                            <td>{{ ucfirst(str_replace('_', ' ', $instalacion['tipo_instalacion'])) }}</td>
                            <td>
                                {{ $instalacion['domicilio'] }}<br>
                                <small>{{ $instalacion['municipio'] }}, {{ $instalacion['estado'] }}</small>
                            </td>
                            <td>
                                @php
                                    $estatusClass = [
                                        'OPERACION' => 'success',
                                        'SUSPENDIDA' => 'warning',
                                        'CANCELADA' => 'danger'
                                    ][$instalacion['estatus']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $estatusClass }}">{{ $instalacion['estatus'] }}</span>
                            </td>
                            <td>
                                @if($instalacion['activo'])
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('instalaciones.show', $instalacion['id']) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('instalaciones.edit', $instalacion['id']) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('instalaciones.tanques', $instalacion['id']) }}" class="btn btn-sm btn-secondary" title="Tanques">
                                        <i class="bi bi-barrel"></i>
                                    </a>
                                    <a href="{{ route('instalaciones.medidores', $instalacion['id']) }}" class="btn btn-sm btn-primary" title="Medidores">
                                        <i class="bi bi-speedometer"></i>
                                    </a>
                                    <a href="{{ route('instalaciones.dispensarios', $instalacion['id']) }}" class="btn btn-sm btn-success" title="Dispensarios">
                                        <i class="bi bi-fuel-pump"></i>
                                    </a>
                                    <a href="{{ route('instalaciones.resumen-operativo', $instalacion['id']) }}" class="btn btn-sm btn-info" title="Resumen Operativo">
                                        <i class="bi bi-graph-up"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay instalaciones registradas</td>
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