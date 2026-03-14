@extends('layouts.app')

@section('title', 'Dictámenes')
@section('header', 'Dictámenes de Calidad')

@section('actions')
<a href="{{ route('dictamenes.create') }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-circle"></i> Nuevo Dictamen
</a>
<div class="btn-group">
    <a href="{{ route('dictamenes.exportar', ['tipo' => 'excel']) }}" class="btn btn-sm btn-success" title="Exportar a Excel">
        <i class="bi bi-file-excel"></i> Excel
    </a>
    <a href="{{ route('dictamenes.exportar', ['tipo' => 'pdf']) }}" class="btn btn-sm btn-danger" title="Exportar a PDF">
        <i class="bi bi-file-pdf"></i> PDF
    </a>
</div>
<a href="{{ route('dictamenes.estadisticas') }}?contribuyente_id={{ request('contribuyente_id') }}&anio={{ now()->year }}" class="btn btn-sm btn-info">
    <i class="bi bi-graph-up"></i> Estadísticas
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Filtros -->
        <form method="GET" action="{{ route('dictamenes.index') }}" class="row g-3 mb-4">
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
                <label for="folio" class="form-label">Folio</label>
                <input type="text" class="form-control" id="folio" name="folio" 
                       value="{{ request('folio') }}" placeholder="Buscar por folio">
            </div>
            
            <div class="col-md-2">
                <label for="numero_lote" class="form-label">Número de Lote</label>
                <input type="text" class="form-control" id="numero_lote" name="numero_lote" 
                       value="{{ request('numero_lote') }}" placeholder="Número de lote">
            </div>
            
            <div class="col-md-2">
                <label for="laboratorio_rfc" class="form-label">RFC Laboratorio</label>
                <input type="text" class="form-control" id="laboratorio_rfc" name="laboratorio_rfc" 
                       value="{{ request('laboratorio_rfc') }}" placeholder="Ej: ABC123456789">
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
                <label for="fecha_emision_inicio" class="form-label">Fecha Emisión Inicio</label>
                <input type="date" class="form-control datepicker" id="fecha_emision_inicio" 
                       name="fecha_emision_inicio" value="{{ request('fecha_emision_inicio') }}">
            </div>
            
            <div class="col-md-3">
                <label for="fecha_emision_fin" class="form-label">Fecha Emisión Fin</label>
                <input type="date" class="form-control datepicker" id="fecha_emision_fin" 
                       name="fecha_emision_fin" value="{{ request('fecha_emision_fin') }}">
            </div>
            
            <div class="col-md-2">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="">Todos</option>
                    <option value="VIGENTE" {{ request('estado') == 'VIGENTE' ? 'selected' : '' }}>Vigente</option>
                    <option value="CADUCADO" {{ request('estado') == 'CADUCADO' ? 'selected' : '' }}>Caducado</option>
                    <option value="CANCELADO" {{ request('estado') == 'CANCELADO' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="vigente" class="form-label">Vigente</label>
                <select class="form-select" id="vigente" name="vigente">
                    <option value="">Todos</option>
                    <option value="1" {{ request('vigente') == '1' ? 'selected' : '' }}>Vigentes</option>
                    <option value="0" {{ request('vigente') == '0' ? 'selected' : '' }}>No Vigentes</option>
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
                <a href="{{ route('dictamenes.index') }}" class="btn btn-secondary">
                    <i class="bi bi-eraser"></i> Limpiar
                </a>
            </div>
        </form>
        
        <!-- Resumen -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6>Total Dictámenes</h6>
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
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h6>Próximos a Vencer</h6>
                        <h3>{{ $resumen['proximos_vencer'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6>Vencidos</h6>
                        <h3>{{ $resumen['vencidos'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla de dictámenes -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Contribuyente</th>
                        <th>Producto</th>
                        <th>N° Lote</th>
                        <th>Fecha Emisión</th>
                        <th>Laboratorio</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dictamenes as $dictamen)
                        <tr>
                            <td>{{ $dictamen['folio'] }}</td>
                            <td>
                                {{ $dictamen['contribuyente']['razon_social'] ?? '' }}<br>
                                <small class="text-muted">{{ $dictamen['contribuyente']['rfc'] ?? '' }}</small>
                            </td>
                            <td>{{ $dictamen['producto']['nombre'] ?? $dictamen['producto_id'] }}</td>
                            <td>{{ $dictamen['numero_lote'] }}</td>
                            <td>{{ $dictamen['fecha_emision'] }}</td>
                            <td>
                                {{ $dictamen['laboratorio_nombre'] }}<br>
                                <small class="text-muted">{{ $dictamen['laboratorio_rfc'] }}</small>
                            </td>
                            <td>
                                @php
                                    $estadoClass = [
                                        'VIGENTE' => 'success',
                                        'CADUCADO' => 'warning',
                                        'CANCELADO' => 'secondary'
                                    ][$dictamen['estado']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $estadoClass }}">{{ $dictamen['estado'] }}</span>
                                @if($dictamen['vigente'] ?? true)
                                    <br><small class="text-success">Vigente</small>
                                @else
                                    <br><small class="text-danger">No Vigente</small>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('dictamenes.show', $dictamen['id']) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($dictamen['estado'] == 'VIGENTE')
                                        <a href="{{ route('dictamenes.edit', $dictamen['id']) }}" class="btn btn-sm btn-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="{{ route('dictamenes.verificar-vigencia', $dictamen['id']) }}" class="btn btn-sm btn-secondary" title="Verificar Vigencia">
                                            <i class="bi bi-check-circle"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay dictámenes registrados</td>
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