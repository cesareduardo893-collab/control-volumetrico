@extends('layouts.app')
@section('title', 'Detalle del Tanque')
@section('header', 'Detalle del Tanque')

@section('actions')
<a href="{{ route('tanques.edit', $tanque['id']) }}" class="btn btn-sm btn-warning">
    <i class="bi bi-pencil"></i> Editar
</a>
<a href="{{ route('tanques.curva-calibracion', $tanque['id']) }}" class="btn btn-sm btn-info">
    <i class="bi bi-graph-up"></i> Curva Calibración
</a>
<a href="{{ route('tanques.verificar-estado', $tanque['id']) }}" class="btn btn-sm btn-primary">
    <i class="bi bi-check-circle"></i> Verificar Estado
</a>
<a href="{{ route('tanques.index') }}" class="btn btn-sm btn-secondary">
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
                        <th style="width: 40%">Identificador:</th>
                        <td><strong>{{ $tanque['identificador'] }}</strong></td>
                    </tr>
                    <tr>
                        <th>Número de Serie:</th>
                        <td>{{ $tanque['numero_serie'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Modelo:</th>
                        <td>{{ $tanque['modelo'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Fabricante:</th>
                        <td>{{ $tanque['fabricante'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Material:</th>
                        <td>{{ $tanque['material'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Estado:</th>
                        <td>
                            @php
                                $estadoClass = [
                                    'OPERATIVO' => 'success',
                                    'MANTENIMIENTO' => 'warning',
                                    'FUERA_SERVICIO' => 'danger',
                                    'CALIBRACION' => 'info'
                                ][$tanque['estado']] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $estadoClass }}">{{ $tanque['estado'] }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Activo:</th>
                        <td>
                            @if(($tanque['activo'] ?? true))
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
                <h5 class="card-title mb-0">Instalación y Producto</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Instalación:</th>
                        <td>
                            @if(isset($tanque['instalacion']))
                                {{ $tanque['instalacion']['nombre'] }}<br>
                                <small class="text-muted">{{ $tanque['instalacion']['clave_instalacion'] }}</small>
                            @else
                                {{ $tanque['instalacion_id'] }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Producto:</th>
                        <td>
                            @if(isset($tanque['producto']))
                                {{ $tanque['producto']['nombre'] }}
                            @else
                                <span class="text-muted">Sin producto asignado</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tipo de Tanque:</th>
                        <td>
                            @if(isset($tanque['tipoTanque']))
                                {{ $tanque['tipoTanque']['nombre'] }}
                            @else
                                {{ $tanque['tipo_tanque_id'] ?? '-' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tipo de Medición:</th>
                        <td>{{ $tanque['tipo_medicion'] ?? '-' }}</td>
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
                <h5 class="card-title mb-0">Capacidades</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Capacidad Total:</th>
                        <td>{{ number_format($tanque['capacidad_total'], 2) }} L</td>
                    </tr>
                    <tr>
                        <th>Capacidad Útil:</th>
                        <td>{{ number_format($tanque['capacidad_util'], 2) }} L</td>
                    </tr>
                    <tr>
                        <th>Capacidad Operativa:</th>
                        <td>{{ number_format($tanque['capacidad_operativa'], 2) }} L</td>
                    </tr>
                    <tr>
                        <th>Capacidad Mínima:</th>
                        <td>{{ number_format($tanque['capacidad_minima'], 2) }} L</td>
                    </tr>
                    <tr>
                        <th>Capacidad Gas Talón:</th>
                        <td>{{ $tanque['capacidad_gas_talon'] ? number_format($tanque['capacidad_gas_talon'], 2) . ' L' : 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="card-title mb-0">Condiciones de Operación</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Temperatura Referencia:</th>
                        <td>{{ $tanque['temperatura_referencia'] }} °C</td>
                    </tr>
                    <tr>
                        <th>Presión Referencia:</th>
                        <td>{{ $tanque['presion_referencia'] }} bar</td>
                    </tr>
                    <tr>
                        <th>Incertidumbre Medición:</th>
                        <td>{{ $tanque['incertidumbre_medicion'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Certificado Calibración:</th>
                        <td>{{ $tanque['certificado_calibracion'] ?? 'No disponible' }}</td>
                    </tr>
                    <tr>
                        <th>Entidad Calibración:</th>
                        <td>{{ $tanque['entidad_calibracion'] ?? 'No disponible' }}</td>
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
                <h5 class="card-title mb-0">Fechas Importantes</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Fecha Fabricación:</th>
                        <td>{{ $tanque['fecha_fabricacion'] ?? 'No registrada' }}</td>
                    </tr>
                    <tr>
                        <th>Fecha Instalación:</th>
                        <td>{{ $tanque['fecha_instalacion'] ?? 'No registrada' }}</td>
                    </tr>
                    <tr>
                        <th>Última Calibración:</th>
                        <td>{{ $tanque['fecha_ultima_calibracion'] ?? 'No registrada' }}</td>
                    </tr>
                    <tr>
                        <th>Próxima Calibración:</th>
                        <td>
                            @if(isset($tanque['fecha_proxima_calibracion']))
                                @php
                                    $dias = now()->diffInDays($tanque['fecha_proxima_calibracion'], false);
                                    $badgeClass = $dias < 7 ? 'danger' : ($dias < 15 ? 'warning' : 'success');
                                @endphp
                                {{ $tanque['fecha_proxima_calibracion'] }}
                                <span class="badge bg-{{ $badgeClass }}">{{ round($dias) }} días</span>
                            @else
                                No programada
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0">Observaciones</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $tanque['observaciones'] ?? 'Sin observaciones' }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Dispensarios conectados -->
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-fuel-pump me-2"></i>
                    Dispensarios Conectados
                </h5>
                <span class="badge bg-light text-dark">{{ count($tanque['dispensarios'] ?? []) }} dispensarios</span>
            </div>
            <div class="card-body">
                @if(!empty($tanque['dispensarios']))
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Conexión Regulatoria:</strong> Este tanque está conectado a los siguientes dispensarios 
                        según lo requerido por el Anexo 21 de la Resolución Miscelánea Fiscal para la conciliación diaria de existencias.
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Clave Dispensario</th>
                                    <th>Instalación</th>
                                    <th>Modelo</th>
                                    <th>Estado del Dispensario</th>
                                    <th>Estado Conexión</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tanque['dispensarios'] as $dispensario)
                                    <tr>
                                        <td>
                                            <strong class="text-primary">{{ $dispensario['clave'] }}</strong>
                                        </td>
                                        <td>
                                            @if(isset($dispensario['instalacion']))
                                                {{ $dispensario['instalacion']['nombre'] }}
                                            @else
                                                {{ $dispensario['instalacion_id'] }}
                                            @endif
                                        </td>
                                        <td>{{ $dispensario['modelo'] ?? '-' }}</td>
                                        <td>
                                            @php
                                                $estadoDispensarioClass = [
                                                    'OPERATIVO' => 'success',
                                                    'MANTENIMIENTO' => 'warning',
                                                    'FUERA_SERVICIO' => 'danger'
                                                ][$dispensario['estado']] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $estadoDispensarioClass }}">{{ $dispensario['estado'] }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $pivotActivo = $dispensario['pivot']['activo'] ?? true;
                                            @endphp
                                            <span class="badge bg-{{ $pivotActivo ? 'success' : 'secondary' }}">
                                                {{ $pivotActivo ? 'Conectado' : 'Desconectado' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('dispensarios.show', $dispensario['id']) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Advertencia:</strong> Este tanque no tiene dispensarios conectados. 
                        Según el Anexo 21, debe estar conectado a al menos un dispensario para realizar 
                        la conciliación diaria de existencias.
                    </div>
                    <p class="text-muted mb-0">
                        <a href="{{ route('dispensarios.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i>
                            Crear Dispensario
                        </a>
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Medidores asociados -->
@if(!empty($tanque['medidores']))
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Medidores Asociados</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Clave</th>
                                <th>Número de Serie</th>
                                <th>Modelo</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tanque['medidores'] as $medidor)
                                <tr>
                                    <td>{{ $medidor['clave'] }}</td>
                                    <td>{{ $medidor['numero_serie'] }}</td>
                                    <td>{{ $medidor['modelo'] ?? '-' }}</td>
                                    <td>
                                        @php
                                            $estadoMedidorClass = [
                                                'OPERATIVO' => 'success',
                                                'CALIBRACION' => 'info',
                                                'MANTENIMIENTO' => 'warning',
                                                'FUERA_SERVICIO' => 'danger',
                                                'FALLA_COMUNICACION' => 'secondary'
                                            ][$medidor['estado']] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $estadoMedidorClass }}">{{ $medidor['estado'] }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('medidores.show', $medidor['id']) }}" class="btn btn-sm btn-info">
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

<!-- Botón de eliminar -->
<form method="POST" action="{{ route('tanques.destroy', $tanque['id']) }}" class="d-inline" 
      onsubmit="return confirm('¿Está seguro de eliminar este tanque? Esta acción no se puede deshacer.');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">
        <i class="bi bi-trash"></i> Eliminar Tanque
    </button>
</form>
@endsection