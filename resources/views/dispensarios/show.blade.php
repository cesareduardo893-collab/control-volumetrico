@extends('layouts.app')

@section('title', 'Detalle del Dispensario')
@section('header', 'Detalle del Dispensario')

@section('actions')
<a href="{{ route('dispensarios.edit', $dispensario['id']) }}" class="btn btn-sm btn-warning">
    <i class="bi bi-pencil"></i> Editar
</a>
<a href="{{ route('dispensarios.mangueras', $dispensario['id']) }}" class="btn btn-sm btn-info">
    <i class="bi bi-pip"></i> Ver Mangueras
</a>
<a href="{{ route('dispensarios.verificar-estado', $dispensario['id']) }}" class="btn btn-sm btn-primary">
    <i class="bi bi-check-circle"></i> Verificar Estado
</a>
<a href="{{ route('dispensarios.index') }}" class="btn btn-sm btn-secondary">
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
                        <td>{{ $dispensario['clave'] }}</td>
                    </tr>
                    <tr>
                        <th>Descripción:</th>
                        <td>{{ $dispensario['descripcion'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Modelo:</th>
                        <td>{{ $dispensario['modelo'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Fabricante:</th>
                        <td>{{ $dispensario['fabricante'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Número de Serie:</th>
                        <td>{{ $dispensario['numero_serie'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Estado:</th>
                        <td>
                            @php
                                $estadoClass = [
                                    'OPERATIVO' => 'success',
                                    'MANTENIMIENTO' => 'warning',
                                    'FUERA_SERVICIO' => 'danger'
                                ][$dispensario['estado']] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $estadoClass }}">{{ $dispensario['estado'] }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Activo:</th>
                        <td>
                            @if($dispensario['activo'])
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
                <h5 class="card-title mb-0">Instalación</h5>
            </div>
            <div class="card-body">
                @if(isset($dispensario['instalacion']))
                    <table class="table table-sm">
                        <tr>
                            <th style="width: 40%">Nombre:</th>
                            <td>{{ $dispensario['instalacion']['nombre'] }}</td>
                        </tr>
                        <tr>
                            <th>Clave:</th>
                            <td>{{ $dispensario['instalacion']['clave_instalacion'] }}</td>
                        </tr>
                        <tr>
                            <th>Domicilio:</th>
                            <td>{{ $dispensario['instalacion']['domicilio'] }}</td>
                        </tr>
                        <tr>
                            <th>Municipio:</th>
                            <td>{{ $dispensario['instalacion']['municipio'] }}</td>
                        </tr>
                        <tr>
                            <th>Estado:</th>
                            <td>{{ $dispensario['instalacion']['estado'] }}</td>
                        </tr>
                    </table>
                @else
                    <p class="text-muted">ID de Instalación: {{ $dispensario['instalacion_id'] }}</p>
                @endif
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
                        <th style="width: 40%">Capacidad Máxima:</th>
                        <td>{{ $dispensario['capacidad_maxima'] ?? 'N/A' }} L/min</td>
                    </tr>
                    <tr>
                        <th>Presión de Operación:</th>
                        <td>{{ $dispensario['presion_operacion'] ?? 'N/A' }} psi</td>
                    </tr>
                    <tr>
                        <th>Tipo de Medición:</th>
                        <td>{{ $dispensario['tipo_medicion'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Precisión:</th>
                        <td>{{ $dispensario['precision'] ?? 'N/A' }}%</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="card-title mb-0">Fechas Importantes</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Fecha de Instalación:</th>
                        <td>{{ $dispensario['fecha_instalacion'] ?? 'No registrada' }}</td>
                    </tr>
                    <tr>
                        <th>Último Mantenimiento:</th>
                        <td>{{ $dispensario['fecha_ultimo_mantenimiento'] ?? 'No registrado' }}</td>
                    </tr>
                    <tr>
                        <th>Próximo Mantenimiento:</th>
                        <td>
                            @if(isset($dispensario['fecha_proximo_mantenimiento']))
                                @php
                                    $dias = now()->diffInDays($dispensario['fecha_proximo_mantenimiento'], false);
                                    $badgeClass = $dias < 7 ? 'danger' : ($dias < 15 ? 'warning' : 'success');
                                @endphp
                                {{ $dispensario['fecha_proximo_mantenimiento'] }}
                                <span class="badge bg-{{ $badgeClass }}">{{ round($dias) }} días</span>
                            @else
                                No programado
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Mangueras asociadas -->
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Mangueras Asociadas</h5>
                <span class="badge bg-light text-dark">{{ count($dispensario['mangueras'] ?? []) }} mangueras</span>
            </div>
            <div class="card-body">
                @if(!empty($dispensario['mangueras']))
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Clave</th>
                                    <th>Descripción</th>
                                    <th>Medidor Asignado</th>
                                    <th>Estado</th>
                                    <th>Activo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dispensario['mangueras'] as $manguera)
                                    <tr>
                                        <td>{{ $manguera['clave'] }}</td>
                                        <td>{{ $manguera['descripcion'] ?? '-' }}</td>
                                        <td>
                                            @if(isset($manguera['medidor']))
                                                {{ $manguera['medidor']['clave'] }}<br>
                                                <small class="text-muted">{{ $manguera['medidor']['numero_serie'] }}</small>
                                            @else
                                                <span class="text-muted">No asignado</span>
                                            @endif
                                        </td>
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
                                        <td>
                                            @if($manguera['activo'])
                                                <span class="badge bg-success">Activo</span>
                                            @else
                                                <span class="badge bg-secondary">Inactivo</span>
                                            @endif
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
                @else
                    <p class="text-muted mb-0">No hay mangueras asociadas a este dispensario.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Historial de mantenimientos -->
@if(!empty($dispensario['mantenimientos']))
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Historial de Mantenimientos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Descripción</th>
                                <th>Técnico</th>
                                <th>Costo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dispensario['mantenimientos'] as $mantenimiento)
                                <tr>
                                    <td>{{ $mantenimiento['fecha'] }}</td>
                                    <td>
                                        <span class="badge bg-{{ $mantenimiento['tipo'] == 'PREVENTIVO' ? 'info' : 'warning' }}">
                                            {{ $mantenimiento['tipo'] }}
                                        </span>
                                    </td>
                                    <td>{{ $mantenimiento['descripcion'] }}</td>
                                    <td>{{ $mantenimiento['tecnico'] }}</td>
                                    <td>${{ number_format($mantenimiento['costo'] ?? 0, 2) }}</td>
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

<!-- Botón de eliminar (solo si no tiene mangueras activas) -->
@if(empty($dispensario['mangueras']))
<form method="POST" action="{{ route('dispensarios.destroy', $dispensario['id']) }}" class="d-inline" 
      onsubmit="return confirm('¿Está seguro de eliminar este dispensario? Esta acción no se puede deshacer.');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">
        <i class="bi bi-trash"></i> Eliminar Dispensario
    </button>
</form>
@endif
@endsection