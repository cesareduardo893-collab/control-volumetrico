@extends('layouts.app')

@section('title', 'Dashboard de Bitácora')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Dashboard de Bitácora</h2>
    </div>
</div>

<!-- Tarjetas de resumen -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Eventos Hoy</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboard['eventos_hoy'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
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
                            Usuarios Activos Hoy</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboard['usuarios_activos'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
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
                            Eventos este Mes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboard['eventos_mes'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
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
                            Eventos Críticos</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboard['eventos_criticos'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
                <h6 class="m-0 font-weight-bold text-primary">Eventos por Día (Últimos 30 días)</h6>
            </div>
            <div class="card-body">
                <canvas id="eventosChart"></canvas>
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

<!-- Tablas de actividad -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Usuarios más Activos</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Eventos</th>
                                <th>Última Actividad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dashboard['usuarios_activos_top'] ?? [] as $usuario)
                            <tr>
                                <td>{{ $usuario['name'] }}</td>
                                <td class="text-center">{{ $usuario['eventos'] }}</td>
                                <td>{{ $usuario['ultima_actividad'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Módulos más Accedidos</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Módulo</th>
                                <th>Accesos</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = array_sum(array_column($dashboard['modulos_top'] ?? [], 'accesos')); @endphp
                            @foreach($dashboard['modulos_top'] ?? [] as $modulo)
                            <tr>
                                <td>{{ $modulo['modulo'] }}</td>
                                <td class="text-center">{{ $modulo['accesos'] }}</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ $total > 0 ? ($modulo['accesos'] * 100 / $total) : 0 }}%">
                                            {{ number_format($total > 0 ? ($modulo['accesos'] * 100 / $total) : 0, 1) }}%
                                        </div>
                                    </div>
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

<!-- Últimos eventos críticos -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Últimos Eventos Críticos</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Fecha/Hora</th>
                                <th>Usuario</th>
                                <th>Tipo</th>
                                <th>Módulo</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dashboard['eventos_criticos_lista'] ?? [] as $evento)
                            <tr>
                                <td>{{ $evento['fecha'] }} {{ $evento['hora'] }}</td>
                                <td>{{ $evento['usuario']['name'] ?? 'Sistema' }}</td>
                                <td>
                                    @if($evento['tipo_evento'] == 'delete')
                                        <span class="badge bg-danger">Eliminación</span>
                                    @else
                                        <span class="badge bg-warning">{{ $evento['tipo_evento'] }}</span>
                                    @endif
                                </td>
                                <td>{{ $evento['modulo'] }}</td>
                                <td>{{ Str::limit($evento['descripcion'], 50) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('bitacora.show', $evento['id']) }}" 
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
    // Gráfica de eventos por día
    var ctx1 = document.getElementById('eventosChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: {!! json_encode($dashboard['grafica']['dias'] ?? []) !!},
            datasets: [{
                label: 'Eventos',
                data: {!! json_encode($dashboard['grafica']['valores'] ?? []) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
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
                        text: 'Cantidad de Eventos'
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
                    'rgba(40, 167, 69, 0.8)',   // éxito - login
                    'rgba(108, 117, 125, 0.8)', // secundario - logout
                    'rgba(0, 123, 255, 0.8)',   // primario - create
                    'rgba(255, 193, 7, 0.8)',   // warning - update
                    'rgba(220, 53, 69, 0.8)',   // peligro - delete
                    'rgba(23, 162, 184, 0.8)'   // info - otros
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
});
</script>
@endpush