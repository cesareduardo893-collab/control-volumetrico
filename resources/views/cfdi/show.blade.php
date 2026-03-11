@extends('layouts.app')

@section('title', 'Detalle del CFDI')
@section('header', 'Detalle del Comprobante Fiscal')

@section('actions')
@if($cfdi['estado'] == 'VIGENTE')
    <button type="button" class="btn btn-sm btn-danger" onclick="confirmarCancelacion({{ $cfdi['id'] }})">
        <i class="bi bi-x-circle"></i> Cancelar CFDI
    </button>
@endif
<a href="{{ route('cfdi.index') }}" class="btn btn-sm btn-secondary">
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
                        <th style="width: 40%">UUID:</th>
                        <td><small>{{ $cfdi['uuid'] }}</small></td>
                    </tr>
                    <tr>
                        <th>Fecha de Emisión:</th>
                        <td>{{ $cfdi['fecha_emision'] }}</td>
                    </tr>
                    <tr>
                        <th>Tipo de Operación:</th>
                        <td>{{ ucfirst($cfdi['tipo_operacion']) }}</td>
                    </tr>
                    <tr>
                        <th>Estado:</th>
                        <td>
                            @if($cfdi['estado'] == 'VIGENTE')
                                <span class="badge bg-success">Vigente</span>
                            @else
                                <span class="badge bg-secondary">Cancelado</span>
                            @endif
                        </td>
                    </tr>
                    @if($cfdi['estado'] == 'CANCELADO' && !empty($cfdi['motivo_cancelacion']))
                        <tr>
                            <th>Motivo Cancelación:</th>
                            <td>{{ $cfdi['motivo_cancelacion'] }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Partes Involucradas</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">RFC Emisor:</th>
                        <td>{{ $cfdi['rfc_emisor'] }}</td>
                    </tr>
                    <tr>
                        <th>RFC Receptor:</th>
                        <td>{{ $cfdi['rfc_receptor'] }}</td>
                    </tr>
                    @if(isset($cfdi['receptor_nombre']))
                        <tr>
                            <th>Nombre Receptor:</th>
                            <td>{{ $cfdi['receptor_nombre'] }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Montos</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Subtotal:</th>
                        <td>${{ number_format($cfdi['subtotal'], 2) }}</td>
                    </tr>
                    <tr>
                        <th>IVA:</th>
                        <td>${{ number_format($cfdi['iva'], 2) }}</td>
                    </tr>
                    <tr>
                        <th>IEPS:</th>
                        <td>${{ number_format($cfdi['ieps'], 2) }}</td>
                    </tr>
                    <tr class="table-active">
                        <th><strong>TOTAL:</strong></th>
                        <td><strong>${{ number_format($cfdi['total'], 2) }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="card-title mb-0">Detalles del Producto/Servicio</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    @if(isset($cfdi['producto']))
                        <tr>
                            <th style="width: 40%">Producto:</th>
                            <td>{{ $cfdi['producto']['nombre'] }}</td>
                        </tr>
                        <tr>
                            <th>Clave SAT:</th>
                            <td>{{ $cfdi['producto']['clave_sat'] }}</td>
                        </tr>
                    @endif
                    
                    @if(isset($cfdi['volumen']))
                        <tr>
                            <th>Volumen:</th>
                            <td>{{ number_format($cfdi['volumen'], 3) }} L</td>
                        </tr>
                    @endif
                    
                    @if(isset($cfdi['registro_volumetrico_id']))
                        <tr>
                            <th>Registro Volumétrico ID:</th>
                            <td>
                                <a href="{{ route('registros-volumetricos.show', $cfdi['registro_volumetrico_id']) }}">
                                    {{ $cfdi['registro_volumetrico_id'] }}
                                </a>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

@if(!empty($cfdi['conceptos']))
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">Conceptos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Cantidad</th>
                                <th>Clave SAT</th>
                                <th>Descripción</th>
                                <th>Valor Unitario</th>
                                <th>Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cfdi['conceptos'] as $concepto)
                                <tr>
                                    <td>{{ $concepto['cantidad'] }}</td>
                                    <td>{{ $concepto['clave_sat'] }}</td>
                                    <td>{{ $concepto['descripcion'] }}</td>
                                    <td>${{ number_format($concepto['valor_unitario'], 2) }}</td>
                                    <td>${{ number_format($concepto['importe'], 2) }}</td>
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

@if(!empty($cfdi['impuestos']))
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Impuestos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Impuesto</th>
                                <th>Tasa</th>
                                <th>Base</th>
                                <th>Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cfdi['impuestos'] as $impuesto)
                                <tr>
                                    <td>{{ $impuesto['tipo'] }}</td>
                                    <td>{{ $impuesto['tasa'] }}%</td>
                                    <td>${{ number_format($impuesto['base'], 2) }}</td>
                                    <td>${{ number_format($impuesto['importe'], 2) }}</td>
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

<!-- Modal de cancelación -->
<div class="modal fade" id="cancelarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Cancelar CFDI</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('cfdi.cancelar', $cfdi['id']) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="motivo_cancelacion" class="form-label">Motivo de Cancelación</label>
                        <textarea class="form-control" id="motivo_cancelacion" name="motivo_cancelacion" 
                                  rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Cancelación</button>
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
</script>
@endpush