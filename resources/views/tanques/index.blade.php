@extends('layouts.app')

@section('title', 'Tanques')
@section('header', 'Tanques de Almacenamiento')

@section('actions')
<a href="{{ route('tanques.create') }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-circle"></i> Nuevo Tanque
</a>
<div class="btn-group">
    <a href="{{ route('tanques.exportar', ['tipo' => 'excel']) }}" class="btn btn-sm btn-success" title="Exportar a Excel">
        <i class="bi bi-file-excel"></i> Excel
    </a>
    <a href="{{ route('tanques.exportar', ['tipo' => 'pdf']) }}" class="btn btn-sm btn-danger" title="Exportar a PDF">
        <i class="bi bi-file-pdf"></i> PDF
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Filtros -->
        <form method="GET" action="{{ route('tanques.index') }}" class="row g-3 mb-4">
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
                <label for="producto_id" class="form-label">Producto</label>
                <select class="form-select select2" id="producto_id" name="producto_id">
                    <option value="">Todos</option>
                    @foreach($productos ?? [] as $producto)
                        <option value="{{ $producto['id'] }}" {{ request('producto_id') == $producto['id'] ? 'selected' : '' }}>
                            {{ $producto['nombre'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="identificador" class="form-label">Identificador</label>
                <input type="text" class="form-control" id="identificador" name="identificador" 
                       value="{{ request('identificador') }}" placeholder="Buscar...">
            </div>
            
            <div class="col-md-2">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="">Todos</option>
                    <option value="OPERATIVO" {{ request('estado') == 'OPERATIVO' ? 'selected' : '' }}>Operativo</option>
                    <option value="MANTENIMIENTO" {{ request('estado') == 'MANTENIMIENTO' ? 'selected' : '' }}>Mantenimiento</option>
                    <option value="FUERA_SERVICIO" {{ request('estado') == 'FUERA_SERVICIO' ? 'selected' : '' }}>Fuera de Servicio</option>
                    <option value="CALIBRACION" {{ request('estado') == 'CALIBRACION' ? 'selected' : '' }}>Calibración</option>
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
                <label for="tipo_tanque_id" class="form-label">Tipo</label>
                <select class="form-select" id="tipo_tanque_id" name="tipo_tanque_id">
                    <option value="">Todos</option>
                    @foreach($tipos_tanque ?? [] as $tipo)
                        <option value="{{ $tipo['id'] }}" {{ request('tipo_tanque_id') == $tipo['id'] ? 'selected' : '' }}>
                            {{ $tipo['nombre'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="calibracion_proxima" class="form-label">Próx. Calibración</label>
                <select class="form-select" id="calibracion_proxima" name="calibracion_proxima">
                    <option value="">Todos</option>
                    <option value="proximos" {{ request('calibracion_proxima') == 'proximos' ? 'selected' : '' }}>Próximos 30 días</option>
                    <option value="vencidos" {{ request('calibracion_proxima') == 'vencidos' ? 'selected' : '' }}>Vencidos</option>
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
                <a href="{{ route('tanques.index') }}" class="btn btn-secondary">
                    <i class="bi bi-eraser"></i> Limpiar
                </a>
            </div>
        </form>
        
        <!-- Resumen -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6>Total Tanques</h6>
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
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6>En Calibración</h6>
                        <h3>{{ $resumen['calibracion'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla de tanques -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Identificador</th>
                        <th>Instalación</th>
                        <th>Producto</th>
                        <th>Capacidad Total</th>
                        <th>Volumen Actual</th>
                        <th>% Ocupación</th>
                        <th>Estado</th>
                        <th>Próx. Calibración</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tanques as $tanque)
                        <tr>
                            <td><strong>{{ $tanque['identificador'] }}</strong></td>
                            <td>
                                @if(isset($tanque['instalacion']))
                                    {{ $tanque['instalacion']['nombre'] }}
                                @else
                                    {{ $tanque['instalacion_id'] }}
                                @endif
                            </td>
                            <td>
                                @if(isset($tanque['producto']))
                                    {{ $tanque['producto']['nombre'] }}
                                @else
                                    <span class="text-muted">No asignado</span>
                                @endif
                            </td>
                            <td>{{ number_format($tanque['capacidad_total'], 3) }} L</td>
                            <td>
                                @if(isset($tanque['volumen_actual']))
                                    <strong>{{ number_format($tanque['volumen_actual'], 3) }} L</strong>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($tanque['volumen_actual']) && $tanque['capacidad_util'] > 0)
                                    @php
                                        $porcentaje = ($tanque['volumen_actual'] / $tanque['capacidad_util']) * 100;
                                        $barClass = $porcentaje > 90 ? 'danger' : ($porcentaje > 75 ? 'warning' : 'success');
                                    @endphp
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-{{ $barClass }}" role="progressbar" 
                                             style="width: {{ $porcentaje }}%">{{ number_format($porcentaje, 1) }}%</div>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @php
                                    $estadoClass = [
                                        'OPERATIVO' => 'success',
                                        'MANTENIMIENTO' => 'warning',
                                        'FUERA_SERVICIO' => 'danger',
                                        'CALIBRACION' => 'info'
                                    ][$tanque['estado']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $estadoClass }}">{{ $tanque['estado'] }}</span>
                            </td>
                            <td>
                                @if(isset($tanque['fecha_proxima_calibracion']))
                                    @php
                                        $dias = now()->diffInDays($tanque['fecha_proxima_calibracion'], false);
                                        $badgeClass = $dias < 7 ? 'danger' : ($dias < 15 ? 'warning' : 'success');
                                    @endphp
                                    {{ $tanque['fecha_proxima_calibracion'] }}
                                    <span class="badge bg-{{ $badgeClass }}">{{ round($dias) }} días</span>
                                @else
                                    No programada
                                @endif
                            </td>
                            <td>
                                @if($tanque['activo'])
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('tanques.show', $tanque['id']) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('tanques.edit', $tanque['id']) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('tanques.curva-calibracion', $tanque['id']) }}" class="btn btn-sm btn-secondary" title="Curva Calibración">
                                        <i class="bi bi-graph-up"></i>
                                    </a>
                                    <a href="{{ route('tanques.verificar-estado', $tanque['id']) }}" class="btn btn-sm btn-primary" title="Verificar Estado">
                                        <i class="bi bi-check-circle"></i>
                                    </a>
                                    <a href="{{ route('existencias.inventario-actual', $tanque['id']) }}" class="btn btn-sm btn-success" title="Inventario">
                                        <i class="bi bi-box"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">No hay tanques registrados</td>
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