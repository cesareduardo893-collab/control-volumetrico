@extends('layouts.app')

@section('title', 'Medidores')
@section('header', 'Medidores de Flujo')

@section('actions')
<a href="{{ route('medidores.create') }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-circle"></i> Nuevo Medidor
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Filtros -->
        <form method="GET" action="{{ route('medidores.index') }}" class="row g-3 mb-4">
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
                <label for="tanque_id" class="form-label">Tanque</label>
                <select class="form-select select2" id="tanque_id" name="tanque_id">
                    <option value="">Todos</option>
                    @foreach($tanques ?? [] as $tanque)
                        <option value="{{ $tanque['id'] }}" {{ request('tanque_id') == $tanque['id'] ? 'selected' : '' }}>
                            {{ $tanque['identificador'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="numero_serie" class="form-label">N° Serie</label>
                <input type="text" class="form-control" id="numero_serie" name="numero_serie" 
                       value="{{ request('numero_serie') }}" placeholder="Buscar por serie">
            </div>
            
            <div class="col-md-2">
                <label for="clave" class="form-label">Clave</label>
                <input type="text" class="form-control" id="clave" name="clave" 
                       value="{{ request('clave') }}" placeholder="Buscar por clave">
            </div>
            
            <div class="col-md-2">
                <label for="elemento_tipo" class="form-label">Tipo Elemento</label>
                <select class="form-select" id="elemento_tipo" name="elemento_tipo">
                    <option value="">Todos</option>
                    <option value="primario" {{ request('elemento_tipo') == 'primario' ? 'selected' : '' }}>Primario</option>
                    <option value="secundario" {{ request('elemento_tipo') == 'secundario' ? 'selected' : '' }}>Secundario</option>
                    <option value="terciario" {{ request('elemento_tipo') == 'terciario' ? 'selected' : '' }}>Terciario</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="tipo_medicion" class="form-label">Tipo Medición</label>
                <select class="form-select" id="tipo_medicion" name="tipo_medicion">
                    <option value="">Todos</option>
                    <option value="estatica" {{ request('tipo_medicion') == 'estatica' ? 'selected' : '' }}>Estática</option>
                    <option value="dinamica" {{ request('tipo_medicion') == 'dinamica' ? 'selected' : '' }}>Dinámica</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="">Todos</option>
                    <option value="OPERATIVO" {{ request('estado') == 'OPERATIVO' ? 'selected' : '' }}>Operativo</option>
                    <option value="CALIBRACION" {{ request('estado') == 'CALIBRACION' ? 'selected' : '' }}>Calibración</option>
                    <option value="MANTENIMIENTO" {{ request('estado') == 'MANTENIMIENTO' ? 'selected' : '' }}>Mantenimiento</option>
                    <option value="FUERA_SERVICIO" {{ request('estado') == 'FUERA_SERVICIO' ? 'selected' : '' }}>Fuera de Servicio</option>
                    <option value="FALLA_COMUNICACION" {{ request('estado') == 'FALLA_COMUNICACION' ? 'selected' : '' }}>Falla Comunicación</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="protocolo_comunicacion" class="form-label">Protocolo</label>
                <input type="text" class="form-control" id="protocolo_comunicacion" name="protocolo_comunicacion" 
                       value="{{ request('protocolo_comunicacion') }}" placeholder="Ej: MODBUS">
            </div>
            
            <div class="col-md-2">
                <label for="activo" class="form-label">Activo</label>
                <select class="form-select" id="activo" name="activo">
                    <option value="">Todos</option>
                    <option value="1" {{ request('activo') == '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('activo') == '0' ? 'selected' : '' }}>Inactivos</option>
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
                <a href="{{ route('medidores.index') }}" class="btn btn-secondary">
                    <i class="bi bi-eraser"></i> Limpiar
                </a>
            </div>
        </form>
        
        <!-- Resumen -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6>Total Medidores</h6>
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
                        <h6>En Calibración</h6>
                        <h3>{{ $resumen['calibracion'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6>Falla Comunicación</h6>
                        <h3>{{ $resumen['falla_comunicacion'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla de medidores -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>N° Serie</th>
                        <th>Instalación</th>
                        <th>Tanque</th>
                        <th>Modelo</th>
                        <th>Tipo</th>
                        <th>Elemento</th>
                        <th>Precisión</th>
                        <th>Estado</th>
                        <th>Próx. Calibración</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medidores as $medidor)
                        <tr>
                            <td><strong>{{ $medidor['clave'] }}</strong></td>
                            <td>{{ $medidor['numero_serie'] }}</td>
                            <td>
                                @if(isset($medidor['instalacion']))
                                    {{ $medidor['instalacion']['nombre'] }}
                                @else
                                    {{ $medidor['instalacion_id'] }}
                                @endif
                            </td>
                            <td>
                                @if(isset($medidor['tanque']))
                                    <a href="{{ route('tanques.show', $medidor['tanque']['id']) }}">
                                        {{ $medidor['tanque']['identificador'] }}
                                    </a>
                                @else
                                    <span class="text-muted">No asignado</span>
                                @endif
                            </td>
                            <td>{{ $medidor['modelo'] ?? '-' }}</td>
                            <td>{{ $medidor['tipo_medicion'] }}</td>
                            <td>{{ ucfirst($medidor['elemento_tipo']) }}</td>
                            <td>{{ $medidor['precision'] }}%</td>
                            <td>
                                @php
                                    $estadoClass = [
                                        'OPERATIVO' => 'success',
                                        'CALIBRACION' => 'info',
                                        'MANTENIMIENTO' => 'warning',
                                        'FUERA_SERVICIO' => 'danger',
                                        'FALLA_COMUNICACION' => 'secondary'
                                    ][$medidor['estado']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $estadoClass }}">{{ $medidor['estado'] }}</span>
                            </td>
                            <td>
                                @if(isset($medidor['fecha_proxima_calibracion']))
                                    @php
                                        $dias = now()->diffInDays($medidor['fecha_proxima_calibracion'], false);
                                        $badgeClass = $dias < 7 ? 'danger' : ($dias < 15 ? 'warning' : 'success');
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }}">
                                        {{ round($dias) }} días
                                    </span>
                                    <br>
                                    <small>{{ $medidor['fecha_proxima_calibracion'] }}</small>
                                @else
                                    No programada
                                @endif
                            </td>
                            <td>
                                @if($medidor['activo'])
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('medidores.show', $medidor['id']) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('medidores.edit', $medidor['id']) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('medidores.historial-calibraciones', $medidor['id']) }}" class="btn btn-sm btn-secondary" title="Historial Calibraciones">
                                        <i class="bi bi-calendar-check"></i>
                                    </a>
                                    <a href="{{ route('medidores.verificar-estado', $medidor['id']) }}" class="btn btn-sm btn-primary" title="Verificar Estado">
                                        <i class="bi bi-check-circle"></i>
                                    </a>
                                    @if($medidor['estado'] == 'OPERATIVO')
                                        <a href="{{ route('medidores.probar-comunicacion', $medidor['id']) }}" 
                                           class="btn btn-sm btn-success" title="Probar Comunicación">
                                            <i class="bi bi-wifi"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center">No hay medidores registrados</td>
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