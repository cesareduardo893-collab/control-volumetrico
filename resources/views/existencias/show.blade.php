@extends('layouts.app')

@section('title', 'Detalle de Existencia')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Detalle de Existencia #{{ $existencia['id'] }}</h6>
                <div>
                    @if($existencia['estado'] == 'pendiente')
                    <a href="{{ route('existencias.edit', $existencia['id']) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    @endif
                    <a href="{{ route('existencias.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Estado y Validación -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="alert {{ $existencia['estado'] == 'valida' ? 'alert-success' : ($existencia['estado'] == 'invalida' ? 'alert-danger' : 'alert-warning') }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas {{ $existencia['estado'] == 'valida' ? 'fa-check-circle' : ($existencia['estado'] == 'invalida' ? 'fa-times-circle' : 'fa-clock') }}"></i>
                                    <strong>Estado:</strong> 
                                    @if($existencia['estado'] == 'valida')
                                        Válida
                                    @elseif($existencia['estado'] == 'invalida')
                                        Inválida
                                    @else
                                        Pendiente de validación
                                    @endif
                                </div>
                                @if($existencia['estado'] == 'pendiente')
                                <div>
                                    <button type="button" class="btn btn-success btn-sm btn-validar" data-id="{{ $existencia['id'] }}">
                                        <i class="fas fa-check"></i> Validar
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Información del Tanque -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-oil-can"></i> Información del Tanque
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Tanque</small>
                            <h6 class="mb-0">{{ $existencia['tanque']['nombre'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Instalación</small>
                            <h6 class="mb-0">{{ $existencia['tanque']['instalacion']['nombre'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Producto</small>
                            <h6 class="mb-0">{{ $existencia['producto']['nombre'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Capacidad</small>
                            <h6 class="mb-0">{{ number_format($existencia['tanque']['capacidad'] ?? 0, 2) }} L</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Fecha y Hora -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-calendar-alt"></i> Fecha y Hora
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Fecha de Medición</small>
                            <h6 class="mb-0">{{ $existencia['fecha_medicion'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Hora de Medición</small>
                            <h6 class="mb-0">{{ $existencia['hora_medicion'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Método</small>
                            <h6 class="mb-0">{{ ucfirst($existencia['metodo_medicion']) }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Usuario</small>
                            <h6 class="mb-0">{{ $existencia['usuario']['name'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Datos de Medición -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-chart-bar"></i> Datos de Medición
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Volumen Bruto</small>
                            <h6 class="mb-0">{{ number_format($existencia['volumen_bruto'], 2) }} L</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Volumen Neto</small>
                            <h6 class="mb-0">{{ number_format($existencia['volumen_neto'], 2) }} L</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Temperatura</small>
                            <h6 class="mb-0">{{ $existencia['temperatura'] }} °C</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Densidad</small>
                            <h6 class="mb-0">{{ $existencia['densidad'] }} kg/m³</h6>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Factor de Corrección</small>
                            <h6 class="mb-0">{{ $existencia['factor_correccion'] }}</h6>
                        </div>
                    </div>
                    @if($existencia['nivel_agua'])
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Nivel de Agua</small>
                            <h6 class="mb-0">{{ $existencia['nivel_agua'] }} cm</h6>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Equipo Utilizado -->
                @if($existencia['medidor_id'])
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-tachometer-alt"></i> Equipo Utilizado
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Medidor</small>
                            <h6 class="mb-0">{{ $existencia['medidor']['nombre'] ?? 'N/A' }}</h6>
                            <small>{{ $existencia['medidor']['clave_medidor'] ?? '' }}</small>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Documentos Asociados -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-file-invoice"></i> Documentos Asociados
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    @if($existencia['cfdi_id'])
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">CFDI</small>
                            <h6 class="mb-0">
                                <a href="{{ route('cfdi.show', $existencia['cfdi_id']) }}">
                                    {{ $existencia['cfdi']['uuid'] ?? 'N/A' }}
                                </a>
                            </h6>
                        </div>
                    </div>
                    @endif
                    
                    @if($existencia['pedimento_id'])
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Pedimento</small>
                            <h6 class="mb-0">
                                <a href="{{ route('pedimentos.show', $existencia['pedimento_id']) }}">
                                    {{ $existencia['pedimento']['numero_pedimento'] ?? 'N/A' }}
                                </a>
                            </h6>
                        </div>
                    </div>
                    @endif
                    
                    @if(!$existencia['cfdi_id'] && !$existencia['pedimento_id'])
                    <div class="col-md-12">
                        <p class="text-muted">No hay documentos asociados</p>
                    </div>
                    @endif
                </div>
                
                <!-- Observaciones -->
                @if($existencia['observaciones'])
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
                            <p class="mb-0">{{ $existencia['observaciones'] }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Fechas de registro -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <hr>
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> Creado: {{ $existencia['created_at'] ?? 'N/A' }} | 
                            Última actualización: {{ $existencia['updated_at'] ?? 'N/A' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Acciones adicionales -->
@if($existencia['estado'] == 'valida')
<div class="row mt-3">
    @if(!$existencia['cfdi_id'])
    <div class="col-md-3">
        <div class="card bg-primary text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Asociar CFDI</h6>
                    </div>
                    <i class="fas fa-file-invoice fa-2x"></i>
                </div>
                <button type="button" class="btn btn-light btn-sm btn-asociar-cfdi" data-id="{{ $existencia['id'] }}" style="position: relative; z-index: 2;">
                    <i class="fas fa-plus"></i> Asociar
                </button>
            </div>
        </div>
    </div>
    @endif
    
    @if(!$existencia['pedimento_id'])
    <div class="col-md-3">
        <div class="card bg-success text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Asociar Pedimento</h6>
                    </div>
                    <i class="fas fa-file-alt fa-2x"></i>
                </div>
                <button type="button" class="btn btn-light btn-sm btn-asociar-pedimento" data-id="{{ $existencia['id'] }}" style="position: relative; z-index: 2;">
                    <i class="fas fa-plus"></i> Asociar
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
@endif

<!-- Modales -->
<div class="modal fade" id="validarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="validarForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Validar Existencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de validar esta existencia?</p>
                    <p class="text-muted">Una vez validada, no podrá ser modificada.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Validar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="asociarCfdiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="asociarCfdiForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Asociar CFDI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="cfdi_id" class="form-label">Seleccionar CFDI</label>
                        <select class="form-select select2" id="cfdi_id" name="cfdi_id" required>
                            <option value="">Buscar CFDI...</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Asociar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="asociarPedimentoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="asociarPedimentoForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Asociar Pedimento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="pedimento_id" class="form-label">Seleccionar Pedimento</label>
                        <select class="form-select select2" id="pedimento_id" name="pedimento_id" required>
                            <option value="">Buscar pedimento...</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Asociar</button>
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
        form.attr('action', '{{ url("existencias") }}/' + id + '/validar');
        $('#validarModal').modal('show');
    });
    
    // Botones asociar CFDI
    $('.btn-asociar-cfdi').click(function() {
        var id = $(this).data('id');
        var form = $('#asociarCfdiForm');
        form.attr('action', '{{ url("existencias") }}/' + id + '/asociar-cfdi');
        
        // Cargar CFDI disponibles
        $.get('/api/cfdi/disponibles', function(data) {
            var select = $('#cfdi_id');
            select.empty().append('<option value="">Seleccione un CFDI...</option>');
            
            $.each(data, function(key, cfdi) {
                select.append('<option value="' + cfdi.id + '">' + cfdi.uuid + ' - $' + cfdi.total + '</option>');
            });
        });
        
        $('#asociarCfdiModal').modal('show');
    });
    
    // Botones asociar Pedimento
    $('.btn-asociar-pedimento').click(function() {
        var id = $(this).data('id');
        var form = $('#asociarPedimentoForm');
        form.attr('action', '{{ url("existencias") }}/' + id + '/asociar-pedimento');
        
        // Cargar pedimentos disponibles
        $.get('/api/pedimentos/disponibles', function(data) {
            var select = $('#pedimento_id');
            select.empty().append('<option value="">Seleccione un pedimento...</option>');
            
            $.each(data, function(key, pedimento) {
                select.append('<option value="' + pedimento.id + '">' + pedimento.numero_pedimento + ' - ' + pedimento.aduana + '</option>');
            });
        });
        
        $('#asociarPedimentoModal').modal('show');
    });
});
</script>
@endpush