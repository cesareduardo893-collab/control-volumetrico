@extends('layouts.app')

@section('title', 'Estadísticas de Dictámenes')
@section('header', 'Estadísticas de Dictámenes')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('dictamenes.estadisticas') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="contribuyente_id" class="form-label">Contribuyente</label>
                        <select class="form-select select2" id="contribuyente_id" name="contribuyente_id" required>
                            <option value="">Seleccione...</option>
                            @foreach($contribuyentes ?? [] as $contribuyente)
                                <option value="{{ $contribuyente['id'] }}" {{ request('contribuyente_id') == $contribuyente['id'] ? 'selected' : '' }}>
                                    {{ $contribuyente['razon_social'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="anio" class="form-label">Año</label>
                        <select class="form-select" id="anio" name="anio" required>
                            @for($i = now()->year; $i >= 2020; $i--)
                                <option value="{{ $i }}" {{ request('anio', now()->year) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-graph-up"></i> Generar Estadísticas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(isset($estadisticas))
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h6>Total Dictámenes</h6>
                <h2>{{ $estadisticas['total'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h6>Vigentes</h6>
                <h2>{{ $estadisticas['vigentes'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h6>Próximos a Vencer</h6>
                <h2>{{ $estadisticas['proximos_vencer'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h6>Vencidos</h6>
                <h2>{{ $estadisticas['vencidos'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Dictámenes por Mes</h5>
            </div>
            <div class="card-body">
                <canvas id="mesesChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Dictámenes por Producto</h5>
            </div>
            <div class="card-body">
                <canvas id="productosChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Dictámenes por Estado</h5>
            </div>
            <div class="card-body">
                <canvas id="estadoChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Top 5 Laboratorios</h5>
            </div>
            <div class="card-body">
                <canvas id="laboratoriosChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Detalle Mensual</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Mes</th>
                                <th>Emitidos</th>
                                <th>Vigentes</th>
                                <th>Vencidos</th>
                                <th>Cancelados</th>
                                <th>Próximos a Vencer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($estadisticas['detalle_mensual'] ?? [] as $mes)
                                <tr>
                                    <td>{{ $mes['mes_nombre'] }}</td>
                                    <td>{{ $mes['emitidos'] }}</td>
                                    <td><span class="badge bg-success">{{ $mes['vigentes'] }}</span></td>
                                    <td><span class="badge bg-danger">{{ $mes['vencidos'] }}</span></td>
                                    <td><span class="badge bg-secondary">{{ $mes['cancelados'] }}</span></td>
                                    <td><span class="badge bg-warning">{{ $mes['proximos_vencer'] }}</span></td>
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
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
    
    @if(isset($estadisticas))
    // Gráfico por mes
    new Chart(document.getElementById('mesesChart'), {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            datasets: [{
                label: 'Dictámenes',
                data: {{ json_encode($estadisticas['por_mes'] ?? array_fill(0, 12, 0)) }},
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
    
    // Gráfico por producto
    new Chart(document.getElementById('productosChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($estadisticas['por_producto'] ?? [])) !!},
            datasets: [{
                data: {!! json_encode(array_values($estadisticas['por_producto'] ?? [])) !!},
                backgroundColor: [
                    '#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#fd7e14',
                    '#198754', '#0dcaf0', '#ffc107', '#dc3545', '#20c997'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
    
    // Gráfico por estado
    new Chart(document.getElementById('estadoChart'), {
        type: 'pie',
        data: {
            labels: {!! json_encode(array_keys($estadisticas['por_estado'] ?? [])) !!},
            datasets: [{
                data: {!! json_encode(array_values($estadisticas['por_estado'] ?? [])) !!},
                backgroundColor: ['#198754', '#ffc107', '#6c757d']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
    
    // Gráfico de laboratorios
    new Chart(document.getElementById('laboratoriosChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($estadisticas['top_laboratorios'] ?? [])) !!},
            datasets: [{
                label: 'Dictámenes',
                data: {!! json_encode(array_values($estadisticas['top_laboratorios'] ?? [])) !!},
                backgroundColor: '#0d6efd'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y'
        }
    });
    @endif
});
</script>
@endpush