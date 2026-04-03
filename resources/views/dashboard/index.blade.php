@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stats-label mb-1">Gasolineras Activas</div>
                        <div class="stats-number">{{ $resumen['contribuyentes_activos'] ?? 0 }}</div>
                    </div>
                    <div class="stats-icon">
                        <i class="bi bi-fuel-pump"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card success h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stats-label mb-1">Estaciones de Servicio</div>
                        <div class="stats-number">{{ $resumen['instalaciones_activas'] ?? 0 }}</div>
                    </div>
                    <div class="stats-icon" style="background: linear-gradient(135deg, #006847 0%, #004E98 100%);">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card warning h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stats-label mb-1">Alarmas Activas</div>
                        <div class="stats-number">{{ $resumen['alarmas_activas'] ?? 0 }}</div>
                    </div>
                    <div class="stats-icon" style="background: linear-gradient(135deg, #F7C331 0%, #FF6B35 100%);">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stats-label mb-1">Volumen Total (L)</div>
                        <div class="stats-number">{{ number_format($resumen['volumen_total'] ?? 0, 2) }}</div>
                    </div>
                    <div class="stats-icon" style="background: linear-gradient(135deg, #CE1126 0%, #FF6B35 100%);">
                        <i class="bi bi-droplet-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Access Modules -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-grid-3x3-gap me-2"></i>
                    Módulos del Sistema
                </h5>
            </div>
            <div class="card-body">
                <!-- Área 1: Operaciones -->
                <div class="mb-4">
                    <h6 class="text-danger mb-3">
                        <i class="bi bi-lightning me-2"></i>
                        Operaciones
                    </h6>
                    <div class="row mt-2">
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('registros-volumetricos.index') }}" class="module-card">
                                <div class="card h-100 border-danger">
                                    <div class="card-body">
                                        <div class="module-icon text-danger">
                                            <i class="bi bi-graph-up-arrow"></i>
                                        </div>
                                        <h6 class="module-title">Reg. Volumétricos</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('registros-volumetricos.create') }}" class="module-card">
                                <div class="card h-100 border-success">
                                    <div class="card-body">
                                        <div class="module-icon text-success">
                                            <i class="bi bi-play-circle-fill"></i>
                                        </div>
                                        <h6 class="module-title">Emulador</h6>
                                        <small class="text-muted">Registros Auto</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('existencias.index') }}" class="module-card">
                                <div class="card h-100 border-warning">
                                    <div class="card-body">
                                        <div class="module-icon text-warning">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                        <h6 class="module-title">Existencias</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('alarmas.index') }}" class="module-card">
                                <div class="card h-100 border-danger">
                                    <div class="card-body">
                                        <div class="module-icon text-danger">
                                            <i class="bi bi-exclamation-triangle"></i>
                                        </div>
                                        <h6 class="module-title">Alarmas</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Área 2: Instalaciones -->
                <div class="mb-4">
                    <h6 class="text-success mb-3">
                        <i class="bi bi-building me-2"></i>
                        Instalaciones
                    </h6>
                    <div class="row mt-2">
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('contribuyentes.index') }}" class="module-card">
                                <div class="card h-100 border-secondary">
                                    <div class="card-body">
                                        <div class="module-icon text-secondary">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <h6 class="module-title">Contribuyentes</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('instalaciones.index') }}" class="module-card">
                                <div class="card h-100 border-dark">
                                    <div class="card-body">
                                        <div class="module-icon text-dark">
                                            <i class="bi bi-geo-alt"></i>
                                        </div>
                                        <h6 class="module-title">Instalaciones</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('tanques.index') }}" class="module-card">
                                <div class="card h-100 border-success">
                                    <div class="card-body">
                                        <div class="module-icon text-success">
                                            <i class="bi bi-droplet"></i>
                                        </div>
                                        <h6 class="module-title">Tanques</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('medidores.index') }}" class="module-card">
                                <div class="card h-100 border-danger">
                                    <div class="card-body">
                                        <div class="module-icon text-danger">
                                            <i class="bi bi-speedometer2"></i>
                                        </div>
                                        <h6 class="module-title">Medidores</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('dispensarios.index') }}" class="module-card">
                                <div class="card h-100 border-primary">
                                    <div class="card-body">
                                        <div class="module-icon text-primary">
                                            <i class="bi bi-fuel-pump"></i>
                                        </div>
                                        <h6 class="module-title">Dispensarios</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('mangueras.index') }}" class="module-card">
                                <div class="card h-100 border-info">
                                    <div class="card-body">
                                        <div class="module-icon text-info">
                                            <i class="bi bi-pip"></i>
                                        </div>
                                        <h6 class="module-title">Mangueras</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Área 3: Catálogos -->
                <div class="mb-4">
                    <h6 class="text-info mb-3">
                        <i class="bi bi-cup-straw me-2"></i>
                        Catálogos
                    </h6>
                    <div class="row mt-2">
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('productos.index') }}" class="module-card">
                                <div class="card h-100 border-primary">
                                    <div class="card-body">
                                        <div class="module-icon text-primary">
                                            <i class="bi bi-cup-straw"></i>
                                        </div>
                                        <h6 class="module-title">Productos</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Área 4: Calidad -->
                <div class="mb-4">
                    <h6 class="text-warning mb-3">
                        <i class="bi bi-clipboard-check me-2"></i>
                        Calidad
                    </h6>
                    <div class="row mt-2">
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('dictamenes.index') }}" class="module-card">
                                <div class="card h-100 border-warning">
                                    <div class="card-body">
                                        <div class="module-icon text-warning">
                                            <i class="bi bi-file-text"></i>
                                        </div>
                                        <h6 class="module-title">Dictámenes</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('certificados-verificacion.index') }}" class="module-card">
                                <div class="card h-100 border-success">
                                    <div class="card-body">
                                        <div class="module-icon text-success">
                                            <i class="bi bi-patch-check"></i>
                                        </div>
                                        <h6 class="module-title">Certificados</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Área 5: Fiscal -->
                <div class="mb-4">
                    <h6 class="text-info mb-3">
                        <i class="bi bi-calculator me-2"></i>
                        Fiscal
                    </h6>
                    <div class="row mt-2">
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('cfdi.index') }}" class="module-card">
                                <div class="card h-100 border-info">
                                    <div class="card-body">
                                        <div class="module-icon text-info">
                                            <i class="bi bi-receipt"></i>
                                        </div>
                                        <h6 class="module-title">CFDI</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('reportes-sat.index') }}" class="module-card">
                                <div class="card h-100 border-dark">
                                    <div class="card-body">
                                        <div class="module-icon text-dark">
                                            <i class="bi bi-envelope-paper"></i>
                                        </div>
                                        <h6 class="module-title">Reportes SAT</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Área 6: Comercio Exterior -->
                <div class="mb-4">
                    <h6 class="text-secondary mb-3">
                        <i class="bi bi-truck me-2"></i>
                        Comercio Exterior
                    </h6>
                    <div class="row mt-2">
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('pedimentos.index') }}" class="module-card">
                                <div class="card h-100 border-secondary">
                                    <div class="card-body">
                                        <div class="module-icon text-secondary">
                                            <i class="bi bi-truck"></i>
                                        </div>
                                        <h6 class="module-title">Pedimentos</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Área 7: Administración -->
                <div class="mb-4">
                    <h6 class="text-primary mb-3">
                        <i class="bi bi-gear me-2"></i>
                        Administración
                    </h6>
                    <div class="row mt-2">
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('users.index') }}" class="module-card">
                                <div class="card h-100 border-primary">
                                    <div class="card-body">
                                        <div class="module-icon text-primary">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <h6 class="module-title">Usuarios</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('roles.index') }}" class="module-card">
                                <div class="card h-100 border-info">
                                    <div class="card-body">
                                        <div class="module-icon text-info">
                                            <i class="bi bi-shield"></i>
                                        </div>
                                        <h6 class="module-title">Roles</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('permissions.index') }}" class="module-card">
                                <div class="card h-100 border-warning">
                                    <div class="card-body">
                                        <div class="module-icon text-warning">
                                            <i class="bi bi-key"></i>
                                        </div>
                                        <h6 class="module-title">Permisos</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Área 8: Sistema -->
                <div class="mb-4">
                    <h6 class="text-secondary mb-3">
                        <i class="bi bi-journal-text me-2"></i>
                        Sistema
                    </h6>
                    <div class="row mt-2">
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                            <a href="{{ route('bitacora.index') }}" class="module-card">
                                <div class="card h-100 border-secondary">
                                    <div class="card-body">
                                        <div class="module-icon text-secondary">
                                            <i class="bi bi-journal-text"></i>
                                        </div>
                                        <h6 class="module-title">Bitácora</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-xl-8 col-lg-7">
        <div class="card">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <h6 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>
                    Movimientos por Día
                </h6>
                <select id="graficaPeriodo" class="form-select form-select-sm" style="width: auto;">
                    <option value="7">Últimos 7 días</option>
                    <option value="30">Últimos 30 días</option>
                    <option value="90">Últimos 90 días</option>
                </select>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="graficaMovimientos"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-pie-chart me-2"></i>
                    Distribución por Producto
                </h6>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="graficaProductos"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Movements Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    Últimos Movimientos
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="ultimosMovimientosTable">
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
                                <td>
                                    <span class="badge bg-light text-dark">{{ $movimiento['producto'] }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ strtolower($movimiento['tipo_movimiento']) == 'recepcion' ? 'success' : 'danger' }}-subtle text-{{ strtolower($movimiento['tipo_movimiento']) == 'recepcion' ? 'success' : 'danger' }}">
                                        <i class="bi bi-arrow-{{ strtolower($movimiento['tipo_movimiento']) == 'recepcion' ? 'down' : 'up' }} me-1"></i>
                                        {{ ucfirst($movimiento['tipo_movimiento']) }}
                                    </span>
                                </td>
                                <td class="text-end fw-semibold">{{ number_format($movimiento['volumen_neto'], 2) }} L</td>
                                <td>
                                    <span class="badge bg-{{ strtolower($movimiento['estado']) == 'validado' ? 'success' : 'warning' }}-subtle text-{{ strtolower($movimiento['estado']) == 'validado' ? 'success' : 'warning' }}">
                                        <i class="bi bi-{{ strtolower($movimiento['estado']) == 'validado' ? 'check-circle' : 'clock' }} me-1"></i>
                                        {{ ucfirst($movimiento['estado']) }}
                                    </span>
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

