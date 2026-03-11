@extends('layouts.app')

@section('title', 'Reportes SAT')
@section('header', 'Reportes para el SAT')

@section('actions')
<a href="{{ route('reportes-sat.create') }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-circle"></i> Nuevo Reporte
</a>
<a href="{{ route('reportes-sat.historial-envios', request('instalacion_id')) }}?anio={{ now()->year }}" class="btn btn-sm btn-info">
    <i class="bi bi-clock-history"></i> Historial de Envíos
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Filtros -->
        <form method="GET" action="{{ route('reportes-sat.index') }}" class="row g-3 mb-4">
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
                <label for="folio" class="form-label">Folio</label>
                <input type="text" class="form-control" id="folio" name="folio" 
                       value="{{ request('folio') }}" placeholder="Buscar por folio">
            </div>
            
            <div class="col-md-2">
                <label for="periodo" class="form-label">Período (AAAAMM)</label>
                <input type="text" class="form-control" id="periodo" name="periodo" 
                       value="{{ request('periodo') }}" placeholder="Ej: 202401" maxlength="7">
            </div>
            
            <div class="col-md-2">
                <label for="tipo_reporte" class="form-label">Tipo</label>
                <select class="form-select" id="tipo_reporte" name="tipo_reporte">
                    <option value="">Todos</option>
                    <option value="MENSUAL" {{ request('tipo_reporte') == 'MENSUAL' ? 'selected' : '' }}>Mensual</option>
                    <option value="ANUAL" {{ request('tipo_reporte') == 'ANUAL' ? 'selected' : '' }}>Anual</option>
                    <option value="ESPECIAL" {{ request('tipo_reporte') == 'ESPECIAL' ? 'selected' : '' }}>Especial</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label for="fecha_generacion_inicio" class="form-label">Fecha Generación Inicio</label>
                <input type="date" class="form-control datepicker" id="fecha_generacion_inicio" 
                       name="fecha_generacion_inicio" value="{{ request('fecha_generacion_inicio') }}">
            </div>
            
            <div class="col-md-3">
                <label for="fecha_generacion_fin" class="form-label">Fecha Generación Fin</label>
                <input type="date" class="form-control datepicker" id="fecha_generacion_fin" 
                       name="fecha_generacion_fin" value="{{ request('fecha_generacion_fin') }}">
            </div>
            
            <div class="col-md-2">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="">Todos</option>
                    <option value="PENDIENTE" {{ request('estado') == 'PENDIENTE' ? 'selected' : '' }}>Pendiente</option>
                    <option value="GENERADO" {{ request('estado') == 'GENERADO' ? 'selected' : '' }}>Generado</option>
                    <option value="FIRMADO" {{ request('estado') == 'FIRMADO' ? 'selected' : '' }}>Firmado</option>
                    <option value="ENVIADO" {{ request('estado') == 'ENVIADO' ? 'selected' : '' }}>Enviado</option>
                    <option value="ACEPTADO" {{ request('estado') == 'ACEPTADO' ? 'selected' : '' }}>Aceptado</option>
                    <option value="RECHAZADO" {{ request('estado') == 'RECHAZADO' ? 'selected' : '' }}>Rechazado</option>
                    <option value="ERROR" {{ request('estado') == 'ERROR' ? 'selected' : '' }}>Error</option>
                    <option value="REQUIERE_REENVIO" {{ request('estado') == 'REQUIERE_REENVIO' ? 'selected' : '' }}>Requiere Reenvío</option>
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
                <a href="{{ route('reportes-sat.index') }}" class="btn btn-secondary">
                    <i class="bi bi-eraser"></i> Limpiar
                </a>
            </div>
        </form>
        
        <!-- Resumen -->
        <div class="row mb-4">
            <div class="col-md-2">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h6>Total</h6>
                        <h3>{{ $resumen['total'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h6>Aceptados</h6>
                        <h3>{{ $resumen['aceptados'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h6>Pendientes</h6>
                        <h3>{{ $resumen['pendientes'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h6>Enviados</h6>
                        <h3>{{ $resumen['enviados'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <h6>Rechazados</h6>
                        <h3>{{ $resumen['rechazados'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-secondary text-white">
                    <div class="card-body text-center">
                        <h6>Errores</h6>
                        <h3>{{ $resumen['errores'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla de reportes -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Instalación</th>
                        <th>Período</th>
                        <th>Tipo</th>
                        <th>Fecha Generación</th>
                        <th>Usuario</th>
                        <th>Estado</th>
                        <th>Fecha Envío</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reportes as $reporte)
                        <tr>
                            <td>{{ $reporte['folio'] }}</td>
                            <td>
                                @if(isset($reporte['instalacion']))
                                    {{ $reporte['instalacion']['nombre'] }}
                                @else
                                    {{ $reporte['instalacion_id'] }}
                                @endif
                            </td>
                            <td>{{ substr($reporte['periodo'], 0, 4) }}-{{ substr($reporte['periodo'], 4, 2) }}</td>
                            <td>
                                <span class="badge bg-info">{{ $reporte['tipo_reporte'] }}</span>
                            </td>
                            <td>{{ $reporte['fecha_generacion'] }}</td>
                            <td>
                                @if(isset($reporte['usuario_genera']))
                                    {{ $reporte['usuario_genera']['nombres'] }} {{ $reporte['usuario_genera']['apellidos'] }}
                                @else
                                    {{ $reporte['usuario_genera_id'] }}
                                @endif
                            </td>
                            <td>
                                @php
                                    $estadoClass = [
                                        'ACEPTADO' => 'success',
                                        'ENVIADO' => 'info',
                                        'GENERADO' => 'primary',
                                        'FIRMADO' => 'secondary',
                                        'PENDIENTE' => 'warning',
                                        'RECHAZADO' => 'danger',
                                        'ERROR' => 'danger',
                                        'REQUIERE_REENVIO' => 'warning'
                                    ][$reporte['estado']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $estadoClass }}">{{ $reporte['estado'] }}</span>
                            </td>
                            <td>{{ $reporte['fecha_envio'] ?? 'No enviado' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('reportes-sat.show', $reporte['id']) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    @if(in_array($reporte['estado'], ['GENERADO', 'PENDIENTE']))
                                        <button type="button" class="btn btn-sm btn-primary" 
                                                onclick="confirmarEnvio({{ $reporte['id'] }})" title="Enviar">
                                            <i class="bi bi-send"></i>
                                        </button>
                                    @endif
                                    
                                    @if($reporte['estado'] == 'GENERADO')
                                        <button type="button" class="btn btn-sm btn-warning" 
                                                onclick="confirmarFirma({{ $reporte['id'] }})" title="Firmar">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    @endif
                                    
                                    @if(in_array($reporte['estado'], ['PENDIENTE', 'GENERADO', 'ERROR', 'RECHAZADO']))
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="confirmarCancelacion({{ $reporte['id'] }})" title="Cancelar">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No hay reportes SAT registrados</td>
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

<!-- Modal de envío -->
<div class="modal fade" id="enviarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Enviar Reporte al SAT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="enviarForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fecha_envio" class="form-label">Fecha de Envío</label>
                        <input type="date" class="form-control datepicker" id="fecha_envio" 
                               name="fecha_envio" value="{{ now()->toDateString() }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Enviar Reporte</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de firma -->
<div class="modal fade" id="firmarModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Firmar Reporte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="firmarForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="cadena_original" class="form-label">Cadena Original</label>
                        <textarea class="form-control" id="cadena_original" name="cadena_original" 
                                  rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="sello_digital" class="form-label">Sello Digital</label>
                        <textarea class="form-control" id="sello_digital" name="sello_digital" 
                                  rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="certificado_sat" class="form-label">Certificado SAT</label>
                        <textarea class="form-control" id="certificado_sat" name="certificado_sat" 
                                  rows="3" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="folio_firma" class="form-label">Folio de Firma</label>
                            <input type="text" class="form-control" id="folio_firma" name="folio_firma" 
                                   value="{{ \Illuminate\Support\Str::uuid() }}" readonly>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="fecha_firma" class="form-label">Fecha de Firma</label>
                            <input type="date" class="form-control datepicker" id="fecha_firma" 
                                   name="fecha_firma" value="{{ now()->toDateString() }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-warning">Firmar Reporte</button>
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
                <h5 class="modal-title">Cancelar Reporte</h5>
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
                    <button type="submit" class="btn btn-danger">Cancelar Reporte</button>
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

function confirmarEnvio(id) {
    $('#enviarForm').attr('action', `{{ url('reportes-sat') }}/${id}/enviar`);
    new bootstrap.Modal(document.getElementById('enviarModal')).show();
}

function confirmarFirma(id) {
    $('#firmarForm').attr('action', `{{ url('reportes-sat') }}/${id}/firmar`);
    new bootstrap.Modal(document.getElementById('firmarModal')).show();
}

function confirmarCancelacion(id) {
    $('#cancelarForm').attr('action', `{{ url('reportes-sat') }}/${id}/cancelar`);
    new bootstrap.Modal(document.getElementById('cancelarModal')).show();
}
</script>
@endpush