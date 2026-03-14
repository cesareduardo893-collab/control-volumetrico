@extends('layouts.app')

@section('title', 'Bitácora de Eventos')
@section('header', 'Bitácora de Eventos')

@section('actions')
<div class="btn-group">
    <a href="{{ route('bitacora.exportar', ['tipo' => 'excel']) }}" class="btn btn-sm btn-success" title="Exportar a Excel">
        <i class="bi bi-file-excel"></i> Excel
    </a>
    <a href="{{ route('bitacora.exportar', ['tipo' => 'pdf']) }}" class="btn btn-sm btn-danger" title="Exportar a PDF">
        <i class="bi bi-file-pdf"></i> PDF
    </a>
</div>
<a href="{{ route('bitacora.resumen') }}?fecha_inicio={{ now()->startOfMonth()->toDateString() }}&fecha_fin={{ now()->toDateString() }}" class="btn btn-sm btn-info">
    <i class="bi bi-file-text"></i> Resumen
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Filtros -->
        <form method="GET" action="{{ route('bitacora.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="usuario_id" class="form-label">Usuario</label>
                <select class="form-select select2" id="usuario_id" name="usuario_id">
                    <option value="">Todos</option>
                    @foreach($usuarios ?? [] as $usuario)
                        <option value="{{ $usuario['id'] }}" {{ request('usuario_id') == $usuario['id'] ? 'selected' : '' }}>
                            {{ $usuario['nombres'] }} {{ $usuario['apellidos'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="tipo_evento" class="form-label">Tipo Evento</label>
                <select class="form-select" id="tipo_evento" name="tipo_evento">
                    <option value="">Todos</option>
                    <option value="LOGIN" {{ request('tipo_evento') == 'LOGIN' ? 'selected' : '' }}>Login</option>
                    <option value="LOGOUT" {{ request('tipo_evento') == 'LOGOUT' ? 'selected' : '' }}>Logout</option>
                    <option value="CREATE" {{ request('tipo_evento') == 'CREATE' ? 'selected' : '' }}>Creación</option>
                    <option value="UPDATE" {{ request('tipo_evento') == 'UPDATE' ? 'selected' : '' }}>Actualización</option>
                    <option value="DELETE" {{ request('tipo_evento') == 'DELETE' ? 'selected' : '' }}>Eliminación</option>
                    <option value="VIEW" {{ request('tipo_evento') == 'VIEW' ? 'selected' : '' }}>Vista</option>
                    <option value="EXPORT" {{ request('tipo_evento') == 'EXPORT' ? 'selected' : '' }}>Exportación</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="modulo" class="form-label">Módulo</label>
                <select class="form-select" id="modulo" name="modulo">
                    <option value="">Todos</option>
                    <option value="seguridad" {{ request('modulo') == 'seguridad' ? 'selected' : '' }}>Seguridad</option>
                    <option value="usuarios" {{ request('modulo') == 'usuarios' ? 'selected' : '' }}>Usuarios</option>
                    <option value="roles" {{ request('modulo') == 'roles' ? 'selected' : '' }}>Roles</option>
                    <option value="permisos" {{ request('modulo') == 'permisos' ? 'selected' : '' }}>Permisos</option>
                    <option value="instalaciones" {{ request('modulo') == 'instalaciones' ? 'selected' : '' }}>Instalaciones</option>
                    <option value="tanques" {{ request('modulo') == 'tanques' ? 'selected' : '' }}>Tanques</option>
                    <option value="medidores" {{ request('modulo') == 'medidores' ? 'selected' : '' }}>Medidores</option>
                    <option value="dispensarios" {{ request('modulo') == 'dispensarios' ? 'selected' : '' }}>Dispensarios</option>
                    <option value="registros_volumetricos" {{ request('modulo') == 'registros_volumetricos' ? 'selected' : '' }}>Registros Volumétricos</option>
                    <option value="existencias" {{ request('modulo') == 'existencias' ? 'selected' : '' }}>Existencias</option>
                    <option value="alarmas" {{ request('modulo') == 'alarmas' ? 'selected' : '' }}>Alarmas</option>
                    <option value="cfdi" {{ request('modulo') == 'cfdi' ? 'selected' : '' }}>CFDI</option>
                    <option value="reportes_sat" {{ request('modulo') == 'reportes_sat' ? 'selected' : '' }}>Reportes SAT</option>
                    <option value="dictamenes" {{ request('modulo') == 'dictamenes' ? 'selected' : '' }}>Dictámenes</option>
                    <option value="pedimentos" {{ request('modulo') == 'pedimentos' ? 'selected' : '' }}>Pedimentos</option>
                    <option value="contribuyentes" {{ request('modulo') == 'contribuyentes' ? 'selected' : '' }}>Contribuyentes</option>
                    <option value="productos" {{ request('modulo') == 'productos' ? 'selected' : '' }}>Productos</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="tabla" class="form-label">Tabla</label>
                <input type="text" class="form-control" id="tabla" name="tabla" 
                       value="{{ request('tabla') }}" placeholder="Ej: users">
            </div>
            
            <div class="col-md-3">
                <label for="registro_id" class="form-label">ID del Registro</label>
                <input type="text" class="form-control" id="registro_id" name="registro_id" 
                       value="{{ request('registro_id') }}" placeholder="ID del registro">
            </div>
            
            <div class="col-md-3">
                <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control datepicker" id="fecha_inicio" 
                       name="fecha_inicio" value="{{ request('fecha_inicio', now()->subDays(30)->toDateString()) }}">
            </div>
            
            <div class="col-md-3">
                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                <input type="date" class="form-control datepicker" id="fecha_fin" 
                       name="fecha_fin" value="{{ request('fecha_fin', now()->toDateString()) }}">
            </div>
            
            <div class="col-md-3">
                <label for="ip_address" class="form-label">Dirección IP</label>
                <input type="text" class="form-control" id="ip_address" name="ip_address" 
                       value="{{ request('ip_address') }}" placeholder="Ej: 192.168.1.1">
            </div>
            
            <div class="col-md-3">
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
                <a href="{{ route('bitacora.index') }}" class="btn btn-secondary">
                    <i class="bi bi-eraser"></i> Limpiar
                </a>
            </div>
        </form>
        
        <!-- Tabla de eventos -->
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="bitacoraTable">
                <thead>
                    <tr>
                        <th>Fecha/Hora</th>
                        <th>Usuario</th>
                        <th>Tipo Evento</th>
                        <th>Módulo</th>
                        <th>Descripción</th>
                        <th>Tabla/Registro</th>
                        <th>IP</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($eventos as $evento)
                        <tr>
                            <td>{{ $evento['fecha_hora'] ?? $evento['created_at'] ?? '-' }}</td>
                            <td>
                                @if($evento['usuario'])
                                    {{ $evento['usuario']['nombres'] }} {{ $evento['usuario']['apellidos'] }}
                                @else
                                    <span class="text-muted">Sistema</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badgeClass = [
                                        'LOGIN' => 'success',
                                        'LOGOUT' => 'secondary',
                                        'CREATE' => 'primary',
                                        'UPDATE' => 'warning',
                                        'DELETE' => 'danger',
                                        'VIEW' => 'info',
                                        'EXPORT' => 'dark'
                                    ][$evento['tipo_evento']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}">{{ $evento['tipo_evento'] }}</span>
                            </td>
                            <td>{{ $evento['modulo'] ?? '-' }}</td>
                            <td>{{ Str::limit($evento['descripcion'], 50) }}</td>
                            <td>
                                @if($evento['tabla'])
                                    {{ $evento['tabla'] }}<br>
                                    <small>ID: {{ $evento['registro_id'] ?? '-' }}</small>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $evento['ip_address'] ?? '-' }}</td>
                            <td>
                                <a href="{{ route('bitacora.show', $evento['id']) }}" class="btn btn-sm btn-info" title="Ver detalle">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay eventos registrados</td>
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
    
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
});
</script>
@endpush
