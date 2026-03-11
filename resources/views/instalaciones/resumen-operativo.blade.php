@extends('layouts.app')

@section('title', 'Resumen Operativo')
@section('header', 'Resumen Operativo de la Instalación')

@section('actions')
<a href="{{ route('instalaciones.show', $resumen['instalacion_id']) }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver a la Instalación
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
                        <th style="width: 40%">Instalación:</th>
                        <td><strong>{{ $resumen['instalacion_nombre'] }}</strong></td>
                    </tr>
                    <tr>
                        <th>Clave:</th>
                        <td>{{ $resumen['instalacion_clave'] }}</td>
                    </tr>
                    <tr>
                        <th>Contribuyente:</th>
                        <td>{{ $resumen['contribuyente'] }}</td>
                    </tr>
                    <tr>
                        <th>Estatus:</th>
                        <td>
                            @php
                                $estatusClass = [
                                    'OPERACION' => 'success',
                                    'SUSPENDIDA' => 'warning',
                                    'CANCELADA' => 'danger'
                                ][$resumen['estatus']] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $estatusClass }}">{{ $resumen['estatus'] }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Resumen de Equipos</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>Total Tanques:</th>
                        <td><span class="badge bg-primary">{{ $resumen['total_tanques'] }}</span></td>
                    </tr>
                    <tr>
                        <th>Tanques Operativos:</th>
                        <td><span class="badge bg-success">{{ $resumen['tanques_operativos'] }}</span></td>
                    </tr>
                    <tr>
                        <th>Tanques en Mantenimiento:</th>
                        <td><span class="badge bg-warning">{{ $resumen['tanques_mantenimiento'] }}</span></td>
                    </tr>
                    <tr>
                        <th>Tanques Fuera de Servicio:</th>
                        <td><span class="badge bg-danger">{{ $resumen['tanques_fuera_servicio'] }}</span></td>
                    </tr>
                </table>
                <hr>
                <table class="table table-sm">
                    <tr>
                        <th>Total Medidores:</th>
                        <td><span class="badge bg-primary">{{ $resumen['total_medidores'] }}</span></td>
                    </tr>
                    <tr>
                        <th>Medidores Operativos:</th>
                        <td><span class="badge bg-success">{{ $resumen['medidores_operativos'] }}</span></td>
                    </tr>
                    <tr>
                        <th>Medidores en Mantenimiento:</th>
                        <td><span class="badge bg-warning">{{ $resumen['medidores_mantenimiento'] }}</span></td>
                    </tr>
                </table>
                <hr>
                <table class="table table-sm">
                    <tr>
                        <th>Total Dispensarios:</th>
                        <td><span class="badge bg-primary">{{ $resumen['total_dispensarios'] }}</span></td>
                    </tr>
                    <tr>
                        <th>Dispensarios Operativos:</th>
                        <td><span class="badge bg-success">{{ $resumen['dispensarios_operativos'] }}</span></td>
                    </tr>
                    <tr>
                        <th>Total Mangueras:</th>
                        <td><span class="badge bg-info">{{ $resumen['total_mangueras'] }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Resumen de Productos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Volumen Total</th>
                                <th>% Ocupación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($resumen['productos'] as $producto)
                                <tr>
                                    <td>{{ $producto['nombre'] }}</td>
                                    <td><strong>{{ number_format($producto['volumen_total'], 3) }} L</strong></td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ $producto['porcentaje'] }}%">{{ number_format($producto['porcentaje'], 1) }}%</div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No hay productos con inventario</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="card-title mb-0">Alertas y Pendientes</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @if($resumen['alarmas_activas'] > 0)
                        <a href="{{ route('alarmas.activas', ['instalacion_id' => $resumen['instalacion_id']]) }}" 
                           class="list-group-item list-group-item-action list-group-item-danger">
                            <i class="bi bi-exclamation-triangle"></i> {{ $resumen['alarmas_activas'] }} Alarmas Activas
                        </a>
                    @endif
                    
                    @if($resumen['tanques_bajo_nivel'] > 0)
                        <a href="#" class="list-group-item list-group-item-action list-group-item-warning">
                            <i class="bi bi-arrow-down"></i> {{ $resumen['tanques_bajo_nivel'] }} Tanques con Nivel Bajo
                        </a>
                    @endif
                    
                    @if($resumen['tanques_alto_nivel'] > 0)
                        <a href="#" class="list-group-item list-group-item-action list-group-item-warning">
                            <i class="bi bi-arrow-up"></i> {{ $resumen['tanques_alto_nivel'] }} Tanques con Nivel Alto
                        </a>
                    @endif
                    
                    @if($resumen['calibraciones_pendientes'] > 0)
                        <a href="#" class="list-group-item list-group-item-action list-group-item-warning">
                            <i class="bi bi-calendar"></i> {{ $resumen['calibraciones_pendientes'] }} Calibraciones Pendientes
                        </a>
                    @endif
                    
                    @if($resumen['mantenimientos_pendientes'] > 0)
                        <a href="#" class="list-group-item list-group-item-action list-group-item-warning">
                            <i class="bi bi-tools"></i> {{ $resumen['mantenimientos_pendientes'] }} Mantenimientos Pendientes
                        </a>
                    @endif
                    
                    @if($resumen['registros_pendientes_validar'] > 0)
                        <a href="{{ route('existencias.index', ['estado' => 'PENDIENTE', 'instalacion_id' => $resumen['instalacion_id']]) }}" 
                           class="list-group-item list-group-item-action list-group-item-info">
                            <i class="bi bi-clock"></i> {{ $resumen['registros_pendientes_validar'] }} Registros Pendientes de Validar
                        </a>
                    @endif
                    
                    @if($resumen['alarmas_activas'] == 0 && $resumen['tanques_bajo_nivel'] == 0 && $resumen['tanques_alto_nivel'] == 0 && 
                        $resumen['calibraciones_pendientes'] == 0 && $resumen['mantenimientos_pendientes'] == 0 && $resumen['registros_pendientes_validar'] == 0)
                        <div class="list-group-item list-group-item-success">
                            <i class="bi bi-check-circle"></i> No hay alertas activas
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">Volumen por Producto</h5>
            </div>
            <div class="card-body">
                <canvas id="productosChart" height="250"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">Estado de Equipos</h5>
            </div>
            <div class="card-body">
                <canvas id="equiposChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Últimas Operaciones</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Fecha/Hora</th>
                                <th>Tipo</th>
                                <th>Tanque</th>
                                <th>Producto</th>
                                <th>Volumen</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($resumen['ultimos_registros'] as $registro)
                                <tr>
                                    <td>{{ $registro['fecha'] }} {{ $registro['hora'] }}</td>
                                    <td>{{ $registro['tipo_movimiento'] }}</td>
                                    <td>{{ $registro['tanque'] }}</td>
                                    <td>{{ $registro['producto'] }}</td>
                                    <td>{{ number_format($registro['volumen'], 3) }} L</td>
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
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No hay operaciones recientes</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Gráfico de productos
    new Chart(document.getElementById('productosChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_column($resumen['productos'] ?? [], 'nombre')) !!},
            datasets: [{
                data: {!! json_encode(array_column($resumen['productos'] ?? [], 'volumen_total')) !!},
                backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#0dcaf0']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
    
    // Gráfico de equipos
    new Chart(document.getElementById('equiposChart'), {
        type: 'bar',
        data: {
            labels: ['Tanques', 'Medidores', 'Dispensarios'],
            datasets: [
                {
                    label: 'Operativos',
                    data: [
                        {{ $resumen['tanques_operativos'] }},
                        {{ $resumen['medidores_operativos'] }},
                        {{ $resumen['dispensarios_operativos'] }}
                    ],
                    backgroundColor: '#198754'
                },
                {
                    label: 'Mantenimiento',
                    data: [
                        {{ $resumen['tanques_mantenimiento'] }},
                        {{ $resumen['medidores_mantenimiento'] }},
                        0
                    ],
                    backgroundColor: '#ffc107'
                },
                {
                    label: 'Fuera de Servicio',
                    data: [
                        {{ $resumen['tanques_fuera_servicio'] }},
                        0,
                        0
                    ],
                    backgroundColor: '#dc3545'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush