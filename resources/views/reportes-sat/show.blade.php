@extends('layouts.app')

@section('title', 'Detalle del Reporte SAT')
@section('header', 'Detalle del Reporte SAT')

@section('actions')
@if(in_array($reporte['estado'], ['GENERADO', 'PENDIENTE']))
    <button type="button" class="btn btn-sm btn-primary" onclick="confirmarEnvio()">
        <i class="bi bi-send"></i> Enviar
    </button>
@endif

@if($reporte['estado'] == 'GENERADO')
    <button type="button" class="btn btn-sm btn-warning" onclick="confirmarFirma()">
        <i class="bi bi-pencil-square"></i> Firmar
    </button>
@endif

@if(in_array($reporte['estado'], ['PENDIENTE', 'GENERADO', 'ERROR', 'RECHAZADO']))
    <button type="button" class="btn btn-sm btn-danger" onclick="confirmarCancelacion()">
        <i class="bi bi-x-circle"></i> Cancelar
    </button>
@endif

<a href="{{ route('reportes-sat.index') }}" class="btn btn-sm btn-secondary">
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
                        <td><strong>{{ $reporte['folio'] }}</strong></td>
                    </tr>
                    <tr>
                        <th>Período:</th>
                        <td>{{ substr($reporte['periodo'], 0, 4) }}-{{ substr($reporte['periodo'], 4, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Tipo:</th>
                        <td><span class="badge bg-info">{{ $reporte['tipo_reporte'] }}</span></td>
                    </tr>
                    <tr>
                        <th>Estado:</th>
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
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Instalación</h5>
            </div>
            <div class="card-body">
                @if(isset($reporte['instalacion']))
                    <table class="table table-sm">
                        <tr>
                            <th style="width: 40%">Nombre:</th>
                            <td>{{ $reporte['instalacion']['nombre'] }}</td>
                        </tr>
                        <tr>
                            <th>Clave:</th>
                            <td>{{ $reporte['instalacion']['clave_instalacion'] }}</td>
                        </tr>
                        <tr>
                            <th>Contribuyente:</th>
                            <td>{{ $reporte['instalacion']['contribuyente']['razon_social'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>RFC:</th>
                            <td>{{ $reporte['instalacion']['contribuyente']['rfc'] ?? '' }}</td>
                        </tr>
                    </table>
                @else
                    <p class="text-muted">ID de Instalación: {{ $reporte['instalacion_id'] }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Generación</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Fecha de Generación:</th>
                        <td>{{ $reporte['fecha_generacion'] }}</td>
                    </tr>
                    <tr>
                        <th>Generado por:</th>
                        <td>
                            @if(isset($reporte['usuario_genera']))
                                {{ $reporte['usuario_genera']['nombres'] }} {{ $reporte['usuario_genera']['apellidos'] }}<br>
                                <small class="text-muted">{{ $reporte['usuario_genera']['email'] }}</small>
                            @else
                                {{ $reporte['usuario_genera_id'] }}
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="card-title mb-0">Información de Firma</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Fecha de Firma:</th>
                        <td>{{ $reporte['fecha_firma'] ?? 'No firmado' }}</td>
                    </tr>
                    <tr>
                        <th>Folio de Firma:</th>
                        <td><small>{{ $reporte['folio_firma'] ?? '-' }}</small></td>
                    </tr>
                    <tr>
                        <th>Certificado SAT:</th>
                        <td>
                            @if(!empty($reporte['certificado_sat']))
                                <small>{{ substr($reporte['certificado_sat'], 0, 50) }}...</small>
                            @else
                                -
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
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">Información de Envío</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Fecha de Envío:</th>
                        <td>{{ $reporte['fecha_envio'] ?? 'No enviado' }}</td>
                    </tr>
                    <tr>
                        <th>Número de Operación:</th>
                        <td>{{ $reporte['numero_operacion'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Respuesta SAT:</th>
                        <td>
                            @if(!empty($reporte['respuesta_sat']))
                                <span class="badge bg-{{ $reporte['estado'] == 'ACEPTADO' ? 'success' : 'danger' }}">
                                    {{ $reporte['respuesta_sat'] }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Documentos</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @if(!empty($reporte['archivo_xml']))
                        <a href="{{ $reporte['archivo_xml'] }}" class="list-group-item list-group-item-action" target="_blank">
                            <i class="bi bi-file-earmark-code text-primary"></i> Archivo XML
                        </a>
                    @endif
                    
                    @if(!empty($reporte['archivo_pdf']))
                        <a href="{{ $reporte['archivo_pdf'] }}" class="list-group-item list-group-item-action" target="_blank">
                            <i class="bi bi-file-earmark-pdf text-danger"></i> Archivo PDF
                        </a>
                    @endif
                    
                    @if(!empty($reporte['acuse']))
                        <a href="{{ $reporte['acuse'] }}" class="list-group-item list-group-item-action" target="_blank">
                            <i class="bi bi-file-earmark-text text-success"></i> Acuse de Recepción
                        </a>
                    @endif
                    
                    @if(empty($reporte['archivo_xml']) && empty($reporte['archivo_pdf']) && empty($reporte['acuse']))
                        <p class="text-muted mb-0">No hay documentos disponibles</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(!empty($reporte['cadena_original']) || !empty($reporte['sello_digital']))
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Detalles de Firma</h5>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="firmaTabs" role="tablist">
                    @if(!empty($reporte['cadena_original']))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="cadena-tab" data-bs-toggle="tab" 
                                    data-bs-target="#cadena" type="button" role="tab">Cadena Original</button>
                        </li>
                    @endif
                    @if(!empty($reporte['sello_digital']))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ empty($reporte['cadena_original']) ? 'active' : '' }}" 
                                    id="sello-tab" data-bs-toggle="tab" data-bs-target="#sello" type="button" role="tab">
                                Sello Digital
                            </button>
                        </li>
                    @endif
                </ul>
                <div class="tab-content mt-3">
                    @if(!empty($reporte['cadena_original']))
                        <div class="tab-pane fade show active" id="cadena" role="tabpanel">
                            <pre class="bg-light p-3 rounded"><code>{{ $reporte['cadena_original'] }}</code></pre>
                        </div>
                    @endif
                    @if(!empty($reporte['sello_digital']))
                        <div class="tab-pane fade {{ empty($reporte['cadena_original']) ? 'show active' : '' }}" id="sello" role="tabpanel">
                            <pre class="bg-light p-3 rounded"><code>{{ $reporte['sello_digital'] }}</code></pre>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if(!empty($reporte['observaciones']))
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Observaciones</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $reporte['observaciones'] }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Log de eventos -->
@if(!empty($reporte['log_eventos']))
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Historial de Eventos</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($reporte['log_eventos'] as $evento)
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <span class="badge bg-{{ $evento['tipo'] == 'exito' ? 'success' : 'info' }}">
                                    {{ $evento['fecha'] }}
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <strong>{{ $evento['accion'] }}</strong>
                                <p class="mb-0 text-muted">{{ $evento['detalle'] }}</p>
                                <small>{{ $evento['usuario'] }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modales (igual que en index) -->
@include('reportes-sat.partials.modales')
@endsection