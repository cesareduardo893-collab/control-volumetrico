@extends('layouts.app')

@section('title', 'Resumen de Actividad')
@section('header', 'Resumen de Actividad')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('bitacora.resumen') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                        <input type="date" class="form-control datepicker" id="fecha_inicio" 
                               name="fecha_inicio" value="{{ request('fecha_inicio', now()->startOfMonth()->toDateString()) }}" required>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="fecha_fin" class="form-label">Fecha Fin</label>
                        <input type="date" class="form-control datepicker" id="fecha_fin" 
                               name="fecha_fin" value="{{ request('fecha_fin', now()->toDateString()) }}" required>
                    </div>
                    
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-file-text"></i> Generar Resumen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(isset($resumen))
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h6>Total Eventos</h6>
                <h2>{{ $resumen['total_eventos'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h6>Usuarios Activos</h6>
                <h2>{{ $resumen['usuarios_activos'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h6>Módulos Accedidos</h6>
                <h2>{{ count($resumen['por_modulo'] ?? []) }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h6>Promedio Diario</h6>
                <h2>{{ $resumen['promedio_diario'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Eventos por Tipo</h5>
            </div>
            <div class="card-body">
                <canvas id="tipoChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Eventos por Módulo</h5>
            </div>
            <div class="card-body">
                <canvas id="moduloChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Top 10 Usuarios más Activos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Eventos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resumen['top_usuarios'] ?? [] as $usuario)
                                <tr>
                                    <td>{{ $usuario['nombre'] }}</td>
                                    <td>{{ $usuario['total'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Actividad por Hora</h5>
            </div>
            <div class="card-body">
                <canvas id="horaChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Eventos Destacados</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th>Tipo</th>
                                <th>Módulo</th>
                                <th>Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resumen['eventos_destacados'] ?? [] as $evento)
                                <tr>
                                    <td>{{ $evento['fecha_hora'] }}</td>
                                    <td>{{ $evento['usuario'] }}</td>
                                    <td>{{ $evento['tipo'] }}</td>
                                    <td>{{ $evento['modulo'] }}</td>
                                    <td>{{ $evento['descripcion'] }}</td>
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
    
    @if(isset($resumen))
    // Gráfico por tipo
    new Chart(document.getElementById('tipoChart'), {
        type: 'pie',
        data: {
            labels: {!! json_encode(array_keys($resumen['por_tipo'] ?? [])) !!},
            datasets: [{
                data: {!! json_encode(array_values($resumen['por_tipo'] ?? [])) !!},
                backgroundColor: ['#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#fd7e14', '#198754']
            }]
        }
    });
    
    // Gráfico por módulo
    new Chart(document.getElementById('moduloChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($resumen['por_modulo'] ?? [])) !!},
            datasets: [{
                label: 'Eventos',
                data: {!! json_encode(array_values($resumen['por_modulo'] ?? [])) !!},
                backgroundColor: '#0d6efd'
            }]
        }
    });
    
    // Gráfico por hora
    new Chart(document.getElementById('horaChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode(range(0, 23)) !!},
            datasets: [{
                label: 'Eventos',
                data: {!! json_encode($resumen['por_hora'] ?? array_fill(0, 24, 0)) !!},
                borderColor: '#0d6efd',
                fill: false
            }]
        }
    });
    @endif
});
</script>
@endpush