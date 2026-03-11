@extends('layouts.app')

@section('title', 'Detalle del Pedimento')
@section('header', 'Detalle del Pedimento')

@section('actions')
@if($pedimento['estado'] == 'ACTIVO')
    <a href="{{ route('pedimentos.edit', $pedimento['id']) }}" class="btn btn-sm btn-warning">
        <i class="bi bi-pencil"></i> Editar
    </a>
    <button type="button" class="btn btn-sm btn-danger" onclick="confirmarCancelacion()">
        <i class="bi bi-x-circle"></i> Cancelar
    </button>
    @if(!isset($pedimento['registro_volumetrico_id']))
        <button type="button" class="btn btn-sm btn-success" onclick="confirmarUtilizado()">
            <i class="bi bi-check-circle"></i> Marcar como Utilizado
        </button>
    @endif
@endif
<a href="{{ route('pedimentos.index') }}" class="btn btn-sm btn-secondary">
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
                        <th style="width: 40%">Número de Pedimento:</th>
                        <td><strong>{{ $pedimento['numero_pedimento'] }}</strong></td>
                    </tr>
                    <tr>
                        <th>Fecha del Pedimento:</th>
                        <td>{{ $pedimento['fecha_pedimento'] }}</td>
                    </tr>
                    <tr>
                        <th>Estado:</th>
                        <td>
                            @php
                                $estadoClass = [
                                    'ACTIVO' => 'success',
                                    'UTILIZADO' => 'info',
                                    'CANCELADO' => 'secondary'
                                ][$pedimento['estado']] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $estadoClass }}">{{ $pedimento['estado'] }}</span>
                        </td>
                    </tr>
                    @if($pedimento['estado'] == 'CANCELADO' && !empty($pedimento['motivo_cancelacion']))
                        <tr>
                            <th>Motivo Cancelación:</th>
                            <td>{{ $pedimento['motivo_cancelacion'] }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Contribuyente</h5>
            </div>
            <div class="card-body">
                @if(isset($pedimento['contribuyente']))
                    <table class="table table-sm">
                        <tr>
                            <th style="width: 40%">RFC:</th>
                            <td>{{ $pedimento['contribuyente']['rfc'] }}</td>
                        </tr>
                        <tr>
                            <th>Razón Social:</th>
                            <td>{{ $pedimento['contribuyente']['razon_social'] }}</td>
                        </tr>
                    </table>
                @else
                    <p class="text-muted">ID: {{ $pedimento['contribuyente_id'] }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Producto y Volumen</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Producto:</th>
                        <td>
                            @if(isset($pedimento['producto']))
                                <strong>{{ $pedimento['producto']['nombre'] }}</strong><br>
                                <small class="text-muted">Clave SAT: {{ $pedimento['producto']['clave_sat'] }}</small>
                            @else
                                {{ $pedimento['producto_id'] }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Volumen:</th>
                        <td><strong>{{ number_format($pedimento['volumen'], 3) }} {{ $pedimento['unidad_medida'] }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="card-title mb-0">Origen y Destino</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">País Origen:</th>
                        <td>{{ $pedimento['pais_origen'] }}</td>
                    </tr>
                    <tr>
                        <th>País Destino:</th>
                        <td>{{ $pedimento['pais_destino'] }}</td>
                    </tr>
                    <tr>
                        <th>Medio Transporte:</th>
                        <td>{{ $pedimento['medio_transporte_entrada'] }}</td>
                    </tr>
                    <tr>
                        <th>Aduana Entrada:</th>
                        <td>{{ $pedimento['aduana_entrada'] ?? 'No especificada' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">Valor Comercial</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Valor:</th>
                        <td>
                            <strong>{{ $pedimento['moneda'] }} {{ number_format($pedimento['valor_comercial'], 2) }}</strong>
                        </td>
                    </tr>
                    @if(isset($pedimento['tipo_cambio']))
                        <tr>
                            <th>Tipo de Cambio:</th>
                            <td>{{ number_format($pedimento['tipo_cambio'], 4) }}</td>
                        </tr>
                        <tr>
                            <th>Valor en MXN:</th>
                            <td><strong>${{ number_format($pedimento['valor_comercial'] * $pedimento['tipo_cambio'], 2) }}</strong></td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Fechas Importantes</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Fecha de Arribo:</th>
                        <td>{{ $pedimento['fecha_arribo'] ?? 'No registrada' }}</td>
                    </tr>
                    <tr>
                        <th>Fecha de Pago:</th>
                        <td>{{ $pedimento['fecha_pago'] ?? 'No registrada' }}</td>
                    </tr>
                    @if(isset($pedimento['registro_volumetrico_id']))
                        <tr>
                            <th>Registro Volumétrico:</th>
                            <td>
                                <a href="{{ route('registros-volumetricos.show', $pedimento['registro_volumetrico_id']) }}">
                                    {{ $pedimento['registro_volumetrico_id'] }}
                                </a>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

@if(!empty($pedimento['observaciones']))
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Observaciones</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $pedimento['observaciones'] }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Documentos adjuntos -->
@if(!empty($pedimento['documentos']))
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Documentos Adjuntos</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @foreach($pedimento['documentos'] as $documento)
                        <a href="{{ $documento['url'] }}" class="list-group-item list-group-item-action" target="_blank">
                            <i class="bi bi-file-pdf text-danger"></i> {{ $documento['nombre'] }}
                            <small class="text-muted">({{ $documento['tamano'] }})</small>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modales -->
<div class="modal fade" id="cancelarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Cancelar Pedimento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('pedimentos.cancelar', $pedimento['id']) }}">
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
                    <button type="submit" class="btn btn-danger">Cancelar Pedimento</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="utilizadoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Marcar como Utilizado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('pedimentos.utilizado', $pedimento['id']) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="registro_volumetrico_id" class="form-label">Registro Volumétrico</label>
                        <input type="number" class="form-control" id="registro_volumetrico_id" 
                               name="registro_volumetrico_id" required>
                        <small class="text-muted">ID del registro volumétrico asociado</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Marcar como Utilizado</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmarCancelacion() {
    new bootstrap.Modal(document.getElementById('cancelarModal')).show();
}

function confirmarUtilizado() {
    new bootstrap.Modal(document.getElementById('utilizadoModal')).show();
}
</script>
@endpush