@extends('layouts.app')

@section('title', 'Inventario Actual')
@section('header', 'Inventario Actual por Tanque')

@section('actions')
<a href="{{ route('existencias.historico', $inventario['tanque_id']) }}?fecha_inicio={{ now()->subDays(30)->toDateString() }}&fecha_fin={{ now()->toDateString() }}" class="btn btn-sm btn-info">
    <i class="bi bi-clock-history"></i> Ver Histórico
</a>
<a href="{{ route('existencias.index') }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Información del Tanque</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Identificador:</th>
                        <td><strong>{{ $inventario['tanque']['identificador'] }}</strong></td>
                    </tr>
                    <tr>
                        <th>Instalación:</th>
                        <td>{{ $inventario['tanque']['instalacion']['nombre'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Producto:</th>
                        <td>{{ $inventario['tanque']['producto']['nombre'] ?? 'No asignado' }}</td>
                    </tr>
                    <tr>
                        <th>Capacidad Total:</th>
                        <td>{{ number_format($inventario['tanque']['capacidad_total'], 3) }} L</td>
                    </tr>
                    <tr>
                        <th>Capacidad Util:</th>
                        <td>{{ number_format($inventario['tanque']['capacidad_util'], 3) }} L</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Inventario Actual</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Volumen Disponible:</th>
                        <td>
                            <h3 class="text-primary">{{ number_format($inventario['volumen_disponible'], 3) }} L</h3>
                        </td>
                    </tr>
                    <tr>
                        <th>Volumen Corregido:</th>
                        <td>{{ number_format($inventario['volumen_corregido'], 3) }} L</td>
                    </tr>
                    <tr>
                        <th>Porcentaje de Ocupación:</th>
                        <td>
                            @php
                                $porcentaje = ($inventario['volumen_disponible'] / $inventario['tanque']['capacidad_util']) * 100;
                                $barClass = $porcentaje > 90 ? 'danger' : ($porcentaje > 75 ? 'warning' : 'success');
                            @endphp
                            <div class="progress mb-2">
                                <div class="progress-bar bg-{{ $barClass }}" role="progressbar" 
                                     style="width: {{ $porcentaje }}%">{{ number_format($porcentaje, 1) }}%</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Última Actualización:</th>
                        <td>{{ $inventario['ultima_actualizacion'] ?? 'No disponible' }}</td>
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
                <h5 class="card-title mb-0">Temperatura y Densidad</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Temperatura:</th>
                        <td>{{ number_format($inventario['temperatura'] ?? 0, 1) }} °C</td>
                    </tr>
                    <tr>
                        <th>Densidad:</th>
                        <td>{{ number_format($inventario['densidad'] ?? 0, 4) }} kg/L</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="card-title mb-0">Alertas</h5>
            </div>
            <div class="card-body">
                @php
                    $alertas = [];
                    $porcentaje = ($inventario['volumen_disponible'] / $inventario['tanque']['capacidad_util']) * 100;
                    
                    if ($porcentaje > 95) {
                        $alertas[] = ['tipo' => 'danger', 'mensaje' => 'Tanque casi lleno (>95%)'];
                    } elseif ($porcentaje < 10) {
                        $alertas[] = ['tipo' => 'danger', 'mensaje' => 'Nivel crítico bajo (<10%)'];
                    } elseif ($porcentaje < 20) {
                        $alertas[] = ['tipo' => 'warning', 'mensaje' => 'Nivel bajo (<20%)'];
                    }
                    
                    if (isset($inventario['dias_sin_actualizar']) && $inventario['dias_sin_actualizar'] > 1) {
                        $alertas[] = ['tipo' => 'warning', 'mensaje' => 'Sin actualización por ' . $inventario['dias_sin_actualizar'] . ' días'];
                    }
                @endphp
                
                @if(count($alertas) > 0)
                    @foreach($alertas as $alerta)
                        <div class="alert alert-{{ $alerta['tipo'] }} mb-2">
                            <i class="bi bi-exclamation-triangle"></i> {{ $alerta['mensaje'] }}
                        </div>
                    @endforeach
                @else
                    <p class="text-success mb-0">
                        <i class="bi bi-check-circle"></i> Sin alertas activas
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">Últimos 10 Movimientos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Fecha/Hora</th>
                                <th>Tipo Movimiento</th>
                                <th>Volumen</th>
                                <th>Volumen Disponible</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventario['ultimos_movimientos'] ?? [] as $movimiento)
                                <tr>
                                    <td>{{ $movimiento['fecha'] }} {{ $movimiento['hora'] }}</td>
                                    <td>
                                        <span class="badge bg-{{ $movimiento['tipo_movimiento'] == 'ENTRADA' ? 'success' : 'danger' }}">
                                            {{ $movimiento['tipo_movimiento'] }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($movimiento['volumen'], 3) }} L</td>
                                    <td>{{ number_format($movimiento['volumen_disponible'], 3) }} L</td>
                                    <td>
                                        @php
                                            $estadoClass = [
                                                'VALIDADO' => 'success',
                                                'PENDIENTE' => 'warning',
                                                'EN_REVISION' => 'info',
                                                'CON_ALARMA' => 'danger'
                                            ][$movimiento['estado']] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $estadoClass }}">{{ $movimiento['estado'] }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No hay movimientos registrados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Gráfica de Evolución</h5>
            </div>
            <div class="card-body">
                <canvas id="evolucionChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    @if(!empty($inventario['evolucion']))
    new Chart(document.getElementById('evolucionChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($inventario['evolucion']['fechas'] ?? []) !!},
            datasets: [{
                label: 'Volumen Disponible (L)',
                data: {!! json_encode($inventario['evolucion']['volumenes'] ?? []) !!},
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
    @endif
});
</script>
@endpush