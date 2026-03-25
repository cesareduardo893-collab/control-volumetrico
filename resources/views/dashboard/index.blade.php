@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('actions')
<div class="btn-group">
    <a href="{{ route('dashboard.exportar', ['tipo' => 'excel']) }}" class="btn btn-success" title="Exportar a Excel">
        <i class="bi bi-file-excel me-1"></i> Excel
    </a>
    <a href="{{ route('dashboard.exportar', ['tipo' => 'pdf']) }}" class="btn btn-danger" title="Exportar a PDF">
        <i class="bi bi-file-pdf me-1"></i> PDF
    </a>
</div>
@endsection

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stats-label mb-1">Contribuyentes Activos</div>
                        <div class="stats-number">{{ $resumen['contribuyentes_activos'] ?? 0 }}</div>
                    </div>
                    <div class="stats-icon">
                        <i class="bi bi-building"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="bi bi-arrow-up me-1"></i>
                        Total de contribuyentes registrados
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card success h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stats-label mb-1">Instalaciones</div>
                        <div class="stats-number">{{ $resumen['instalaciones_activas'] ?? 0 }}</div>
                    </div>
                    <div class="stats-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="bi bi-arrow-up me-1"></i>
                        Instalaciones operativas
                    </small>
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
                    <div class="stats-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="bi bi-clock me-1"></i>
                        Requieren atención
                    </small>
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
                        <div class="stats-number">{{ number_format($resumen['volumen_total'] ?? 0, 0) }}</div>
                    </div>
                    <div class="stats-icon" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                        <i class="bi bi-droplet-half"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="bi bi-graph-up me-1"></i>
                        Volumen total registrado
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Access Modules - Organized by Areas -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-grid-3x3-gap me-2"></i>
                    Módulos de Acceso Rápido
                </h5>
            </div>
            <div class="card-body">
                <!-- Área 1: Control Volumétrico -->
                <div class="mb-4">
                    <h6 class="text-primary mb-3">
                        <i class="bi bi-shield-check me-2"></i>
                        Control Volumétrico
                    </h6>
                    
                    <!-- 1.1 Control volumétrico -->
                    <div class="mb-3">
                        <small class="text-muted fw-semibold">
                            <i class="bi bi-speedometer2 me-1"></i>
                            1.1 Control Volumétrico
                        </small>
                        <div class="row mt-2">
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                                <a href="{{ route('registros-volumetricos.index') }}" class="module-card">
                                    <div class="card h-100 border-success">
                                        <div class="card-body">
                                            <div class="module-icon text-success">
                                                <i class="bi bi-graph-up-arrow"></i>
                                            </div>
                                            <h6 class="module-title">Reg. Volumétricos</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                                <a href="{{ route('existencias.index') }}" class="module-card">
                                    <div class="card h-100 border-success">
                                        <div class="card-body">
                                            <div class="module-icon text-success">
                                                <i class="bi bi-bar-chart-fill"></i>
                                            </div>
                                            <h6 class="module-title">Existencias</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 1.2 Gestión de sistemas de medición y equipos -->
                    <div class="mb-3">
                        <small class="text-muted fw-semibold">
                            <i class="bi bi-gear me-1"></i>
                            1.2 Gestión de Sistemas de Medición y Equipos
                        </small>
                        <div class="row mt-2">
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                                <a href="{{ route('tanques.index') }}" class="module-card">
                                    <div class="card h-100 border-warning">
                                        <div class="card-body">
                                            <div class="module-icon text-warning">
                                                <i class="bi bi-droplet-half"></i>
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
                                                <i class="bi bi-fuel-pump-fill"></i>
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
                                                <i class="bi bi-droplet-fill"></i>
                                            </div>
                                            <h6 class="module-title">Mangueras</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 1.3 Gestión de la calidad del producto -->
                    <div class="mb-3">
                        <small class="text-muted fw-semibold">
                            <i class="bi bi-award me-1"></i>
                            1.3 Gestión de la Calidad del Producto
                        </small>
                        <div class="row mt-2">
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                                <a href="{{ route('dictamenes.index') }}" class="module-card">
                                    <div class="card h-100 border-warning">
                                        <div class="card-body">
                                            <div class="module-icon text-warning">
                                                <i class="bi bi-file-earmark-text"></i>
                                            </div>
                                            <h6 class="module-title">Dictámenes</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                                <a href="{{ route('productos.index') }}" class="module-card">
                                    <div class="card h-100 border-primary">
                                        <div class="card-body">
                                            <div class="module-icon text-primary">
                                                <i class="bi bi-box-seam-fill"></i>
                                            </div>
                                            <h6 class="module-title">Productos</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 1.4 Verificación y certificación -->
                    <div class="mb-3">
                        <small class="text-muted fw-semibold">
                            <i class="bi bi-patch-check me-1"></i>
                            1.4 Verificación y Certificación
                        </small>
                        <div class="row mt-2">
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                                <a href="{{ route('certificados-verificacion.index') }}" class="module-card">
                                    <div class="card h-100 border-success">
                                        <div class="card-body">
                                            <div class="module-icon text-success">
                                                <i class="bi bi-patch-check-fill"></i>
                                            </div>
                                            <h6 class="module-title">Certificados</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 1.5 Integración fiscal y envío de reportes al SAT -->
                    <div class="mb-3">
                        <small class="text-muted fw-semibold">
                            <i class="bi bi-cloud-upload me-1"></i>
                            1.5 Integración Fiscal y Envío de Reportes al SAT
                        </small>
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
                                <a href="{{ route('pedimentos.index') }}" class="module-card">
                                    <div class="card h-100 border-secondary">
                                        <div class="card-body">
                                            <div class="module-icon text-secondary">
                                                <i class="bi bi-file-earmark-arrow-down"></i>
                                            </div>
                                            <h6 class="module-title">Pedimentos</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                                <a href="{{ route('reportes-sat.index') }}" class="module-card">
                                    <div class="card h-100 border-dark">
                                        <div class="card-body">
                                            <div class="module-icon text-dark">
                                                <i class="bi bi-file-earmark-bar-graph"></i>
                                            </div>
                                            <h6 class="module-title">Reportes SAT</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Área 2: Gestión de Datos -->
                <div class="mb-4">
                    <h6 class="text-purple mb-3">
                        <i class="bi bi-cpu me-2"></i>
                        Gestión de Datos
                    </h6>
                    
                    <!-- 2.1 Arquitectura y componentes -->
                    <div class="mb-3">
                        <small class="text-muted fw-semibold">
                            <i class="bi bi-diagram-3 me-1"></i>
                            2.1 Arquitectura y Componentes
                        </small>
                        <div class="row mt-2">
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                                <a href="{{ route('contribuyentes.index') }}" class="module-card">
                                    <div class="card h-100 border-secondary">
                                        <div class="card-body">
                                            <div class="module-icon text-secondary">
                                                <i class="bi bi-building"></i>
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
                                                <i class="bi bi-geo-alt-fill"></i>
                                            </div>
                                            <h6 class="module-title">Instalaciones</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 2.2 Funcionalidades específicas -->
                    <div class="mb-3">
                        <small class="text-muted fw-semibold">
                            <i class="bi bi-lightning me-1"></i>
                            2.2 Funcionalidades Específicas
                        </small>
                        <div class="row mt-2">
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                                <a href="{{ route('alarmas.index') }}" class="module-card">
                                    <div class="card h-100 border-danger">
                                        <div class="card-body">
                                            <div class="module-icon text-danger">
                                                <i class="bi bi-exclamation-triangle-fill"></i>
                                            </div>
                                            <h6 class="module-title">Alarmas</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                                <a href="{{ route('bitacora.index') }}" class="module-card">
                                    <div class="card h-100 border-primary">
                                        <div class="card-body">
                                            <div class="module-icon text-primary">
                                                <i class="bi bi-journal-text"></i>
                                            </div>
                                            <h6 class="module-title">Bitácora</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 2.3 Seguridad informática -->
                    <div class="mb-3">
                        <small class="text-muted fw-semibold">
                            <i class="bi bi-shield-lock me-1"></i>
                            2.3 Seguridad Informática
                        </small>
                        <div class="row mt-2">
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                                <a href="{{ route('users.index') }}" class="module-card">
                                    <div class="card h-100 border-primary">
                                        <div class="card-body">
                                            <div class="module-icon text-primary">
                                                <i class="bi bi-people-fill"></i>
                                            </div>
                                            <h6 class="module-title">Usuarios</h6>
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
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded p-2 me-2">
                                            <i class="bi bi-calendar-event text-primary"></i>
                                        </div>
                                        <span>{{ $movimiento['fecha_movimiento'] }}</span>
                                    </div>
                                </td>
                                <td>{{ $movimiento['instalacion'] }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $movimiento['producto'] }}</span>
                                </td>
                                <td>
                                    @switch($movimiento['tipo_movimiento'])
                                        @case('entrada')
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="bi bi-arrow-down me-1"></i>Entrada
                                            </span>
                                            @break
                                        @case('salida')
                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="bi bi-arrow-up me-1"></i>Salida
                                            </span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary-subtle text-secondary">{{ $movimiento['tipo_movimiento'] }}</span>
                                    @endswitch
                                </td>
                                <td class="text-end fw-semibold">{{ number_format($movimiento['volumen_neto'], 2) }} L</td>
                                <td>
                                    @switch($movimiento['estado'])
                                        @case('validado')
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="bi bi-check-circle me-1"></i>Validado
                                            </span>
                                            @break
                                        @case('pendiente')
                                            <span class="badge bg-warning-subtle text-warning">
                                                <i class="bi bi-clock me-1"></i>Pendiente
                                            </span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary-subtle text-secondary">{{ $movimiento['estado'] }}</span>
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