@push('styles')
<style>
    .bg-success-subtle { background-color: rgba(25, 135, 84, 0.1) !important; }
    .bg-danger-subtle { background-color: rgba(220, 53, 69, 0.1) !important; }
    .bg-warning-subtle { background-color: rgba(255, 193, 7, 0.1) !important; }
    .bg-secondary-subtle { background-color: rgba(108, 117, 125, 0.1) !important; }
    
    .table tbody tr { transition: all 0.3s ease; }
    .table tbody tr:hover { background: rgba(255, 107, 53, 0.05); transform: translateX(5px); }
    .badge { font-weight: 500; padding: 0.5em 0.75em; }

    .module-card .card {
        transition: all 0.3s ease;
        border-width: 2px;
    }
    .module-card .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    .border-success { border-color: #198754 !important; }
    .border-warning { border-color: #ffc107 !important; }
    .border-danger { border-color: #dc3545 !important; }
    .border-primary { border-color: #0d6efd !important; }
    .border-info { border-color: #0dcaf0 !important; }
    .border-secondary { border-color: #6c757d !important; }
    .border-dark { border-color: #212529 !important; }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('#ultimosMovimientosTable').DataTable({
        pageLength: 10,
        order: [[0, 'desc']],
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' }
    });

    cargarGraficas();
    $('#graficaPeriodo').change(function() {
        cargarGraficaMovimientos($(this).val());
    });
});

function cargarGraficas() {
    cargarGraficaMovimientos(7);
    cargarGraficaProductos();
}

function cargarGraficaMovimientos(dias) {
    $.ajax({
        url: '{{ route("api.dashboard.grafica-movimientos") }}',
        type: 'GET',
        data: { dias: dias },
        success: function(data) {
            const ctx = document.getElementById('graficaMovimientos').getContext('2d');
            if (window.movimientosChart) window.movimientosChart.destroy();
            window.movimientosChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.data.labels,
                    datasets: [{
                        label: 'Entradas',
                        data: data.data.entradas,
                        borderColor: 'rgba(79, 172, 254, 1)',
                        backgroundColor: 'rgba(79, 172, 254, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5
                    }, {
                        label: 'Salidas',
                        data: data.data.salidas,
                        borderColor: 'rgba(240, 147, 251, 1)',
                        backgroundColor: 'rgba(240, 147, 251, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top' } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar gráfica de movimientos:', error);
        }
    });
}

function cargarGraficaProductos() {
    $.ajax({
        url: '{{ route("api.dashboard.grafica-productos") }}',
        type: 'GET',
        success: function(data) {
            const ctx = document.getElementById('graficaProductos').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.data.labels,
                    datasets: [{
                        data: data.data.valores,
                        backgroundColor: ['rgba(102, 126, 234, 0.8)', 'rgba(118, 75, 162, 0.8)', 'rgba(240, 147, 251, 0.8)', 'rgba(79, 172, 254, 0.8)', 'rgba(0, 242, 254, 0.8)'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } },
                    cutout: '60%'
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar gráfica de productos:', error);
        }
    });
}
</script>
@endpush