@extends('layouts.app')

@section('title', 'Estadísticas de Alarmas')
@section('header', 'Estadísticas de Alarmas')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('alarmas.estadisticas') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="instalacion_id" class="form-label">Instalación</label>
                        <select class="form-select" id="instalacion_id" name="instalacion_id" required>
                            <option value="">Seleccione...</option>
                            @foreach($instalaciones ?? [] as $instalacion)
                                <option value="{{ $instalacion['id'] }}" {{ request('instalacion_id') == $instalacion['id'] ? 'selected' : '' }}>
                                    {{ $instalacion['nombre'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                        <input type="date" class="form-control datepicker" id="fecha_inicio" 
                               name="fecha_inicio" value="{{ request('fecha_inicio', now()->startOfMonth()->toDateString()) }}" required>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="fecha_fin" class="form-label">Fecha Fin</label>
                        <input type="date" class="form-control datepicker" id="fecha_fin" 
                               name="fecha_fin" value="{{ request('fecha_fin', now()->toDateString()) }}" required>
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-graph-up"></i> Generar
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
                <h6>Total Alarmas</h6>
                <h2>{{ $estadisticas['total_alarmas'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h6>Atendidas</h6>
                <h2>{{ $estadisticas['atendidas'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h6>Pendientes</h6>
                <h2>{{ $estadisticas['pendientes'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h6>Tiempo Promedio Atención</h6>
                <h2>{{ $estadisticas['tiempo_promedio_atencion'] ?? 0 }} min</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Alarmas por Gravedad</h5>
            </div>
            <div class="card-body">
                <canvas id="gravedadChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Alarmas por Tipo</h5>
            </div>
            <div class="card-body">
                <canvas id="tipoChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Alarmas por Componente</h5>
            </div>
            <div class="card-body">
                <canvas id="componenteChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Evolución Diaria</h5>
            </div>
            <div class="card-body">
                <canvas id="evolucionChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Top 10 Componentes con más Alarmas</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Componente</th>
                                <th>Identificador</th>
                                <th>Total Alarmas</th>
                                <th>Críticas</th>
                                <th>Altas</th>
                                <th>Medias</th>
                                <th>Bajas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($estadisticas['top_componentes'] ?? [] as $comp)
                                <tr>
                                    <td>{{ $comp['tipo'] }}</td>
                                    <td>{{ $comp['identificador'] }}</td>
                                    <td>{{ $comp['total'] }}</td>
                                    <td><span class="badge bg-dark">{{ $comp['criticas'] }}</span></td>
                                    <td><span class="badge bg-danger">{{ $comp['altas'] }}</span></td>
                                    <td><span class="badge bg-warning">{{ $comp['medias'] }}</span></td>
                                    <td><span class="badge bg-info">{{ $comp['bajas'] }}</span></td>
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
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        language: 'es',
        autoclose: true
    });
    
    @if(isset($estadisticas))
    // Gráfico por gravedad
    new Chart(document.getElementById('gravedadChart'), {
        type: 'pie',
        data: {
            labels: {!! json_encode($estadisticas['gravedad_labels'] ?? []) !!},
            datasets: [{
                data: {!! json_encode($estadisticas['gravedad_data'] ?? []) !!},
                backgroundColor: ['#0dcaf0', '#ffc107', '#dc3545', '#212529']
            }]
        }
    });
    
    // Gráfico por tipo
    new Chart(document.getElementById('tipoChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($estadisticas['tipo_labels'] ?? []) !!},
            datasets: [{
                label: 'Cantidad',
                data: {!! json_encode($estadisticas['tipo_data'] ?? []) !!},
                backgroundColor: '#0d6efd'
            }]
        }
    });
    
    // Gráfico por componente
    new Chart(document.getElementById('componenteChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($estadisticas['componente_labels'] ?? []) !!},
            datasets: [{
                data: {!! json_encode($estadisticas['componente_data'] ?? []) !!},
                backgroundColor: ['#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#fd7e14']
            }]
        }
    });
    
    // Gráfico de evolución
    new Chart(document.getElementById('evolucionChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($estadisticas['evolucion_fechas'] ?? []) !!},
            datasets: [{
                label: 'Alarmas',
                data: {!! json_encode($estadisticas['evolucion_cantidades'] ?? []) !!},
                borderColor: '#0d6efd',
                tension: 0.1
            }]
        }
    });
    @endif
});
</script>
@endpush