@push('styles')
<style>
    .bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }
    
    .bg-danger-subtle {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }
    
    .bg-warning-subtle {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }
    
    .bg-secondary-subtle {
        background-color: rgba(108, 117, 125, 0.1) !important;
    }
    
    .table tbody tr {
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background: rgba(102, 126, 234, 0.05);
        transform: translateX(5px);
    }
    
    .badge {
        font-weight: 500;
        padding: 0.5em 0.75em;
    }

    .text-purple {
        color: #764ba2 !important;
    }

    .module-card .card {
        transition: all 0.3s ease;
        border-width: 2px;
    }

    .module-card .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .border-success {
        border-color: #198754 !important;
    }

    .border-warning {
        border-color: #ffc107 !important;
    }

    .border-danger {
        border-color: #dc3545 !important;
    }

    .border-primary {
        border-color: #0d6efd !important;
    }

    .border-info {
        border-color: #0dcaf0 !important;
    }

    .border-secondary {
        border-color: #6c757d !important;
    }

    .border-dark {
        border-color: #212529 !important;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#ultimosMovimientosTable').DataTable({
        pageLength: 10,
        order: [[0, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        drawCallback: function() {
            // Add animation to rows
            $(this).find('tbody tr').each(function(index) {
                $(this).css({
                    'opacity': '0',
                    'transform': 'translateY(20px)'
                }).delay(index * 50).animate({
                    'opacity': '1'
                }, 300);
                $(this).css('transform', 'translateY(0)');
            });
        }
    });

    // Load charts
    cargarGraficas();

    // Period change event
    $('#graficaPeriodo').change(function() {
        cargarGraficaMovimientos($(this).val());
    });
});

