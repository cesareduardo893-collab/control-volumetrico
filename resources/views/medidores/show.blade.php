@extends('layouts.app')

@section('title', 'Detalle del Medidor')
@section('header', 'Detalle del Medidor')

@section('actions')
<a href="{{ route('medidores.edit', $medidor['id']) }}" class="btn btn-sm btn-warning">
    <i class="bi bi-pencil"></i> Editar
</a>
<a href="{{ route('medidores.historial-calibraciones', $medidor['id']) }}" class="btn btn-sm btn-info">
    <i class="bi bi-calendar-check"></i> Historial Calibraciones
</a>
<a href="{{ route('medidores.verificar-estado', $medidor['id']) }}" class="btn btn-sm btn-primary">
    <i class="bi bi-check-circle"></i> Verificar Estado
</a>
@if($medidor['estado'] == 'OPERATIVO')
    <a href="{{ route('medidores.probar-comunicacion', $medidor['id']) }}" class="btn btn-sm btn-success">
        <i class="bi bi-wifi"></i> Probar Comunicación
    </a>
@endif
<a href="{{ route('medidores.index') }}" class="btn btn-sm btn-secondary">
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
                        <td><strong>{{ $medidor['clave'] }}</strong></td>
                    </tr>
                    <tr>
                        <th>Número de Serie:</th>
                        <td>{{ $medidor['numero_serie'] }}</td>
                    </tr>
                    <tr>
                        <th>Modelo:</th>
                        <td>{{ $medidor['modelo'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Fabricante:</th>
                        <td>{{ $medidor['fabricante'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tipo de Elemento:</th>
                        <td>{{ ucfirst($medidor['elemento_tipo']) }}</td>
                    </tr>
                    <tr>
                        <th>Tipo de Medición:</th>
                        <td>{{ $medidor['tipo_medicion'] }}</td>
                    </tr>
                    <tr>
                        <th>Estado:</th>
                        <td>
                            @php
                                $estadoClass = [
                                    'OPERATIVO' => 'success',
                                    'CALIBRACION' => 'info',
                                    'MANTENIMIENTO' => 'warning',
                                    'FUERA_SERVICIO' => 'danger',
                                    'FALLA_COMUNICACION' => 'secondary'
                                ][$medidor['estado']] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $estadoClass }}">{{ $medidor['estado'] }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Activo:</th>
                        <td>
                            @if($medidor['activo'])
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
                <h5 class="card-title mb-0">Ubicación</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Instalación:</th>
                        <td>
                            @if(isset($medidor['instalacion']))
                                <a href="{{ route('instalaciones.show', $medidor['instalacion']['id']) }}">
                                    {{ $medidor['instalacion']['nombre'] }}
                                </a>
                            @else
                                {{ $medidor['instalacion_id'] }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tanque Asignado:</th>
                        <td>
                            @if(isset($medidor['tanque']))
                                <a href="{{ route('tanques.show', $medidor['tanque']['id']) }}">
                                    {{ $medidor['tanque']['identificador'] }}
                                </a>
                                <br>
                                <small class="text-muted">{{ $medidor['tanque']['producto']['nombre'] ?? 'Sin producto' }}</small>
                            @else
                                <span class="text-muted">No asignado</span>
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
                <h5 class="card-title mb-0">Especificaciones Técnicas</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Precisión:</th>
                        <td>{{ $medidor['precision'] }}%</td>
                    </tr>
                    <tr>
                        <th>Capacidad Máxima:</th>
                        <td>{{ number_format($medidor['capacidad_maxima'], 1) }} L/min</td>
                    </tr>
                    @if(isset($medidor['presion_maxima']))
                        <tr>
                            <th>Presión Máxima:</th>
                            <td>{{ $medidor['presion_maxima'] }} psi</td>
                        </tr>
                    @endif
                    @if(isset($medidor['temperatura_maxima']))
                        <tr>
                            <th>Temperatura Máxima:</th>
                            <td>{{ $medidor['temperatura_maxima'] }} °C</td>
                        </tr>
                    @endif
                    @if(isset($medidor['tecnologia_id']))
                        <tr>
                            <th>Tecnología:</th>
                            <td>{{ $medidor['tecnologia_id'] }}</td>
                        </tr>
                    @endif
                    @if(isset($medidor['protocolo_comunicacion']))
                        <tr>
                            <th>Protocolo:</th>
                            <td>{{ $medidor['protocolo_comunicacion'] }}</td>
                        </tr>
                    @endif
                </table>
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
                        <th style="width: 40%">Fecha Instalación:</th>
                        <td>{{ $medidor['fecha_instalacion'] ?? 'No registrada' }}</td>
                    </tr>
                    <tr>
                        <th>Última Calibración:</th>
                        <td>{{ $medidor['fecha_ultima_calibracion'] ?? 'No registrada' }}</td>
                    </tr>
                    <tr>
                        <th>Próxima Calibración:</th>
                        <td>
                            @if(isset($medidor['fecha_proxima_calibracion']))
                                @php
                                    $dias = now()->diffInDays($medidor['fecha_proxima_calibracion'], false);
                                    $badgeClass = $dias < 7 ? 'danger' : ($dias < 15 ? 'warning' : 'success');
                                @endphp
                                {{ $medidor['fecha_proxima_calibracion'] }}
                                <span class="badge bg-{{ $badgeClass }}">{{ round($dias) }} días</span>
                            @else
                                No programada
                            @endif
                        </td>
                    </tr>
                    @if(isset($medidor['certificado_calibracion']))
                        <tr>
                            <th>Certificado:</th>
                            <td>{{ $medidor['certificado_calibracion'] }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Mangueras asociadas (si aplica) -->
@if(!empty($medidor['mangueras']))
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">Mangueras Asociadas</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Clave</th>
                                <th>Descripción</th>
                                <th>Dispensario</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medidor['mangueras'] as $manguera)
                                <tr>
                                    <td>{{ $manguera['clave'] }}</td>
                                    <td>{{ $manguera['descripcion'] ?? '-' }}</td>
                                    <td>
                                        @if(isset($manguera['dispensario']))
                                            <a href="{{ route('dispensarios.show', $manguera['dispensario']['id']) }}">
                                                {{ $manguera['dispensario']['clave'] }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $estadoMangueraClass = [
                                                'OPERATIVO' => 'success',
                                                'MANTENIMIENTO' => 'warning',
                                                'FUERA_SERVICIO' => 'danger'
                                            ][$manguera['estado']] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $estadoMangueraClass }}">{{ $manguera['estado'] }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('mangueras.show', $manguera['id']) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
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

@if(!empty($medidor['observaciones']))
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Observaciones</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $medidor['observaciones'] }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Botón de eliminar (solo si no tiene mangueras asociadas) -->
@if(empty($medidor['mangueras']))
<form method="POST" action="{{ route('medidores.destroy', $medidor['id']) }}" class="d-inline"
      onsubmit="return confirm('¿Está seguro de eliminar este medidor? Esta acción no se puede deshacer.');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">
        <i class="bi bi-trash"></i> Eliminar Medidor
    </button>
</form>
@endif
@endsection