@extends('layouts.app')

@section('title', 'Existencias')
@section('header', 'Control de Existencias')

@section('actions')
<a href="{{ route('existencias.create') }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-circle"></i> Nuevo Registro
</a>
<a href="{{ route('existencias.reporte-mermas') }}?instalacion_id={{ request('instalacion_id') }}&fecha_inicio={{ now()->startOfMonth()->toDateString() }}&fecha_fin={{ now()->toDateString() }}" class="btn btn-sm btn-info">
    <i class="bi bi-graph-up"></i> Reporte de Mermas
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Filtros -->
        <form method="GET" action="{{ route('existencias.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="tanque_id" class="form-label">Tanque</label>
                <select class="form-select select2" id="tanque_id" name="tanque_id">
                    <option value="">Todos</option>
                    @foreach($tanques ?? [] as $tanque)
                        <option value="{{ $tanque['id'] }}" {{ request('tanque_id') == $tanque['id'] ? 'selected' : '' }}>
                            {{ $tanque['identificador'] }} - {{ $tanque['producto']['nombre'] ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-3">
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
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" class="form-control datepicker" id="fecha" name="fecha" 
                       value="{{ request('fecha') }}">
            </div>
            
            <div class="col-md-2">
                <label for="tipo_registro" class="form-label">Tipo Registro</label>
                <select class="form-select" id="tipo_registro" name="tipo_registro">
                    <option value="">Todos</option>
                    <option value="inicial" {{ request('tipo_registro') == 'inicial' ? 'selected' : '' }}>Inicial</option>
                    <option value="operacion" {{ request('tipo_registro') == 'operacion' ? 'selected' : '' }}>Operación</option>
                    <option value="final" {{ request('tipo_registro') == 'final' ? 'selected' : '' }}>Final</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="tipo_movimiento" class="form-label">Tipo Movimiento</label>
                <select class="form-select" id="tipo_movimiento" name="tipo_movimiento">
                    <option value="">Todos</option>
                    <option value="INICIAL" {{ request('tipo_movimiento') == 'INICIAL' ? 'selected' : '' }}>Inicial</option>
                    <option value="RECEPCION" {{ request('tipo_movimiento') == 'RECEPCION' ? 'selected' : '' }}>Recepción</option>
                    <option value="ENTREGA" {{ request('tipo_movimiento') == 'ENTREGA' ? 'selected' : '' }}>Entrega</option>
                    <option value="VENTA" {{ request('tipo_movimiento') == 'VENTA' ? 'selected' : '' }}>Venta</option>
                    <option value="TRASPASO" {{ request('tipo_movimiento') == 'TRASPASO' ? 'selected' : '' }}>Traspaso</option>
                    <option value="AJUSTE" {{ request('tipo_movimiento') == 'AJUSTE' ? 'selected' : '' }}>Ajuste</option>
                    <option value="INVENTARIO" {{ request('tipo_movimiento') == 'INVENTARIO' ? 'selected' : '' }}>Inventario</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="">Todos</option>
                    <option value="PENDIENTE" {{ request('estado') == 'PENDIENTE' ? 'selected' : '' }}>Pendiente</option>
                    <option value="VALIDADO" {{ request('estado') == 'VALIDADO' ? 'selected' : '' }}>Validado</option>
                    <option value="EN_REVISION" {{ request('estado') == 'EN_REVISION' ? 'selected' : '' }}>En Revisión</option>
                    <option value="CON_ALARMA" {{ request('estado') == 'CON_ALARMA' ? 'selected' : '' }}>Con Alarma</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="numero_registro" class="form-label">N° Registro</label>
                <input type="text" class="form-control" id="numero_registro" name="numero_registro" 
                       value="{{ request('numero_registro') }}" placeholder="Buscar...">
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
                <a href="{{ route('existencias.index') }}" class="btn btn-secondary">
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
                        <h6>En Revisión</h6>
                        <h3>{{ $resumen['en_revision'] ?? 0 }}</h3>
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
        
        <!-- Tabla de existencias -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>N° Registro</th>
                        <th>Fecha/Hora</th>
                        <th>Tanque</th>
                        <th>Producto</th>
                        <th>Volumen Medido</th>
                        <th>Volumen Corregido</th>
                        <th>Volumen Disponible</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($existencias as $existencia)
                        <tr>
                            <td>{{ $existencia['numero_registro'] }}</td>
                            <td>{{ $existencia['fecha'] }} {{ $existencia['hora'] }}</td>
                            <td>
                                @if(isset($existencia['tanque']))
                                    {{ $existencia['tanque']['identificador'] }}
                                @else
                                    {{ $existencia['tanque_id'] }}
                                @endif
                            </td>
                            <td>
                                @if(isset($existencia['producto']))
                                    {{ $existencia['producto']['nombre'] }}
                                @else
                                    {{ $existencia['producto_id'] }}
                                @endif
                            </td>
                            <td>{{ number_format($existencia['volumen_medido'], 3) }} L</td>
                            <td>{{ number_format($existencia['volumen_corregido'], 3) }} L</td>
                            <td>{{ number_format($existencia['volumen_disponible'], 3) }} L</td>
                            <td>
                                <span class="badge bg-info">{{ $existencia['tipo_registro'] }}</span>
                                <br>
                                <small>{{ $existencia['tipo_movimiento'] }}</small>
                            </td>
                            <td>
                                @php
                                    $estadoClass = [
                                        'VALIDADO' => 'success',
                                        'PENDIENTE' => 'warning',
                                        'EN_REVISION' => 'info',
                                        'CON_ALARMA' => 'danger'
                                    ][$existencia['estado']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $estadoClass }}">{{ $existencia['estado'] }}</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('existencias.show', $existencia['id']) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($existencia['estado'] == 'PENDIENTE')
                                        <button type="button" class="btn btn-sm btn-success" 
                                                onclick="confirmarValidacion({{ $existencia['id'] }})" title="Validar">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">No hay registros de existencias</td>
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
                <h5 class="modal-title">Validar Existencia</h5>
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
    $('#validarForm').attr('action', `{{ url('existencias') }}/${id}/validar`);
    new bootstrap.Modal(document.getElementById('validarModal')).show();
}
</script>
@endpush