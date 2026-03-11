@extends('layouts.app')

@section('title', 'Registros Volumétricos')
@section('header', 'Registros Volumétricos')

@section('actions')
<a href="{{ route('registros-volumetricos.create') }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-circle"></i> Nuevo Registro
</a>
<a href="{{ route('registros-volumetricos.resumen-diario') }}?instalacion_id={{ request('instalacion_id') }}&fecha={{ now()->toDateString() }}" class="btn btn-sm btn-info">
    <i class="bi bi-calendar-day"></i> Resumen Diario
</a>
<a href="{{ route('registros-volumetricos.estadisticas-mensuales') }}?instalacion_id={{ request('instalacion_id') }}&anio={{ now()->year }}&mes={{ now()->month }}" class="btn btn-sm btn-success">
    <i class="bi bi-graph-up"></i> Estadísticas Mensuales
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Filtros -->
        <form method="GET" action="{{ route('registros-volumetricos.index') }}" class="row g-3 mb-4">
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
                <label for="numero_registro" class="form-label">N° Registro</label>
                <input type="text" class="form-control" id="numero_registro" name="numero_registro" 
                       value="{{ request('numero_registro') }}" placeholder="Buscar...">
            </div>
            
            <div class="col-md-3">
                <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control datepicker" id="fecha_inicio" 
                       name="fecha_inicio" value="{{ request('fecha_inicio', now()->startOfMonth()->toDateString()) }}">
            </div>
            
            <div class="col-md-3">
                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                <input type="date" class="form-control datepicker" id="fecha_fin" 
                       name="fecha_fin" value="{{ request('fecha_fin', now()->toDateString()) }}">
            </div>
            
            <div class="col-md-2">
                <label for="tipo_registro" class="form-label">Tipo Registro</label>
                <select class="form-select" id="tipo_registro" name="tipo_registro">
                    <option value="">Todos</option>
                    <option value="operacion" {{ request('tipo_registro') == 'operacion' ? 'selected' : '' }}>Operación</option>
                    <option value="acumulado" {{ request('tipo_registro') == 'acumulado' ? 'selected' : '' }}>Acumulado</option>
                    <option value="existencias" {{ request('tipo_registro') == 'existencias' ? 'selected' : '' }}>Existencias</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="operacion" class="form-label">Operación</label>
                <select class="form-select" id="operacion" name="operacion">
                    <option value="">Todos</option>
                    <option value="recepcion" {{ request('operacion') == 'recepcion' ? 'selected' : '' }}>Recepción</option>
                    <option value="entrega" {{ request('operacion') == 'entrega' ? 'selected' : '' }}>Entrega</option>
                    <option value="inventario_inicial" {{ request('operacion') == 'inventario_inicial' ? 'selected' : '' }}>Inventario Inicial</option>
                    <option value="inventario_final" {{ request('operacion') == 'inventario_final' ? 'selected' : '' }}>Inventario Final</option>
                    <option value="venta" {{ request('operacion') == 'venta' ? 'selected' : '' }}>Venta</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="">Todos</option>
                    <option value="PENDIENTE" {{ request('estado') == 'PENDIENTE' ? 'selected' : '' }}>Pendiente</option>
                    <option value="PROCESADO" {{ request('estado') == 'PROCESADO' ? 'selected' : '' }}>Procesado</option>
                    <option value="VALIDADO" {{ request('estado') == 'VALIDADO' ? 'selected' : '' }}>Validado</option>
                    <option value="ERROR" {{ request('estado') == 'ERROR' ? 'selected' : '' }}>Error</option>
                    <option value="CANCELADO" {{ request('estado') == 'CANCELADO' ? 'selected' : '' }}>Cancelado</option>
                    <option value="CON_ALARMA" {{ request('estado') == 'CON_ALARMA' ? 'selected' : '' }}>Con Alarma</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="documento_fiscal_uuid" class="form-label">UUID Documento</label>
                <input type="text" class="form-control" id="documento_fiscal_uuid" name="documento_fiscal_uuid" 
                       value="{{ request('documento_fiscal_uuid') }}" placeholder="UUID">
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
                <a href="{{ route('registros-volumetricos.index') }}" class="btn btn-secondary">
                    <i class="bi bi-eraser"></i> Limpiar
                </a>
            </div>
        </form>
        
        <!-- Resumen -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6>Total Registros</h6>
                        <h3>{{ $resumen['total'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6>Validados</h6>
                        <h3>{{ $resumen['validados'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h6>Pendientes</h6>
                        <h3>{{ $resumen['pendientes'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6>Con Alarma</h6>
                        <h3>{{ $resumen['con_alarma'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla de registros -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>N° Registro</th>
                        <th>Fecha/Hora</th>
                        <th>Instalación</th>
                        <th>Tanque</th>
                        <th>Producto</th>
                        <th>Volumen Operación</th>
                        <th>Tipo</th>
                        <th>Operación</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registros as $registro)
                        <tr>
                            <td>{{ $registro['numero_registro'] }}</td>
                            <td>{{ $registro['fecha'] }} {{ substr($registro['hora_inicio'], 0, 5) }}</td>
                            <td>
                                @if(isset($registro['instalacion']))
                                    {{ $registro['instalacion']['nombre'] }}
                                @else
                                    {{ $registro['instalacion_id'] }}
                                @endif
                            </td>
                            <td>
                                @if(isset($registro['tanque']))
                                    {{ $registro['tanque']['identificador'] }}
                                @else
                                    {{ $registro['tanque_id'] }}
                                @endif
                            </td>
                            <td>
                                @if(isset($registro['producto']))
                                    {{ $registro['producto']['nombre'] }}
                                @else
                                    {{ $registro['producto_id'] }}
                                @endif
                            </td>
                            <td><strong>{{ number_format($registro['volumen_operacion'], 3) }} L</strong></td>
                            <td>
                                <span class="badge bg-info">{{ $registro['tipo_registro'] }}</span>
                            </td>
                            <td>
                                @php
                                    $operacionClass = [
                                        'recepcion' => 'success',
                                        'entrega' => 'danger',
                                        'venta' => 'warning',
                                        'inventario_inicial' => 'secondary',
                                        'inventario_final' => 'secondary'
                                    ][$registro['operacion']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $operacionClass }}">{{ $registro['operacion'] }}</span>
                            </td>
                            <td>
                                @php
                                    $estadoClass = [
                                        'VALIDADO' => 'success',
                                        'PROCESADO' => 'info',
                                        'PENDIENTE' => 'warning',
                                        'ERROR' => 'danger',
                                        'CANCELADO' => 'secondary',
                                        'CON_ALARMA' => 'danger'
                                    ][$registro['estado']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $estadoClass }}">{{ $registro['estado'] }}</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('registros-volumetricos.show', $registro['id']) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(in_array($registro['estado'], ['PENDIENTE', 'ERROR']))
                                        <button type="button" class="btn btn-sm btn-success" 
                                                onclick="confirmarValidacion({{ $registro['id'] }})" title="Validar">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="confirmarCancelacion({{ $registro['id'] }})" title="Cancelar">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">No hay registros volumétricos</td>
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

<!-- Modal de validación -->
<div class="modal fade" id="validarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Validar Registro Volumétrico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="validarForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="observaciones_validacion" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones_validacion" 
                                  name="observaciones_validacion" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Confirmar Validación</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de cancelación -->
<div class="modal fade" id="cancelarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Cancelar Registro Volumétrico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="cancelarForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="motivo_cancelacion" class="form-label">Motivo de Cancelación</label>
                        <textarea class="form-control" id="motivo_cancelacion" 
                                  name="motivo_cancelacion" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Cancelación</button>
                </div>
            </form>
        </div>
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

function confirmarValidacion(id) {
    $('#validarForm').attr('action', `{{ url('registros-volumetricos') }}/${id}/validar`);
    new bootstrap.Modal(document.getElementById('validarModal')).show();
}

function confirmarCancelacion(id) {
    $('#cancelarForm').attr('action', `{{ url('registros-volumetricos') }}/${id}/cancelar`);
    new bootstrap.Modal(document.getElementById('cancelarModal')).show();
}
</script>
@endpush