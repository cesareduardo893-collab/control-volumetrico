@extends('layouts.app')

@section('title', 'Detalle del Pedimento')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Detalle del Pedimento: {{ $pedimento['numero_pedimento'] }}</h6>
                <div>
                    @if($pedimento['estado'] == 'activo')
                    <a href="{{ route('pedimentos.edit', $pedimento['id']) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    @endif
                    <a href="{{ route('pedimentos.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
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
                            <small class="text-muted">Número de Pedimento</small>
                            <h6 class="mb-0">{{ $pedimento['numero_pedimento'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Aduana</small>
                            <h6 class="mb-0">{{ $pedimento['aduana'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Patente</small>
                            <h6 class="mb-0">{{ $pedimento['patente'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Ejercicio</small>
                            <h6 class="mb-0">{{ $pedimento['ejercicio'] }}</h6>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Contribuyente</small>
                            <h6 class="mb-0">{{ $pedimento['contribuyente']['razon_social'] ?? 'N/A' }}</h6>
                            <small>{{ $pedimento['contribuyente']['rfc'] ?? '' }}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Producto</small>
                            <h6 class="mb-0">{{ $pedimento['producto']['nombre'] ?? 'N/A' }}</h6>
                            <small>{{ $pedimento['producto']['clave_producto'] ?? '' }}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Fecha de Importación</small>
                            <h6 class="mb-0">{{ $pedimento['fecha_importacion'] }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Datos de Importación -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-ship"></i> Datos de Importación
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Tipo de Cambio</small>
                            <h6 class="mb-0">{{ number_format($pedimento['tipo_cambio'], 4) }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Peso Bruto</small>
                            <h6 class="mb-0">{{ number_format($pedimento['peso_bruto'], 2) }} kg</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Peso Neto</small>
                            <h6 class="mb-0">{{ number_format($pedimento['peso_neto'], 2) }} kg</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Volumen</small>
                            <h6 class="mb-0">{{ number_format($pedimento['volumen'], 2) }} L</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Cantidades -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-chart-bar"></i> Cantidades
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Cantidad Importada</small>
                            <h6 class="mb-0">{{ number_format($pedimento['cantidad_importada'], 2) }} L</h6>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Cantidad Despachada</small>
                            <h6 class="mb-0">{{ number_format($pedimento['cantidad_despachada'], 2) }} L</h6>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Cantidad Pendiente</small>
                            <h6 class="mb-0">{{ number_format($pedimento['cantidad_pendiente'], 2) }} L</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Estado -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Estado</small>
                            <h6 class="mb-0">
                                @if($pedimento['estado'] == 'activo')
                                    <span class="badge bg-success">Activo</span>
                                @elseif($pedimento['estado'] == 'liquidado')
                                    <span class="badge bg-info">Liquidado</span>
                                @else
                                    <span class="badge bg-danger">Cancelado</span>
                                @endif
                            </h6>
                        </div>
                    </div>
                </div>
                
                <!-- Observaciones -->
                @if($pedimento['observaciones'])
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
                            <p class="mb-0">{{ $pedimento['observaciones'] }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Registros Asociados -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-link"></i> Registros Volumétricos Asociados
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Fecha</th>
                                        <th>Instalación</th>
                                        <th>Tanque</th>
                                        <th>Volumen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pedimento['registros_volumetricos'] ?? [] as $registro)
                                    <tr>
                                        <td>{{ $registro['id'] }}</td>
                                        <td>{{ $registro['fecha_movimiento'] }}</td>
                                        <td>{{ $registro['instalacion']['nombre'] ?? 'N/A' }}</td>
                                        <td>{{ $registro['tanque']['nombre'] ?? 'N/A' }}</td>
                                        <td class="text-end">{{ number_format($registro['volumen_neto'], 2) }} L</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No hay registros asociados</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Fechas de registro -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <hr>
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> Creado: {{ $pedimento['created_at'] ?? 'N/A' }} | 
                            Última actualización: {{ $pedimento['updated_at'] ?? 'N/A' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Botones de acción adicionales -->
@if($pedimento['cantidad_pendiente'] > 0 && $pedimento['estado'] == 'activo')
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card bg-success text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Asociar a Registro Volumétrico</h6>
                        <small>Cantidad pendiente: {{ number_format($pedimento['cantidad_pendiente'], 2) }} L</small>
                    </div>
                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#asociarModal">
                        <i class="fas fa-plus"></i> Asociar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal asociar a registro -->
<div class="modal fade" id="asociarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="asociarForm" method="POST" action="{{ route('pedimentos.asociar-registro', $pedimento['id']) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Asociar a Registro Volumétrico</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="registro_volumetrico_id" class="form-label">Seleccionar Registro</label>
                        <select class="form-select select2" id="registro_volumetrico_id" name="registro_volumetrico_id" required>
                            <option value="">Buscar registro...</option>
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
    // Cargar registros disponibles
    $('#asociarModal').on('shown.bs.modal', function() {
        $.get('/api/registros-volumetricos/disponibles', function(data) {
            var select = $('#registro_volumetrico_id');
            select.empty().append('<option value="">Seleccione un registro...</option>');
            
            $.each(data, function(key, registro) {
                select.append('<option value="' + registro.id + '">' + 
                    registro.fecha_movimiento + ' - ' + registro.tanque.nombre + ' (' + registro.volumen_neto + ' L)</option>');
            });
        });
    });
});
</script>
@endpush