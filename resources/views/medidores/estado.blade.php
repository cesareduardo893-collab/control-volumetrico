@extends('layouts.app')

@section('title', 'Estado del Medidor')
@section('header', 'Verificación de Estado del Medidor')

@section('actions')
<a href="{{ route('medidores.show', $medidor_id) }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver al Medidor
</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header 
                @if($estado['estado_general'] == 'OPERATIVO') bg-success 
                @elseif($estado['estado_general'] == 'CALIBRACION') bg-info
                @elseif($estado['estado_general'] == 'MANTENIMIENTO') bg-warning
                @else bg-danger 
                @endif text-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-speedometer2"></i> 
                    Resultado de Verificación de Estado
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    @if($estado['estado_general'] == 'OPERATIVO')
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                        <h3 class="text-success mt-3">MEDIDOR OPERATIVO</h3>
                    @elseif($estado['estado_general'] == 'CALIBRACION')
                        <i class="bi bi-gear-fill text-info" style="font-size: 5rem;"></i>
                        <h3 class="text-info mt-3">EN CALIBRACIÓN</h3>
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
                                <h6 class="mb-0">Información del Medidor</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <th>Clave:</th>
                                        <td>{{ $estado['clave'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>N° Serie:</th>
                                        <td>{{ $estado['numero_serie'] }}</td>
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
                                                    'CALIBRACION' => 'info',
                                                    'MANTENIMIENTO' => 'warning',
                                                    'FUERA_SERVICIO' => 'danger',
                                                    'FALLA_COMUNICACION' => 'secondary'
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
                                        <th>Precisión actual:</th>
                                        <td>{{ $estado['precision_actual'] }}%</td>
                                    </tr>
                                    <tr>
                                        <th>Desviación:</th>
                                        <td>
                                            @php
                                                $desviacionClass = $estado['desviacion'] > 1 ? 'danger' : ($estado['desviacion'] > 0.5 ? 'warning' : 'success');
                                            @endphp
                                            <span class="text-{{ $desviacionClass }}">{{ $estado['desviacion'] }}%</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Total de lecturas:</th>
                                        <td>{{ number_format($estado['total_lecturas']) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Promedio diario:</th>
                                        <td>{{ number_format($estado['promedio_diario']) }} L</td>
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
                                <h6 class="mb-0">Calibración</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <th>Días desde última calibración:</th>
                                        <td>
                                            @if(isset($estado['dias_ultima_calibracion']))
                                                <span class="badge bg-{{ $estado['dias_ultima_calibracion'] > 365 ? 'danger' : 'warning' }}">
                                                    {{ $estado['dias_ultima_calibracion'] }} días
                                                </span>
                                            @else
                                                No registrada
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Días hasta próxima calibración:</th>
                                        <td>
                                            @if(isset($estado['dias_proxima_calibracion']))
                                                @if($estado['dias_proxima_calibracion'] < 0)
                                                    <span class="badge bg-danger">Vencida hace {{ abs($estado['dias_proxima_calibracion']) }} días</span>
                                                @elseif($estado['dias_proxima_calibracion'] < 15)
                                                    <span class="badge bg-warning">{{ $estado['dias_proxima_calibracion'] }} días (próxima)</span>
                                                @else
                                                    <span class="badge bg-success">{{ $estado['dias_proxima_calibracion'] }} días</span>
                                                @endif
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
                    <a href="{{ route('medidores.show', $medidor_id) }}" class="btn btn-primary">
                        <i class="bi bi-eye"></i> Ver Medidor
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection