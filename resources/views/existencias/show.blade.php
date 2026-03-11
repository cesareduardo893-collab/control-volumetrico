@extends('layouts.app')

@section('title', 'Detalle de Existencia')
@section('header', 'Detalle del Registro de Existencia')

@section('actions')
@if($existencia['estado'] == 'PENDIENTE')
    <button type="button" class="btn btn-sm btn-success" onclick="confirmarValidacion()">
        <i class="bi bi-check-circle"></i> Validar
    </button>
@endif
<a href="{{ route('existencias.index') }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Información General</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Número de Registro:</th>
                        <td>{{ $existencia['numero_registro'] }}</td>
                    </tr>
                    <tr>
                        <th>Fecha:</th>
                        <td>{{ $existencia['fecha'] }}</td>
                    </tr>
                    <tr>
                        <th>Hora:</th>
                        <td>{{ $existencia['hora'] }}</td>
                    </tr>
                    <tr>
                        <th>Tipo de Registro:</th>
                        <td><span class="badge bg-info">{{ $existencia['tipo_registro'] }}</span></td>
                    </tr>
                    <tr>
                        <th>Tipo de Movimiento:</th>
                        <td>{{ $existencia['tipo_movimiento'] }}</td>
                    </tr>
                    <tr>
                        <th>Estado:</th>
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
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Tanque y Producto</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Tanque:</th>
                        <td>
                            @if(isset($existencia['tanque']))
                                <strong>{{ $existencia['tanque']['identificador'] }}</strong><br>
                                <small class="text-muted">{{ $existencia['tanque']['instalacion']['nombre'] ?? '' }}</small>
                            @else
                                {{ $existencia['tanque_id'] }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Producto:</th>
                        <td>
                            @if(isset($existencia['producto']))
                                <strong>{{ $existencia['producto']['nombre'] }}</strong><br>
                                <small class="text-muted">{{ $existencia['producto']['clave_sat'] }}</small>
                            @else
                                {{ $existencia['producto_id'] }}
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Volúmenes</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Volumen Medido:</th>
                        <td><strong>{{ number_format($existencia['volumen_medido'], 3) }} L</strong></td>
                    </tr>
                    <tr>
                        <th>Volumen Corregido:</th>
                        <td><strong>{{ number_format($existencia['volumen_corregido'], 3) }} L</strong></td>
                    </tr>
                    <tr>
                        <th>Volumen Disponible:</th>
                        <td><strong>{{ number_format($existencia['volumen_disponible'], 3) }} L</strong></td>
                    </tr>
                    <tr>
                        <th>Volumen de Agua:</th>
                        <td>{{ number_format($existencia['volumen_agua'], 3) }} L</td>
                    </tr>
                    <tr>
                        <th>Volumen de Sedimentos:</th>
                        <td>{{ number_format($existencia['volumen_sedimentos'], 3) }} L</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="card-title mb-0">Condiciones de Medición</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Temperatura:</th>
                        <td>{{ number_format($existencia['temperatura'], 1) }} °C</td>
                    </tr>
                    <tr>
                        <th>Densidad:</th>
                        <td>{{ number_format($existencia['densidad'] ?? 0, 4) }} kg/L</td>
                    </tr>
                    @if(isset($existencia['presion']))
                        <tr>
                            <th>Presión:</th>
                            <td>{{ $existencia['presion'] }} psi</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

@if(!empty($existencia['observaciones']))
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">Observaciones</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $existencia['observaciones'] }}</p>
            </div>
        </div>
    </div>
</div>
@endif

@if(!empty($existencia['validaciones']))
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Historial de Validaciones</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th>Acción</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($existencia['validaciones'] as $validacion)
                                <tr>
                                    <td>{{ $validacion['fecha'] }}</td>
                                    <td>{{ $validacion['usuario']['nombres'] ?? '' }} {{ $validacion['usuario']['apellidos'] ?? '' }}</td>
                                    <td>
                                        @if($validacion['accion'] == 'VALIDAR')
                                            <span class="badge bg-success">Validado</span>
                                        @else
                                            <span class="badge bg-warning">Revisión</span>
                                        @endif
                                    </td>
                                    <td>{{ $validacion['observaciones'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal de validación -->
<div class="modal fade" id="validarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Validar Existencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('existencias.validar', $existencia['id']) }}">
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
function confirmarValidacion() {
    new bootstrap.Modal(document.getElementById('validarModal')).show();
}
</script>
@endpush