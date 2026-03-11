@extends('layouts.app')

@section('title', 'Detalle del Certificado')
@section('header', 'Detalle del Certificado de Verificación')

@section('actions')
<a href="{{ route('certificados-verificacion.edit', $certificado['id']) }}" class="btn btn-sm btn-warning">
    <i class="bi bi-pencil"></i> Editar
</a>
<a href="{{ route('certificados-verificacion.verificar-vigencia', $certificado['id']) }}" class="btn btn-sm btn-info">
    <i class="bi bi-check-circle"></i> Verificar Vigencia
</a>
<a href="{{ route('certificados-verificacion.index') }}" class="btn btn-sm btn-secondary">
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
                        <td>{{ $certificado['folio'] }}</td>
                    </tr>
                    <tr>
                        <th>Fecha de Emisión:</th>
                        <td>{{ $certificado['fecha_emision'] }}</td>
                    </tr>
                    <tr>
                        <th>Resultado:</th>
                        <td>
                            @if($certificado['resultado'] == 'acreditado')
                                <span class="badge bg-success">Acreditado</span>
                            @else
                                <span class="badge bg-danger">No Acreditado</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Vigente:</th>
                        <td>
                            @if($certificado['vigente'] ?? true)
                                <span class="badge bg-success">Sí</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Verificación Extraordinaria:</th>
                        <td>
                            @if($certificado['requiere_verificacion_extraordinaria'] ?? false)
                                <span class="badge bg-warning">Requiere</span>
                            @else
                                <span class="badge bg-info">No Requiere</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Proveedor</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">RFC:</th>
                        <td>{{ $certificado['proveedor_rfc'] }}</td>
                    </tr>
                    <tr>
                        <th>Nombre:</th>
                        <td>{{ $certificado['proveedor_nombre'] }}</td>
                    </tr>
                    <tr>
                        <th>Número de Acreditación:</th>
                        <td>{{ $certificado['proveedor_numero_acreditacion'] ?? 'No especificado' }}</td>
                    </tr>
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
                @if(isset($certificado['contribuyente']))
                    <table class="table table-sm">
                        <tr>
                            <th style="width: 40%">RFC:</th>
                            <td>{{ $certificado['contribuyente']['rfc'] }}</td>
                        </tr>
                        <tr>
                            <th>Razón Social:</th>
                            <td>{{ $certificado['contribuyente']['razon_social'] }}</td>
                        </tr>
                        <tr>
                            <th>Permiso:</th>
                            <td>{{ $certificado['contribuyente']['numero_permiso'] ?? 'No especificado' }}</td>
                        </tr>
                    </table>
                @else
                    <p class="text-muted">Información no disponible</p>
                @endif
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="card-title mb-0">Fechas de Verificación</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Inicio:</th>
                        <td>{{ $certificado['fecha_inicio_verificacion'] }}</td>
                    </tr>
                    <tr>
                        <th>Fin:</th>
                        <td>{{ $certificado['fecha_fin_verificacion'] }}</td>
                    </tr>
                    <tr>
                        <th>Caducidad:</th>
                        <td>{{ $certificado['fecha_caducidad'] ?? 'No definida' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">Tabla de Cumplimiento</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Concepto</th>
                                <th>Cumple</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($certificado['tabla_cumplimiento'] ?? [] as $concepto => $valor)
                                @if(!str_contains($concepto, '_obs'))
                                    @php
                                        $observacion = $certificado['tabla_cumplimiento'][$concepto . '_obs'] ?? '-';
                                        $badgeClass = $valor == 'SI' ? 'success' : ($valor == 'NO' ? 'danger' : 'secondary');
                                    @endphp
                                    <tr>
                                        <td>{{ ucfirst(str_replace('_', ' ', $concepto)) }}</td>
                                        <td><span class="badge bg-{{ $badgeClass }}">{{ $valor }}</span></td>
                                        <td>{{ $observacion }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    @if(!empty($certificado['observaciones']))
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Observaciones</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $certificado['observaciones'] }}</p>
            </div>
        </div>
    </div>
    @endif
    
    @if(!empty($certificado['historial_verificaciones']))
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Historial de Verificaciones</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Resultado</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($certificado['historial_verificaciones'] as $historial)
                                <tr>
                                    <td>{{ $historial['fecha'] }}</td>
                                    <td>{{ $historial['tipo'] }}</td>
                                    <td>
                                        @if($historial['resultado'] == 'acreditado')
                                            <span class="badge bg-success">Acreditado</span>
                                        @else
                                            <span class="badge bg-danger">No Acreditado</span>
                                        @endif
                                    </td>
                                    <td>{{ $historial['observaciones'] ?? '-' }}</td>
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
@endsection