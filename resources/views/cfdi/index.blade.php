@extends('layouts.app')

@section('title', 'CFDI')
@section('header', 'Comprobantes Fiscales Digitales (CFDI)')

@section('actions')
<a href="{{ route('cfdi.create') }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-circle"></i> Nuevo CFDI
</a>
<div class="btn-group">
    <a href="{{ route('cfdi.exportar', ['tipo' => 'excel']) }}" class="btn btn-sm btn-success" title="Exportar a Excel">
        <i class="bi bi-file-excel"></i> Excel
    </a>
    <a href="{{ route('cfdi.exportar', ['tipo' => 'pdf']) }}" class="btn btn-sm btn-danger" title="Exportar a PDF">
        <i class="bi bi-file-pdf"></i> PDF
    </a>
</div>
<a href="{{ route('cfdi.resumen-fiscal', ['contribuyente_rfc' => request('contribuyente_rfc'), 'anio' => now()->year]) }}" class="btn btn-sm btn-info">
    <i class="bi bi-calculator"></i> Resumen Fiscal
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Filtros -->
        <form method="GET" action="{{ route('cfdi.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="uuid" class="form-label">UUID</label>
                <input type="text" class="form-control" id="uuid" name="uuid" 
                       value="{{ request('uuid') }}" placeholder="Buscar por UUID">
            </div>
            
            <div class="col-md-2">
                <label for="rfc_emisor" class="form-label">RFC Emisor</label>
                <input type="text" class="form-control" id="rfc_emisor" name="rfc_emisor" 
                       value="{{ request('rfc_emisor') }}" placeholder="Ej: AAA010101AAA">
            </div>
            
            <div class="col-md-2">
                <label for="rfc_receptor" class="form-label">RFC Receptor</label>
                <input type="text" class="form-control" id="rfc_receptor" name="rfc_receptor" 
                       value="{{ request('rfc_receptor') }}" placeholder="Ej: AAA010101AAA">
            </div>
            
            <div class="col-md-2">
                <label for="tipo_operacion" class="form-label">Tipo Operación</label>
                <select class="form-select" id="tipo_operacion" name="tipo_operacion">
                    <option value="">Todos</option>
                    <option value="adquisicion" {{ request('tipo_operacion') == 'adquisicion' ? 'selected' : '' }}>Adquisición</option>
                    <option value="enajenacion" {{ request('tipo_operacion') == 'enajenacion' ? 'selected' : '' }}>Enajenación</option>
                    <option value="servicio" {{ request('tipo_operacion') == 'servicio' ? 'selected' : '' }}>Servicio</option>
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
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="">Todos</option>
                    <option value="VIGENTE" {{ request('estado') == 'VIGENTE' ? 'selected' : '' }}>Vigente</option>
                    <option value="CANCELADO" {{ request('estado') == 'CANCELADO' ? 'selected' : '' }}>Cancelado</option>
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
                <a href="{{ route('cfdi.index') }}" class="btn btn-secondary">
                    <i class="bi bi-eraser"></i> Limpiar
                </a>
            </div>
        </form>
        
        <!-- Resumen -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6>Total CFDI</h6>
                        <h3>{{ $resumen['total'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6>Vigentes</h6>
                        <h3>{{ $resumen['vigentes'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6>Cancelados</h6>
                        <h3>{{ $resumen['cancelados'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6>Total (MXN)</h6>
                        <h3>${{ number_format($resumen['total_monto'] ?? 0, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla de CFDI -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>UUID</th>
                        <th>RFC Emisor</th>
                        <th>RFC Receptor</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cfdis as $cfdi)
                        <tr>
                            <td><small>{{ substr($cfdi['uuid'], 0, 8) }}...{{ substr($cfdi['uuid'], -4) }}</small></td>
                            <td>{{ $cfdi['rfc_emisor'] }}</td>
                            <td>{{ $cfdi['rfc_receptor'] }}</td>
                            <td>{{ ucfirst($cfdi['tipo_operacion']) }}</td>
                            <td>{{ $cfdi['fecha_emision'] }}</td>
                            <td>${{ number_format($cfdi['total'], 2) }}</td>
                            <td>
                                @if($cfdi['estado'] == 'VIGENTE')
                                    <span class="badge bg-success">Vigente</span>
                                @else
                                    <span class="badge bg-secondary">Cancelado</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('cfdi.show', $cfdi['id']) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($cfdi['estado'] == 'VIGENTE')
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="confirmarCancelacion({{ $cfdi['id'] }})" title="Cancelar">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay CFDI registrados</td>
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

<!-- Modal de cancelación -->
<div class="modal fade" id="cancelarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Cancelar CFDI</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="cancelarForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="motivo_cancelacion" class="form-label">Motivo de Cancelación</label>
                        <textarea class="form-control" id="motivo_cancelacion" name="motivo_cancelacion" 
                                  rows="3" required></textarea>
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

function confirmarCancelacion(id) {
    $('#cancelarForm').attr('action', `{{ url('cfdi') }}/${id}/cancelar`);
    new bootstrap.Modal(document.getElementById('cancelarModal')).show();
}
</script>
@endpush