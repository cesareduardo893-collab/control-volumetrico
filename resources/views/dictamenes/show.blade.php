@extends('layouts.app')

@section('title', 'Detalle del Dictamen')
@section('header', 'Detalle del Dictamen de Calidad')

@section('actions')
@if($dictamen['estado'] == 'VIGENTE')
    <a href="{{ route('dictamenes.edit', $dictamen['id']) }}" class="btn btn-sm btn-warning">
        <i class="bi bi-pencil"></i> Editar
    </a>
    <button type="button" class="btn btn-sm btn-danger" onclick="confirmarCancelacion()">
        <i class="bi bi-x-circle"></i> Cancelar Dictamen
    </button>
@endif
<a href="{{ route('dictamenes.verificar-vigencia', $dictamen['id']) }}" class="btn btn-sm btn-info">
    <i class="bi bi-check-circle"></i> Verificar Vigencia
</a>
<a href="{{ route('dictamenes.index') }}" class="btn btn-sm btn-secondary">
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
                        <th style="width: 40%">Folio:</th>
                        <td>{{ $dictamen['folio'] }}</td>
                    </tr>
                    <tr>
                        <th>Número de Lote:</th>
                        <td>{{ $dictamen['numero_lote'] }}</td>
                    </tr>
                    <tr>
                        <th>Fecha de Emisión:</th>
                        <td>{{ $dictamen['fecha_emision'] }}</td>
                    </tr>
                    <tr>
                        <th>Estado:</th>
                        <td>
                            @php
                                $estadoClass = [
                                    'VIGENTE' => 'success',
                                    'CADUCADO' => 'warning',
                                    'CANCELADO' => 'secondary'
                                ][$dictamen['estado']] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $estadoClass }}">{{ $dictamen['estado'] }}</span>
                            @if($dictamen['vigente'] ?? true)
                                <span class="badge bg-success">Vigente</span>
                            @else
                                <span class="badge bg-danger">No Vigente</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Contribuyente e Instalación</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Contribuyente:</th>
                        <td>
                            @if(isset($dictamen['contribuyente']))
                                {{ $dictamen['contribuyente']['razon_social'] }}<br>
                                <small class="text-muted">{{ $dictamen['contribuyente']['rfc'] }}</small>
                            @else
                                {{ $dictamen['contribuyente_id'] }}
                            @endif
                        </td>
                    </tr>
                    @if(isset($dictamen['instalacion']))
                        <tr>
                            <th>Instalación:</th>
                            <td>{{ $dictamen['instalacion']['nombre'] }}</td>
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
                <h5 class="card-title mb-0">Laboratorio</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">RFC:</th>
                        <td>{{ $dictamen['laboratorio_rfc'] }}</td>
                    </tr>
                    <tr>
                        <th>Nombre:</th>
                        <td>{{ $dictamen['laboratorio_nombre'] }}</td>
                    </tr>
                    <tr>
                        <th>N° Acreditación:</th>
                        <td>{{ $dictamen['laboratorio_numero_acreditacion'] }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="card-title mb-0">Fechas del Proceso</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Toma de Muestra:</th>
                        <td>{{ $dictamen['fecha_toma_muestra'] }}</td>
                    </tr>
                    <tr>
                        <th>Fecha de Pruebas:</th>
                        <td>{{ $dictamen['fecha_pruebas'] }}</td>
                    </tr>
                    <tr>
                        <th>Fecha de Resultados:</th>
                        <td>{{ $dictamen['fecha_resultados'] }}</td>
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
                <h5 class="card-title mb-0">Producto y Muestra</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    @if(isset($dictamen['producto']))
                        <tr>
                            <th style="width: 40%">Producto:</th>
                            <td>{{ $dictamen['producto']['nombre'] }}</td>
                        </tr>
                        <tr>
                            <th>Clave SAT:</th>
                            <td>{{ $dictamen['producto']['clave_sat'] }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th>Volumen de Muestra:</th>
                        <td>{{ number_format($dictamen['volumen_muestra'], 3) }} {{ $dictamen['unidad_medida_muestra'] }}</td>
                    </tr>
                    <tr>
                        <th>Método de Muestreo:</th>
                        <td>{{ $dictamen['metodo_muestreo'] }}</td>
                    </tr>
                    <tr>
                        <th>Método de Ensayo:</th>
                        <td>{{ $dictamen['metodo_ensayo'] }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    @if(!empty($dictamen['parametros']))
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Parámetros Analizados</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Parámetro</th>
                                <th>Resultado</th>
                                <th>Unidad</th>
                                <th>Especificación</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dictamen['parametros'] as $parametro)
                                <tr>
                                    <td>{{ $parametro['nombre'] }}</td>
                                    <td>{{ $parametro['resultado'] }}</td>
                                    <td>{{ $parametro['unidad'] }}</td>
                                    <td>{{ $parametro['especificacion'] }}</td>
                                    <td>
                                        @if($parametro['cumple'])
                                            <span class="badge bg-success">Cumple</span>
                                        @else
                                            <span class="badge bg-danger">No Cumple</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@if(!empty($dictamen['resultados']))
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Resultados del Análisis</h5>
            </div>
            <div class="card-body">
                <p>{{ $dictamen['resultados'] }}</p>
            </div>
        </div>
    </div>
</div>
@endif

@if(!empty($dictamen['observaciones']))
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Observaciones</h5>
            </div>
            <div class="card-body">
                <p>{{ $dictamen['observaciones'] }}</p>
            </div>
        </div>
    </div>
</div>
@endif

@if(!empty($dictamen['documentos_adjuntos']))
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Documentos Adjuntos</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @foreach($dictamen['documentos_adjuntos'] as $documento)
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

<!-- Modal de cancelación -->
<div class="modal fade" id="cancelarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Cancelar Dictamen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('dictamenes.cancelar', $dictamen['id']) }}">
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