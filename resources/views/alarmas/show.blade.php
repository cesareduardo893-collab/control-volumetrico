@extends('layouts.app')

@section('title', 'Detalle de Alarma')
@section('header', 'Detalle de Alarma')

@section('actions')
@if($alarma['estado_atencion'] == 'PENDIENTE')
    <a href="{{ route('alarmas.atender.form', $alarma['id']) }}" class="btn btn-sm btn-warning">
        <i class="bi bi-check-circle"></i> Atender Alarma
    </a>
@endif
@if(in_array($alarma['estado_atencion'], ['PENDIENTE', 'EN_PROCESO']))
    <a href="{{ route('alarmas.actualizar-estado.form', $alarma['id']) }}" class="btn btn-sm btn-secondary">
        <i class="bi bi-arrow-repeat"></i> Actualizar Estado
    </a>
@endif
<a href="{{ route('alarmas.edit', $alarma['id']) }}" class="btn btn-sm btn-primary">
    <i class="bi bi-pencil"></i> Editar
</a>
<a href="{{ route('alarmas.index') }}" class="btn btn-sm btn-secondary">
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
                        <td>{{ $alarma['numero_registro'] }}</td>
                    </tr>
                    <tr>
                        <th>Fecha y Hora:</th>
                        <td>{{ $alarma['fecha_hora'] }}</td>
                    </tr>
                    <tr>
                        <th>Tipo de Alarma:</th>
                        <td>{{ $alarma['tipo_alarma']['nombre'] ?? $alarma['tipo_alarma_id'] }}</td>
                    </tr>
                    <tr>
                        <th>Gravedad:</th>
                        <td>
                            @php
                                $badgeClass = [
                                    'BAJA' => 'info',
                                    'MEDIA' => 'warning',
                                    'ALTA' => 'danger',
                                    'CRITICA' => 'dark'
                                ][$alarma['gravedad']] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $badgeClass }}">{{ $alarma['gravedad'] }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Estado:</th>
                        <td>
                            @php
                                $estadoClasses = [
                                    'PENDIENTE' => 'danger',
                                    'EN_PROCESO' => 'warning',
                                    'RESUELTA' => 'success',
                                    'IGNORADA' => 'secondary'
                                ];
                                $estadoClass = $estadoClasses[$alarma['estado_atencion']] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $estadoClass }}">{{ $alarma['estado_atencion'] }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Requiere Atención Inmediata:</th>
                        <td>
                            @if($alarma['requiere_atencion_inmediata'])
                                <span class="badge bg-danger">Sí</span>
                            @else
                                <span class="badge bg-success">No</span>
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
                <h5 class="card-title mb-0">Componente Asociado</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Tipo:</th>
                        <td>{{ $alarma['componente_tipo'] }}</td>
                    </tr>
                    <tr>
                        <th>ID:</th>
                        <td>{{ $alarma['componente_id'] }}</td>
                    </tr>
                    <tr>
                        <th>Identificador:</th>
                        <td>{{ $alarma['componente_identificador'] }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">Descripción</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $alarma['descripcion'] }}</p>
            </div>
        </div>
    </div>
    
    @if(!empty($alarma['atenciones']))
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Historial de Atenciones</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th>Acciones Tomadas</th>
                                <th>Estado</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alarma['atenciones'] as $atencion)
                                <tr>
                                    <td>{{ $atencion['fecha_atencion'] }}</td>
                                    <td>{{ $atencion['usuario']['nombres'] ?? '' }} {{ $atencion['usuario']['apellidos'] ?? '' }}</td>
                                    <td>{{ $atencion['acciones_tomadas'] }}</td>
                                    <td>
                                        @php
                                            $atencionClass = $estadoClasses[$atencion['estado_atencion']] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $atencionClass }}">
                                            {{ $atencion['estado_atencion'] }}
                                        </span>
                                    </td>
                                    <td>{{ $atencion['observaciones'] ?? '-' }}</td>
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