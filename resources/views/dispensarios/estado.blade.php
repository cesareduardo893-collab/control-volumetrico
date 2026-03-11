@extends('layouts.app')

@section('title', 'Estado del Dispensario')
@section('header', 'Verificación de Estado del Dispensario')

@section('actions')
<a href="{{ route('dispensarios.show', $dispensario_id) }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver al Dispensario
</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-check-circle"></i> 
                    Resultado de Verificación de Estado
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    @if($estado['estado_general'] == 'OPERATIVO')
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                        <h3 class="text-success mt-3">DISPENSARIO OPERATIVO</h3>
                    @elseif($estado['estado_general'] == 'MANTENIMIENTO')
                        <i class="bi bi-tools text-warning" style="font-size: 5rem;"></i>
                        <h3 class="text-warning mt-3">EN MANTENIMIENTO</h3>
                    @else
                        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 5rem;"></i>
                        <h3 class="text-danger mt-3">FUERA DE SERVICIO</h3>
                    @endif
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Información del Dispensario</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <th>Clave:</th>
                                        <td>{{ $estado['clave'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Modelo:</th>
                                        <td>{{ $estado['modelo'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Estado actual:</th>
                                        <td>
                                            @php
                                                $badgeClass = [
                                                    'OPERATIVO' => 'success',
                                                    'MANTENIMIENTO' => 'warning',
                                                    'FUERA_SERVICIO' => 'danger'
                                                ][$estado['estado_actual']] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $badgeClass }}">{{ $estado['estado_actual'] }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Estadísticas</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <th>Total Mangueras:</th>
                                        <td>{{ $estado['total_mangueras'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Mangueras Operativas:</th>
                                        <td><span class="badge bg-success">{{ $estado['mangueras_operativas'] }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Mangueras en Mantenimiento:</th>
                                        <td><span class="badge bg-warning">{{ $estado['mangueras_mantenimiento'] }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Mangueras Fuera de Servicio:</th>
                                        <td><span class="badge bg-danger">{{ $estado['mangueras_fuera_servicio'] }}</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Mantenimiento</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <th>Días desde último mantenimiento:</th>
                                        <td>
                                            @if(isset($estado['dias_ultimo_mantenimiento']))
                                                <span class="badge bg-{{ $estado['dias_ultimo_mantenimiento'] > 30 ? 'warning' : 'success' }}">
                                                    {{ $estado['dias_ultimo_mantenimiento'] }} días
                                                </span>
                                            @else
                                                No registrado
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Días hasta próximo mantenimiento:</th>
                                        <td>
                                            @if(isset($estado['dias_proximo_mantenimiento']))
                                                @if($estado['dias_proximo_mantenimiento'] < 0)
                                                    <span class="badge bg-danger">Vencido hace {{ abs($estado['dias_proximo_mantenimiento']) }} días</span>
                                                @elseif($estado['dias_proximo_mantenimiento'] < 7)
                                                    <span class="badge bg-warning">{{ $estado['dias_proximo_mantenimiento'] }} días (próximo)</span>
                                                @else
                                                    <span class="badge bg-success">{{ $estado['dias_proximo_mantenimiento'] }} días</span>
                                                @endif
                                            @else
                                                No programado
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Alertas</h6>
                            </div>
                            <div class="card-body">
                                @if(!empty($estado['alertas']))
                                    <ul class="list-unstyled">
                                        @foreach($estado['alertas'] as $alerta)
                                            <li class="text-danger">
                                                <i class="bi bi-exclamation-triangle"></i> {{ $alerta }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-success mb-0">
                                        <i class="bi bi-check-circle"></i> No hay alertas activas
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                @if(!empty($estado['recomendaciones']))
                <div class="alert alert-info mt-3">
                    <h6><i class="bi bi-info-circle"></i> Recomendaciones:</h6>
                    <ul class="mb-0">
                        @foreach($estado['recomendaciones'] as $recomendacion)
                            <li>{{ $recomendacion }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <hr>
                
                <div class="d-flex justify-content-center">
                    <a href="{{ route('dispensarios.show', $dispensario_id) }}" class="btn btn-primary">
                        <i class="bi bi-eye"></i> Ver Dispensario
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection