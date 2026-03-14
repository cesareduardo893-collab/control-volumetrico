@extends('layouts.app')

@section('title', 'Certificados de Verificación')
@section('header', 'Certificados de Verificación')

@section('actions')
<a href="{{ route('certificados-verificacion.create') }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-circle"></i> Nuevo Certificado
</a>
<div class="btn-group">
    <a href="{{ route('certificados-verificacion.exportar', ['tipo' => 'excel']) }}" class="btn btn-sm btn-success" title="Exportar a Excel">
        <i class="bi bi-file-excel"></i> Excel
    </a>
    <a href="{{ route('certificados-verificacion.exportar', ['tipo' => 'pdf']) }}" class="btn btn-sm btn-danger" title="Exportar a PDF">
        <i class="bi bi-file-pdf"></i> PDF
    </a>
</div>
<a href="{{ route('certificados-verificacion.estadisticas') }}?contribuyente_id={{ request('contribuyente_id') }}&anio={{ now()->year }}" class="btn btn-sm btn-info">
    <i class="bi bi-graph-up"></i> Estadísticas
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Filtros -->
        <form method="GET" action="{{ route('certificados-verificacion.index') }}" class="row g-3 mb-4">
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
                <label for="proveedor_rfc" class="form-label">RFC Proveedor</label>
                <input type="text" class="form-control" id="proveedor_rfc" name="proveedor_rfc" 
                       value="{{ request('proveedor_rfc') }}" placeholder="Ej: ABC123456789">
            </div>
            
            <div class="col-md-2">
                <label for="resultado" class="form-label">Resultado</label>
                <select class="form-select" id="resultado" name="resultado">
                    <option value="">Todos</option>
                    <option value="acreditado" {{ request('resultado') == 'acreditado' ? 'selected' : '' }}>Acreditado</option>
                    <option value="no_acreditado" {{ request('resultado') == 'no_acreditado' ? 'selected' : '' }}>No Acreditado</option>
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
                <label for="vigente" class="form-label">Vigente</label>
                <select class="form-select" id="vigente" name="vigente">
                    <option value="">Todos</option>
                    <option value="1" {{ request('vigente') == '1' ? 'selected' : '' }}>Vigentes</option>
                    <option value="0" {{ request('vigente') == '0' ? 'selected' : '' }}>No Vigentes</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="requiere_verificacion_extraordinaria" class="form-label">Verif. Extraord.</label>
                <select class="form-select" id="requiere_verificacion_extraordinaria" name="requiere_verificacion_extraordinaria">
                    <option value="">Todos</option>
                    <option value="1" {{ request('requiere_verificacion_extraordinaria') == '1' ? 'selected' : '' }}>Requiere</option>
                    <option value="0" {{ request('requiere_verificacion_extraordinaria') == '0' ? 'selected' : '' }}>No Requiere</option>
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
                <a href="{{ route('certificados-verificacion.index') }}" class="btn btn-secondary">
                    <i class="bi bi-eraser"></i> Limpiar
                </a>
            </div>
        </form>
        
        <!-- Tabla de certificados -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Contribuyente</th>
                        <th>Proveedor</th>
                        <th>Fecha Emisión</th>
                        <th>Resultado</th>
                        <th>Vigente</th>
                        <th>Verif. Extraord.</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificados as $certificado)
                        <tr>
                            <td>{{ $certificado['folio'] }}</td>
                            <td>
                                {{ $certificado['contribuyente']['razon_social'] ?? '' }}<br>
                                <small class="text-muted">{{ $certificado['contribuyente']['rfc'] ?? '' }}</small>
                            </td>
                            <td>
                                {{ $certificado['proveedor_nombre'] }}<br>
                                <small class="text-muted">{{ $certificado['proveedor_rfc'] }}</small>
                            </td>
                            <td>{{ $certificado['fecha_emision'] }}</td>
                            <td>
                                @if($certificado['resultado'] == 'acreditado')
                                    <span class="badge bg-success">Acreditado</span>
                                @else
                                    <span class="badge bg-danger">No Acreditado</span>
                                @endif
                            </td>
                            <td>
                                @if($certificado['vigente'] ?? true)
                                    <span class="badge bg-success">Vigente</span>
                                @else
                                    <span class="badge bg-secondary">No Vigente</span>
                                @endif
                            </td>
                            <td>
                                @if($certificado['requiere_verificacion_extraordinaria'] ?? false)
                                    <span class="badge bg-warning">Requiere</span>
                                @else
                                    <span class="badge bg-info">No Requiere</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('certificados-verificacion.show', $certificado['id']) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('certificados-verificacion.edit', $certificado['id']) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('certificados-verificacion.verificar-vigencia', $certificado['id']) }}" class="btn btn-sm btn-secondary" title="Verificar Vigencia">
                                        <i class="bi bi-check-circle"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay certificados registrados</td>
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