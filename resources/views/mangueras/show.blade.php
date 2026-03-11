@extends('layouts.app')

@section('title', 'Detalle de Manguera')
@section('header', 'Detalle de Manguera')

@section('actions')
<a href="{{ route('mangueras.edit', $manguera['id']) }}" class="btn btn-sm btn-warning">
    <i class="bi bi-pencil"></i> Editar
</a>
<a href="{{ route('mangueras.index') }}" class="btn btn-sm btn-secondary">
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
                        <th style="width: 40%">Clave:</th>
                        <td><strong>{{ $manguera['clave'] }}</strong></td>
                    </tr>
                    <tr>
                        <th>Descripción:</th>
                        <td>{{ $manguera['descripcion'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Estado:</th>
                        <td>
                            @php
                                $estadoClass = [
                                    'OPERATIVO' => 'success',
                                    'MANTENIMIENTO' => 'warning',
                                    'FUERA_SERVICIO' => 'danger'
                                ][$manguera['estado']] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $estadoClass }}">{{ $manguera['estado'] }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Activo:</th>
                        <td>
                            @if($manguera['activo'])
                                <span class="badge bg-success">Sí</span>
                            @else
                                <span class="badge bg-secondary">No</span>
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
                <h5 class="card-title mb-0">Dispensario</h5>
            </div>
            <div class="card-body">
                @if(isset($manguera['dispensario']))
                    <table class="table table-sm">
                        <tr>
                            <th style="width: 40%">Clave:</th>
                            <td>
                                <a href="{{ route('dispensarios.show', $manguera['dispensario']['id']) }}">
                                    {{ $manguera['dispensario']['clave'] }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Instalación:</th>
                            <td>{{ $manguera['dispensario']['instalacion']['nombre'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>Modelo:</th>
                            <td>{{ $manguera['dispensario']['modelo'] ?? '-' }}</td>
                        </tr>
                    </table>
                @else
                    <p class="text-muted">ID de Dispensario: {{ $manguera['dispensario_id'] }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Medidor Asignado</h5>
            </div>
            <div class="card-body">
                @if(isset($manguera['medidor']))
                    <table class="table table-sm">
                        <tr>
                            <th style="width: 40%">Clave:</th>
                            <td>
                                <a href="{{ route('medidores.show', $manguera['medidor']['id']) }}">
                                    {{ $manguera['medidor']['clave'] }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>N° Serie:</th>
                            <td>{{ $manguera['medidor']['numero_serie'] }}</td>
                        </tr>
                        <tr>
                            <th>Modelo:</th>
                            <td>{{ $manguera['medidor']['modelo'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Fabricante:</th>
                            <td>{{ $manguera['medidor']['fabricante'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Precisión:</th>
                            <td>{{ $manguera['medidor']['precision'] }}%</td>
                        </tr>
                        <tr>
                            <th>Estado:</th>
                            <td>
                                @php
                                    $estadoMedidorClass = [
                                        'OPERATIVO' => 'success',
                                        'CALIBRACION' => 'info',
                                        'MANTENIMIENTO' => 'warning',
                                        'FUERA_SERVICIO' => 'danger',
                                        'FALLA_COMUNICACION' => 'secondary'
                                    ][$manguera['medidor']['estado']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $estadoMedidorClass }}">{{ $manguera['medidor']['estado'] }}</span>
                            </td>
                        </tr>
                    </table>
                    
                    <form method="POST" action="{{ route('mangueras.quitar-medidor', $manguera['id']) }}" 
                          class="d-inline" onsubmit="return confirm('¿Está seguro de quitar el medidor de esta manguera?');">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="bi bi-x-circle"></i> Quitar Medidor
                        </button>
                    </form>
                @else
                    <p class="text-muted">No hay medidor asignado</p>
                    <a href="{{ route('mangueras.edit', $manguera['id']) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> Asignar Medidor
                    </a>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="card-title mb-0">Calibración</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Última Calibración:</th>
                        <td>{{ $manguera['fecha_ultima_calibracion'] ?? 'No registrada' }}</td>
                    </tr>
                    <tr>
                        <th>Próxima Calibración:</th>
                        <td>
                            @if(isset($manguera['fecha_proxima_calibracion']))
                                @php
                                    $dias = now()->diffInDays($manguera['fecha_proxima_calibracion'], false);
                                    $badgeClass = $dias < 7 ? 'danger' : ($dias < 15 ? 'warning' : 'success');
                                @endphp
                                {{ $manguera['fecha_proxima_calibracion'] }}
                                <span class="badge bg-{{ $badgeClass }}">{{ round($dias) }} días</span>
                            @else
                                No programada
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Certificado:</th>
                        <td>{{ $manguera['certificado_calibracion'] ?? 'No disponible' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@if(!empty($manguera['observaciones']))
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">Observaciones</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $manguera['observaciones'] }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Historial de calibraciones -->
@if(!empty($manguera['historial_calibraciones']))
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Historial de Calibraciones</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Certificado</th>
                                <th>Laboratorio</th>
                                <th>Precisión</th>
                                <th>Resultado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($manguera['historial_calibraciones'] as $calibracion)
                                <tr>
                                    <td>{{ $calibracion['fecha'] }}</td>
                                    <td>{{ $calibracion['certificado'] }}</td>
                                    <td>{{ $calibracion['laboratorio'] }}</td>
                                    <td>{{ $calibracion['precision'] }}%</td>
                                    <td>
                                        @if($calibracion['exitosa'])
                                            <span class="badge bg-success">Exitosa</span>
                                        @else
                                            <span class="badge bg-danger">Fallida</span>
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
</div>
@endif

<!-- Botón de eliminar (solo si no tiene medidor asignado) -->
@if(!isset($manguera['medidor']))
<form method="POST" action="{{ route('mangueras.destroy', $manguera['id']) }}" class="d-inline" 
      onsubmit="return confirm('¿Está seguro de eliminar esta manguera? Esta acción no se puede deshacer.');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">
        <i class="bi bi-trash"></i> Eliminar Manguera
    </button>
</form>
@endif
@endsection