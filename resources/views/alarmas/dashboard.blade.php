@extends('layouts.app')

@section('title', 'Dashboard de Alarmas')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Dashboard de Alarmas</h2>
    </div>
</div>

<!-- Tarjetas de resumen -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Alarmas Activas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboard['activas'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Atendidas Hoy</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboard['atendidas_hoy'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Tiempo Promedio</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboard['tiempo_promedio'] ?? 0 }} min</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Total del Mes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboard['total_mes'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráficas -->
<div class="row mb-4">
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Alarmas por Día</h6>
            </div>
            <div class="card-body">
                <canvas id="alarmasChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Distribución por Tipo</h6>
            </div>
            <div class="card-body">
                <canvas id="tipoChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Alarmas por instalación -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Alarmas por Instalación</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Instalación</th>
                                <th>Activas</th>
                                <th>Atendidas</th>
                                <th>Descartadas</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dashboard['por_instalacion'] ?? [] as $instalacion)
                            <tr>
                                <td>{{ $instalacion['nombre'] }}</td>
                                <td class="text-center">
                                    <span class="badge bg-danger">{{ $instalacion['activas'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $instalacion['atendidas'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $instalacion['descartadas'] }}</span>
                                </td>
                                <td class="text-center">{{ $instalacion['total'] }}</td>
                                <td class="text-center">
                                    <a href="{{ route('alarmas.index', ['instalacion_id' => $instalacion['id']]) }}" 
                                       class="btn btn-info btn-sm">
                                        Ver alarmas
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

<!-- Últimas alarmas -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Últimas 10 Alarmas</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Fecha/Hora</th>
                                <th>Instalación</th>
                                <th>Tipo</th>
                                <th>Mensaje</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dashboard['ultimas_alarmas'] ?? [] as $alarma)
                            <tr>
                                <td>{{ $alarma['fecha_alarma'] }} {{ $alarma['hora_alarma'] }}</td>
                                <td>{{ $alarma['instalacion']['nombre'] ?? 'N/A' }}</td>
                                <td>
                                    @switch($alarma['tipo_alarma'])
                                        @case('nivel')
                                            <span class="badge bg-warning">Nivel</span>
                                            @break
                                        @case('temperatura')
                                            <span class="badge bg-danger">Temperatura</span>
                                            @break
                                        @case('presion')
                                            <span class="badge bg-info">Presión</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $alarma['tipo_alarma'] }}</span>
                                    @endswitch
                                </td>
                                <td>{{ $alarma['mensaje'] }}</td>
                                <td>
                                    @if($alarma['estado'] == 'activa')
                                        <span class="badge bg-danger">Activa</span>
                                    @elseif($alarma['estado'] == 'atendida')
                                        <span class="badge bg-success">Atendida</span>
                                    @else
                                        <span class="badge bg-secondary">Descartada</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('alarmas.show', $alarma['id']) }}" 
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Gráfica de alarmas por día
    var ctx1 = document.getElementById('alarmasChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: {!! json_encode($dashboard['grafica']['dias'] ?? []) !!},
            datasets: [{
                label: 'Alarmas',
                data: {!! json_encode($dashboard['grafica']['valores'] ?? []) !!},
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Cantidad'
                    }
                }
            }
        }
    });

    // Gráfica de distribución por tipo
    var ctx2 = document.getElementById('tipoChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($dashboard['por_tipo']['labels'] ?? []) !!},
            datasets: [{
                data: {!! json_encode($dashboard['por_tipo']['valores'] ?? []) !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Actualización automática cada 60 segundos
    setInterval(function() {
        if (document.visibilityState === 'visible') {
            location.reload();
        }
    }, 60000);
});
</script>
@endpush