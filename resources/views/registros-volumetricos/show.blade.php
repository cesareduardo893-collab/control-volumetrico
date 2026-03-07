@extends('layouts.app')

@section('title', 'Detalle del Registro Volumétrico')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Detalle del Registro Volumétrico #{{ $registro['id'] }}</h6>
                <div>
                    @if($registro['estado'] == 'registrado')
                    <a href="{{ route('registros-volumetricos.edit', $registro['id']) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    @endif
                    <a href="{{ route('registros-volumetricos.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Estado y Validación -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="alert {{ $registro['estado'] == 'validado' ? 'alert-success' : ($registro['estado'] == 'anulado' ? 'alert-danger' : 'alert-warning') }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas {{ $registro['estado'] == 'validado' ? 'fa-check-circle' : ($registro['estado'] == 'anulado' ? 'fa-ban' : 'fa-clock') }}"></i>
                                    <strong>Estado:</strong> 
                                    @if($registro['estado'] == 'validado')
                                        Validado
                                    @elseif($registro['estado'] == 'anulado')
                                        Anulado - {{ $registro['motivo_anulacion'] ?? 'Sin motivo' }}
                                    @else
                                        Pendiente de validación
                                    @endif
                                </div>
                                @if($registro['estado'] == 'registrado')
                                <div>
                                    <button type="button" class="btn btn-success btn-sm btn-validar" data-id="{{ $registro['id'] }}">
                                        <i class="fas fa-check"></i> Validar
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm btn-anular" data-id="{{ $registro['id'] }}">
                                        <i class="fas fa-ban"></i> Anular
                                    </button>
                                </div>
                                @endif
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
                            <h6 class="mb-0">{{ $registro['instalacion']['nombre'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Tanque</small>
                            <h6 class="mb-0">{{ $registro['tanque']['nombre'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Producto</small>
                            <h6 class="mb-0">{{ $registro['producto']['nombre'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Tipo de Movimiento</small>
                            <h6 class="mb-0">
                                @switch($registro['tipo_movimiento'])
                                    @case('entrada')
                                        <span class="badge bg-success">Entrada</span>
                                        @break
                                    @case('salida')
                                        <span class="badge bg-danger">Salida</span>
                                        @break
                                    @case('trasiego')
                                        <span class="badge bg-info">Trasiego</span>
                                        @break
                                    @case('ajuste')
                                        <span class="badge bg-warning">Ajuste</span>
                                        @break
                                @endswitch
                            </h6>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Fecha</small>
                            <h6 class="mb-0">{{ $registro['fecha_movimiento'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Hora</small>
                            <h6 class="mb-0">{{ $registro['hora_movimiento'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Usuario</small>
                            <h6 class="mb-0">{{ $registro['usuario']['name'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Datos Volumétricos -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-chart-bar"></i> Datos Volumétricos
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Volumen Bruto</small>
                            <h6 class="mb-0">{{ number_format($registro['volumen_bruto'], 2) }} L</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Volumen Neto</small>
                            <h6 class="mb-0">{{ number_format($registro['volumen_neto'], 2) }} L</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Temperatura</small>
                            <h6 class="mb-0">{{ $registro['temperatura'] }} °C</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Densidad</small>
                            <h6 class="mb-0">{{ $registro['densidad'] }} kg/m³</h6>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Factor de Corrección</small>
                            <h6 class="mb-0">{{ $registro['factor_correccion'] }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Equipos Asociados -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-cog"></i> Equipos Asociados
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    @if($registro['medidor_id'])
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Medidor</small>
                            <h6 class="mb-0">{{ $registro['medidor']['nombre'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    @endif
                    
                    @if($registro['dispensario_id'])
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Dispensario</small>
                            <h6 class="mb-0">{{ $registro['dispensario']['nombre'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    @endif
                    
                    @if($registro['manguera_id'])
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Manguera</small>
                            <h6 class="mb-0">{{ $registro['manguera']['clave_manguera'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Documentos Asociados -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-file-invoice"></i> Documentos Asociados
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    @if($registro['cfdi_id'])
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">CFDI</small>
                            <h6 class="mb-0">
                                <a href="{{ route('cfdi.show', $registro['cfdi_id']) }}">
                                    {{ $registro['cfdi']['uuid'] ?? 'N/A' }}
                                </a>
                            </h6>
                        </div>
                    </div>
                    @endif
                    
                    @if($registro['pedimento_id'])
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Pedimento</small>
                            <h6 class="mb-0">
                                <a href="{{ route('pedimentos.show', $registro['pedimento_id']) }}">
                                    {{ $registro['pedimento']['numero_pedimento'] ?? 'N/A' }}
                                </a>
                            </h6>
                        </div>
                    </div>
                    @endif
                    
                    @if($registro['dictamen_id'])
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Dictamen</small>
                            <h6 class="mb-0">
                                <a href="{{ route('dictamenes.show', $registro['dictamen_id']) }}">
                                    {{ $registro['dictamen']['numero_dictamen'] ?? 'N/A' }}
                                </a>
                            </h6>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Observaciones -->
                @if($registro['observaciones'])
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
                            <p class="mb-0">{{ $registro['observaciones'] }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Fechas de registro -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <hr>
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> Creado: {{ $registro['created_at'] ?? 'N/A' }} | 
                            Última actualización: {{ $registro['updated_at'] ?? 'N/A' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Botones de acción adicionales -->
<div class="row mt-3">
    <div class="col-md-3">
        <div class="card bg-primary text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">PDF</h6>
                    </div>
                    <i class="fas fa-file-pdf fa-2x"></i>
                </div>
                <a href="{{ route('registros-volumetricos.pdf', $registro['id']) }}" class="text-white stretched-link" target="_blank"></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">XML</h6>
                    </div>
                    <i class="fas fa-file-code fa-2x"></i>
                </div>
                <a href="{{ route('registros-volumetricos.xml', $registro['id']) }}" class="text-white stretched-link"></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Resumen Diario</h6>
                    </div>
                    <i class="fas fa-chart-line fa-2x"></i>
                </div>
                <a href="{{ route('registros-volumetricos.resumen-diario', $registro['id']) }}" class="text-white stretched-link"></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Estadísticas</h6>
                    </div>
                    <i class="fas fa-chart-pie fa-2x"></i>
                </div>
                <a href="{{ route('registros-volumetricos.estadisticas-mensuales', $registro['id']) }}" class="text-white stretched-link"></a>
            </div>
        </div>
    </div>
</div>

<!-- Modales -->
<div class="modal fade" id="validarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="validarForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Validar Registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de validar este registro volumétrico?</p>
                    <p class="text-muted">Una vez validado, no podrá ser modificado.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Validar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="anularModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="anularForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Anular Registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="motivo_anulacion" class="form-label">Motivo de anulación <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="motivo_anulacion" name="motivo_anulacion" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Anular</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Botón validar
    $('.btn-validar').click(function() {
        var id = $(this).data('id');
        var form = $('#validarForm');
        form.attr('action', '{{ url("registros-volumetricos") }}/' + id + '/validar');
        $('#validarModal').modal('show');
    });
    
    // Botón anular
    $('.btn-anular').click(function() {
        var id = $(this).data('id');
        var form = $('#anularForm');
        form.attr('action', '{{ url("registros-volumetricos") }}/' + id + '/cancelar');
        $('#anularModal').modal('show');
    });
});
</script>
@endpush