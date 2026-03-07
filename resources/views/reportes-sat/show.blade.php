@extends('layouts.app')

@section('title', 'Detalle del Reporte SAT')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Detalle del Reporte SAT #{{ $reporte['id'] }}</h6>
                <div>
                    @if($reporte['estado'] == 'generado')
                    <a href="{{ route('reportes-sat.edit', $reporte['id']) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <button type="button" class="btn btn-success btn-sm btn-firmar" data-id="{{ $reporte['id'] }}">
                        <i class="fas fa-signature"></i> Firmar
                    </button>
                    @endif
                    @if($reporte['estado'] == 'firmado')
                    <button type="button" class="btn btn-primary btn-sm btn-enviar" data-id="{{ $reporte['id'] }}">
                        <i class="fas fa-paper-plane"></i> Enviar
                    </button>
                    @endif
                    <a href="{{ route('reportes-sat.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Estado del reporte -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="alert {{ $reporte['estado'] == 'recibido' ? 'alert-success' : ($reporte['estado'] == 'error' ? 'alert-danger' : 'alert-info') }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas {{ $reporte['estado'] == 'recibido' ? 'fa-check-circle' : ($reporte['estado'] == 'error' ? 'fa-exclamation-triangle' : 'fa-clock') }}"></i>
                                    <strong>Estado:</strong> 
                                    @if($reporte['estado'] == 'generado')
                                        Generado
                                    @elseif($reporte['estado'] == 'firmado')
                                        Firmado
                                    @elseif($reporte['estado'] == 'enviado')
                                        Enviado
                                    @elseif($reporte['estado'] == 'recibido')
                                        Recibido por SAT
                                    @else
                                        Error en el envío
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Información General -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Información General
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Instalación</small>
                            <h6 class="mb-0">{{ $reporte['instalacion']['nombre'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Periodo</small>
                            <h6 class="mb-0">{{ ucfirst($reporte['periodo']) }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Año/Mes</small>
                            <h6 class="mb-0">{{ $reporte['anio'] }}-{{ str_pad($reporte['mes'], 2, '0', STR_PAD_LEFT) }}</h6>
                        </div>
                    </div>
                    @if($reporte['semana'])
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Semana</small>
                            <h6 class="mb-0">{{ $reporte['semana'] }}</h6>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Período -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-calendar-alt"></i> Período del Reporte
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Fecha Inicio</small>
                            <h6 class="mb-0">{{ $reporte['fecha_inicio'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Fecha Fin</small>
                            <h6 class="mb-0">{{ $reporte['fecha_fin'] }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Estadísticas -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-chart-bar"></i> Estadísticas de Registros
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="border p-3 rounded text-center">
                            <h2 class="text-primary mb-0">{{ $reporte['registros_generados'] }}</h2>
                            <small class="text-muted">Registros Generados</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded text-center">
                            <h2 class="text-success mb-0">{{ $reporte['registros_validos'] }}</h2>
                            <small class="text-muted">Registros Válidos</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded text-center">
                            <h2 class="text-danger mb-0">{{ $reporte['registros_invalidos'] }}</h2>
                            <small class="text-muted">Registros Inválidos</small>
                        </div>
                    </div>
                </div>
                
                <!-- Usuarios -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-users"></i> Usuarios Responsables
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Generado por</small>
                            <h6 class="mb-0">{{ $reporte['usuario_generacion']['name'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    @if($reporte['usuario_firma'])
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Firmado por</small>
                            <h6 class="mb-0">{{ $reporte['usuario_firma']['name'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    @endif
                    @if($reporte['usuario_envio'])
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Enviado por</small>
                            <h6 class="mb-0">{{ $reporte['usuario_envio']['name'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Observaciones -->
                @if($reporte['observaciones'])
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-comment"></i> Observaciones
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="border p-3 rounded">
                            <p class="mb-0">{{ $reporte['observaciones'] }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Estado -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Estado del Registro</small>
                            <h6 class="mb-0">
                                @if($reporte['activo'])
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </h6>
                        </div>
                    </div>
                </div>
                
                <!-- Fechas de registro -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <hr>
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> Creado: {{ $reporte['created_at'] ?? 'N/A' }} | 
                            Última actualización: {{ $reporte['updated_at'] ?? 'N/A' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Acciones adicionales -->
<div class="row mt-3">
    <div class="col-md-3">
        <div class="card bg-primary text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Descargar XML</h6>
                    </div>
                    <i class="fas fa-file-code fa-2x"></i>
                </div>
                <a href="{{ route('reportes-sat.descargar-xml', $reporte['id']) }}" class="btn btn-light btn-sm" target="_blank">
                    <i class="fas fa-download"></i> Descargar
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Descargar PDF</h6>
                    </div>
                    <i class="fas fa-file-pdf fa-2x"></i>
                </div>
                <a href="{{ route('reportes-sat.descargar-pdf', $reporte['id']) }}" class="btn btn-light btn-sm" target="_blank">
                    <i class="fas fa-download"></i> Descargar
                </a>
            </div>
        </div>
    </div>
    
    @if($reporte['estado'] == 'enviado' || $reporte['estado'] == 'recibido')
    <div class="col-md-3">
        <div class="card bg-info text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Acuse SAT</h6>
                    </div>
                    <i class="fas fa-file-signature fa-2x"></i>
                </div>
                <a href="{{ route('reportes-sat.acuse', $reporte['id']) }}" class="btn btn-light btn-sm" target="_blank">
                    <i class="fas fa-eye"></i> Ver
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modales -->
<div class="modal fade" id="firmarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="firmarForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Firmar Reporte SAT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de firmar este reporte?</p>
                    <p class="text-muted">Una vez firmado, podrá ser enviado al SAT.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Firmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="enviarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="enviarForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Enviar Reporte SAT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de enviar este reporte al SAT?</p>
                    <p class="text-muted">El reporte será transmitido electrónicamente.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Botón firmar
    $('.btn-firmar').click(function() {
        var id = $(this).data('id');
        var form = $('#firmarForm');
        form.attr('action', '{{ url("reportes-sat") }}/' + id + '/firmar');
        $('#firmarModal').modal('show');
    });

    // Botón enviar
    $('.btn-enviar').click(function() {
        var id = $(this).data('id');
        var form = $('#enviarForm');
        form.attr('action', '{{ url("reportes-sat") }}/' + id + '/enviar');
        $('#enviarModal').modal('show');
    });
});
</script>
@endpush