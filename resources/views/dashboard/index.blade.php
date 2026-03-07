@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Dashboard</h2>
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
                            Contribuyentes Activos</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $resumen['contribuyentes_activos'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-building fa-2x text-gray-300"></i>
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
                            Instalaciones</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $resumen['instalaciones_activas'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-gas-pump fa-2x text-gray-300"></i>
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
                            Alarmas Activas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $resumen['alarmas_activas'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
                            Volumen Total (L)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($resumen['volumen_total'] ?? 0, 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-oil-can fa-2x text-gray-300"></i>
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
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Movimientos por Día</h6>
                <div class="dropdown no-arrow">
                    <select id="graficaPeriodo" class="form-select form-select-sm">
                        <option value="7">Últimos 7 días</option>
                        <option value="30">Últimos 30 días</option>
                        <option value="90">Últimos 90 días</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="graficaMovimientos"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Distribución por Producto</h6>
            </div>
            <div class="card-body">
                <canvas id="graficaProductos"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de últimos movimientos -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Últimos Movimientos</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="ultimosMovimientosTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Instalación</th>
                                <th>Producto</th>
                                <th>Tipo</th>
                                <th>Volumen</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resumen['ultimos_movimientos'] ?? [] as $movimiento)
                            <tr>
                                <td>{{ $movimiento['fecha_movimiento'] }}</td>
                                <td>{{ $movimiento['instalacion'] }}</td>
                                <td>{{ $movimiento['producto'] }}</td>
                                <td>
                                    @switch($movimiento['tipo_movimiento'])
                                        @case('entrada')
                                            <span class="badge bg-success">Entrada</span>
                                            @break
                                        @case('salida')
                                            <span class="badge bg-danger">Salida</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $movimiento['tipo_movimiento'] }}</span>
                                    @endswitch
                                </td>
                                <td class="text-end">{{ number_format($movimiento['volumen_neto'], 2) }} L</td>
                                <td>
                                    @switch($movimiento['estado'])
                                        @case('validado')
                                            <span class="badge bg-success">Validado</span>
                                            @break
                                        @case('pendiente')
                                            <span class="badge bg-warning">Pendiente</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $movimiento['estado'] }}</span>
                                    @endswitch
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
<script>
$(document).ready(function() {
    // Inicializar DataTable
    $('#ultimosMovimientosTable').DataTable({
        pageLength: 10,
        order: [[0, 'desc']]
    });

    // Cargar gráficas
    cargarGraficas();

    // Evento cambio de período
    $('#graficaPeriodo').change(function() {
        cargarGraficaMovimientos($(this).val());
    });
});

function cargarGraficas() {
    cargarGraficaMovimientos(7);
    cargarGraficaProductos();
}

function cargarGraficaMovimientos(dias) {
    $.get('/dashboard/grafica-movimientos', { dias: dias }, function(data) {
        const ctx = document.getElementById('graficaMovimientos').getContext('2d');
        
        if (window.movimientosChart) {
            window.movimientosChart.destroy();
        }

        window.movimientosChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Entradas',
                    data: data.entradas,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                }, {
                    label: 'Salidas',
                    data: data.salidas,
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
                            text: 'Volumen (L)'
                        }
                    }
                }
            }
        });
    });
}

function cargarGraficaProductos() {
    $.get('/dashboard/grafica-productos', function(data) {
        const ctx = document.getElementById('graficaProductos').getContext('2d');
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.valores,
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
    });
}
</script>
@endpush