function cargarGraficas() {
    cargarGraficaMovimientos(7);
    cargarGraficaProductos();
}

function cargarGraficaMovimientos(dias) {
    $.get('{{ route("api.dashboard.grafica-movimientos") }}', { dias: dias }, function(data) {
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
                    borderColor: 'rgba(79, 172, 254, 1)',
                    backgroundColor: 'rgba(79, 172, 254, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(79, 172, 254, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }, {
                    label: 'Salidas',
                    data: data.salidas,
                    borderColor: 'rgba(240, 147, 251, 1)',
                    backgroundColor: 'rgba(240, 147, 251, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(240, 147, 251, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                family: 'Inter',
                                size: 12
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                family: 'Inter'
                            }
                        },
                        title: {
                            display: true,
                            text: 'Volumen (L)',
                            font: {
                                family: 'Inter',
                                weight: 'bold'
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Inter'
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }).fail(function() {
        console.error('Error al cargar gráfica de movimientos');
    });
}

function cargarGraficaProductos() {
    $.get('{{ route("api.dashboard.grafica-productos") }}', function(data) {
        const ctx = document.getElementById('graficaProductos').getContext('2d');
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.valores,
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(118, 75, 162, 0.8)',
                        'rgba(240, 147, 251, 0.8)',
                        'rgba(79, 172, 254, 0.8)',
                        'rgba(0, 242, 254, 0.8)'
                    ],
                    borderColor: [
                        'rgba(102, 126, 234, 1)',
                        'rgba(118, 75, 162, 1)',
                        'rgba(240, 147, 251, 1)',
                        'rgba(79, 172, 254, 1)',
                        'rgba(0, 242, 254, 1)'
                    ],
                    borderWidth: 2,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1500,
                    easing: 'easeInOutQuart'
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                family: 'Inter',
                                size: 12
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });
    }).fail(function() {
        console.error('Error al cargar gráfica de productos');
    });
}
</script>
@endpush