@extends('layouts.app')

@section('title', 'Detalle de Alarma')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Detalle de Alarma #{{ $alarma['id'] }}</h6>
                <div>
                    @if($alarma['estado'] == 'activa')
                    <button type="button" class="btn btn-success btn-sm btn-atender" data-id="{{ $alarma['id'] }}">
                        <i class="fas fa-check"></i> Atender
                    </button>
                    <button type="button" class="btn btn-warning btn-sm btn-descartar" data-id="{{ $alarma['id'] }}">
                        <i class="fas fa-times"></i> Descartar
                    </button>
                    @endif
                    <a href="{{ route('alarmas.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Estado -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="alert {{ $alarma['estado'] == 'activa' ? 'alert-danger' : ($alarma['estado'] == 'atendida' ? 'alert-success' : 'alert-secondary') }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas {{ $alarma['estado'] == 'activa' ? 'fa-exclamation-triangle' : ($alarma['estado'] == 'atendida' ? 'fa-check-circle' : 'fa-ban') }}"></i>
                                    <strong>Estado:</strong> 
                                    @if($alarma['estado'] == 'activa')
                                        Activa
                                    @elseif($alarma['estado'] == 'atendida')
                                        Atendida
                                    @else
                                        Descartada
                                    @endif
                                </div>
                                @if($alarma['estado'] != 'activa')
                                <div>
                                    <small>Atendida por: {{ $alarma['usuario_atencion']['name'] ?? 'N/A' }} el {{ $alarma['fecha_atencion'] }} {{ $alarma['hora_atencion'] }}</small>
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
                            <small class="text-muted">Fecha/Hora</small>
                            <h6 class="mb-0">{{ $alarma['fecha_alarma'] }} {{ $alarma['hora_alarma'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Tipo</small>
                            <h6 class="mb-0">
                                @switch($alarma['tipo_alarma'])
                                    @case('nivel')
                                        Nivel
                                        @break
                                    @case('temperatura')
                                        Temperatura
                                        @break
                                    @case('presion')
                                        Presión
                                        @break
                                    @case('flujo')
                                        Flujo
                                        @break
                                    @case('equipo')
                                        Equipo
                                        @break
                                    @case('comunicacion')
                                        Comunicación
                                        @break
                                @endswitch
                            </h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Severidad</small>
                            <h6 class="mb-0">
                                @if($alarma['severidad'] == 'alta')
                                    <span class="badge bg-danger">Alta</span>
                                @elseif($alarma['severidad'] == 'media')
                                    <span class="badge bg-warning">Media</span>
                                @else
                                    <span class="badge bg-info">Baja</span>
                                @endif
                            </h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Instalación</small>
                            <h6 class="mb-0">{{ $alarma['instalacion']['nombre'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Mensaje -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Mensaje</small>
                            <h5 class="mb-0">{{ $alarma['mensaje'] }}</h5>
                        </div>
                    </div>
                </div>
                
                <!-- Datos de la Alarma -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-chart-line"></i> Datos de la Alarma
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    @if($alarma['valor'])
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Valor</small>
                            <h6 class="mb-0">{{ $alarma['valor'] }} {{ $alarma['unidad'] ?? '' }}</h6>
                        </div>
                    </div>
                    @endif
                    
                    @if($alarma['umbral'])
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Umbral</small>
                            <h6 class="mb-0">{{ $alarma['umbral'] }} {{ $alarma['unidad'] ?? '' }}</h6>
                        </div>
                    </div>
                    @endif
                    
                    @if($alarma['condicion'])
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Condición</small>
                            <h6 class="mb-0">
                                @if($alarma['condicion'] == 'mayor')
                                    > 
                                @elseif($alarma['condicion'] == 'menor')
                                    < 
                                @elseif($alarma['condicion'] == 'igual')
                                    =
                                @elseif($alarma['condicion'] == 'entre')
                                    Entre
                                @endif
                            </h6>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Origen -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-map-marker-alt"></i> Origen
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    @if($alarma['tanque_id'])
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Tanque</small>
                            <h6 class="mb-0">{{ $alarma['tanque']['nombre'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    @endif
                    
                    @if($alarma['medidor_id'])
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Medidor</small>
                            <h6 class="mb-0">{{ $alarma['medidor']['nombre'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    @endif
                    
                    @if($alarma['dispensario_id'])
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Dispensario</small>
                            <h6 class="mb-0">{{ $alarma['dispensario']['nombre'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Observaciones de atención -->
                @if($alarma['observaciones'])
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
                            <p class="mb-0">{{ $alarma['observaciones'] }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Fechas de registro -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <hr>
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> Creado: {{ $alarma['created_at'] ?? 'N/A' }} | 
                            Última actualización: {{ $alarma['updated_at'] ?? 'N/A' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal atender alarma -->
<div class="modal fade" id="atenderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="atenderForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Atender Alarma</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="estado" value="atendida">
                    <input type="hidden" name="usuario_atencion" value="{{ session('user.id') }}">
                    <input type="hidden" name="fecha_atencion" value="{{ date('Y-m-d') }}">
                    <input type="hidden" name="hora_atencion" value="{{ date('H:i:s') }}">
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Atender</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal descartar alarma -->
<div class="modal fade" id="descartarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="descartarForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Descartar Alarma</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="estado" value="descartada">
                    <input type="hidden" name="usuario_atencion" value="{{ session('user.id') }}">
                    <input type="hidden" name="fecha_atencion" value="{{ date('Y-m-d') }}">
                    <input type="hidden" name="hora_atencion" value="{{ date('H:i:s') }}">
                    
                    <div class="mb-3">
                        <label for="observaciones_descartar" class="form-label">Motivo del descarte</label>
                        <textarea class="form-control" id="observaciones_descartar" name="observaciones" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Descartar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Botón atender
    $('.btn-atender').click(function() {
        var id = $(this).data('id');
        var form = $('#atenderForm');
        form.attr('action', '{{ url("alarmas") }}/' + id);
        $('#atenderModal').modal('show');
    });

    // Botón descartar
    $('.btn-descartar').click(function() {
        var id = $(this).data('id');
        var form = $('#descartarForm');
        form.attr('action', '{{ url("alarmas") }}/' + id);
        $('#descartarModal').modal('show');
    });
});
</script>
@endpush