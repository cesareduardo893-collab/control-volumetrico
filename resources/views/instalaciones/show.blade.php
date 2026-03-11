@extends('layouts.app')

@section('title', 'Detalle de Instalación')
@section('header', 'Detalle de Instalación')

@section('actions')
<a href="{{ route('instalaciones.edit', $instalacion['id']) }}" class="btn btn-sm btn-warning">
    <i class="bi bi-pencil"></i> Editar
</a>
<a href="{{ route('instalaciones.tanques', $instalacion['id']) }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-barrel"></i> Tanques
</a>
<a href="{{ route('instalaciones.medidores', $instalacion['id']) }}" class="btn btn-sm btn-primary">
    <i class="bi bi-speedometer"></i> Medidores
</a>
<a href="{{ route('instalaciones.dispensarios', $instalacion['id']) }}" class="btn btn-sm btn-success">
    <i class="bi bi-fuel-pump"></i> Dispensarios
</a>
<a href="{{ route('instalaciones.resumen-operativo', $instalacion['id']) }}" class="btn btn-sm btn-info">
    <i class="bi bi-graph-up"></i> Resumen Operativo
</a>
<a href="{{ route('instalaciones.index') }}" class="btn btn-sm btn-secondary">
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
                        <td><strong>{{ $instalacion['clave_instalacion'] }}</strong></td>
                    </tr>
                    <tr>
                        <th>Nombre:</th>
                        <td>{{ $instalacion['nombre'] }}</td>
                    </tr>
                    <tr>
                        <th>Tipo:</th>
                        <td>{{ ucfirst(str_replace('_', ' ', $instalacion['tipo_instalacion'])) }}</td>
                    </tr>
                    <tr>
                        <th>Estatus:</th>
                        <td>
                            @php
                                $estatusClass = [
                                    'OPERACION' => 'success',
                                    'SUSPENDIDA' => 'warning',
                                    'CANCELADA' => 'danger'
                                ][$instalacion['estatus']] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $estatusClass }}">{{ $instalacion['estatus'] }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Activo:</th>
                        <td>
                            @if($instalacion['activo'])
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
                <h5 class="card-title mb-0">Contribuyente</h5>
            </div>
            <div class="card-body">
                @if(isset($instalacion['contribuyente']))
                    <table class="table table-sm">
                        <tr>
                            <th style="width: 40%">RFC:</th>
                            <td>{{ $instalacion['contribuyente']['rfc'] }}</td>
                        </tr>
                        <tr>
                            <th>Razón Social:</th>
                            <td>{{ $instalacion['contribuyente']['razon_social'] }}</td>
                        </tr>
                        <tr>
                            <th>Régimen Fiscal:</th>
                            <td>{{ $instalacion['contribuyente']['regimen_fiscal'] }}</td>
                        </tr>
                    </table>
                @else
                    <p class="text-muted">ID de Contribuyente: {{ $instalacion['contribuyente_id'] }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Domicilio</h5>
            </div>
            <div class="card-body">
                <address>
                    <strong>{{ $instalacion['domicilio'] }}</strong><br>
                    {{ $instalacion['codigo_postal'] }}, {{ $instalacion['municipio'] }}<br>
                    {{ $instalacion['estado'] }}
                </address>
                @if(!empty($instalacion['telefono']) || !empty($instalacion['email']))
                    <hr>
                    @if(!empty($instalacion['telefono']))
                        <p><i class="bi bi-telephone"></i> {{ $instalacion['telefono'] }}</p>
                    @endif
                    @if(!empty($instalacion['email']))
                        <p><i class="bi bi-envelope"></i> {{ $instalacion['email'] }}</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="card-title mb-0">Fechas</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Fecha de Apertura:</th>
                        <td>{{ $instalacion['fecha_apertura'] ?? 'No registrada' }}</td>
                    </tr>
                    <tr>
                        <th>Fecha de Cierre:</th>
                        <td>{{ $instalacion['fecha_cierre'] ?? 'No registrada' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Resumen de equipos -->
<div class="row">
    <div class="col-md-3">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body text-center">
                <i class="bi bi-barrel fs-1"></i>
                <h3>{{ $instalacion['total_tanques'] ?? 0 }}</h3>
                <h6>Tanques</h6>
                @if(($instalacion['total_tanques'] ?? 0) > 0)
                    <a href="{{ route('instalaciones.tanques', $instalacion['id']) }}" class="btn btn-sm btn-light mt-2">Ver Tanques</a>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white mb-4">
            <div class="card-body text-center">
                <i class="bi bi-speedometer fs-1"></i>
                <h3>{{ $instalacion['total_medidores'] ?? 0 }}</h3>
                <h6>Medidores</h6>
                @if(($instalacion['total_medidores'] ?? 0) > 0)
                    <a href="{{ route('instalaciones.medidores', $instalacion['id']) }}" class="btn btn-sm btn-light mt-2">Ver Medidores</a>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white mb-4">
            <div class="card-body text-center">
                <i class="bi bi-fuel-pump fs-1"></i>
                <h3>{{ $instalacion['total_dispensarios'] ?? 0 }}</h3>
                <h6>Dispensarios</h6>
                @if(($instalacion['total_dispensarios'] ?? 0) > 0)
                    <a href="{{ route('instalaciones.dispensarios', $instalacion['id']) }}" class="btn btn-sm btn-light mt-2">Ver Dispensarios</a>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white mb-4">
            <div class="card-body text-center">
                <i class="bi bi-exclamation-triangle fs-1"></i>
                <h3>{{ $instalacion['alarmas_activas'] ?? 0 }}</h3>
                <h6>Alarmas Activas</h6>
                @if(($instalacion['alarmas_activas'] ?? 0) > 0)
                    <a href="{{ route('alarmas.activas', ['instalacion_id' => $instalacion['id']]) }}" class="btn btn-sm btn-light mt-2">Ver Alarmas</a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Últimos registros volumétricos -->
@if(!empty($instalacion['ultimos_registros']))
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">Últimos Registros Volumétricos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>N° Registro</th>
                                <th>Tanque</th>
                                <th>Producto</th>
                                <th>Volumen</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($instalacion['ultimos_registros'] as $registro)
                                <tr>
                                    <td>{{ $registro['fecha'] }} {{ $registro['hora'] }}</td>
                                    <td>{{ $registro['numero_registro'] }}</td>
                                    <td>{{ $registro['tanque']['identificador'] ?? $registro['tanque_id'] }}</td>
                                    <td>{{ $registro['producto']['nombre'] ?? $registro['producto_id'] }}</td>
                                    <td>{{ number_format($registro['volumen_operacion'], 3) }} L</td>
                                    <td>
                                        @php
                                            $estadoClass = [
                                                'VALIDADO' => 'success',
                                                'PENDIENTE' => 'warning',
                                                'ERROR' => 'danger'
                                            ][$registro['estado']] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $estadoClass }}">{{ $registro['estado'] }}</span>
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

<!-- Botón de eliminar (solo si no tiene equipos asociados) -->
@if(($instalacion['total_tanques'] ?? 0) == 0 && ($instalacion['total_medidores'] ?? 0) == 0 && ($instalacion['total_dispensarios'] ?? 0) == 0)
<form method="POST" action="{{ route('instalaciones.destroy', $instalacion['id']) }}" class="d-inline" 
      onsubmit="return confirm('¿Está seguro de eliminar esta instalación? Esta acción no se puede deshacer.');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">
        <i class="bi bi-trash"></i> Eliminar Instalación
    </button>
</form>
@endif
@endsection