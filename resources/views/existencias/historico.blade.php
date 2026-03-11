@extends('layouts.app')

@section('title', 'Histórico de Existencias')
@section('header', 'Histórico de Existencias')

@section('actions')
<a href="{{ route('existencias.inventario-actual', $tanqueId) }}" class="btn btn-sm btn-info">
    <i class="bi bi-box"></i> Inventario Actual
</a>
<a href="{{ route('existencias.index') }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Filtros -->
        <form method="GET" action="{{ route('existencias.historico', $tanqueId) }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control datepicker" id="fecha_inicio" 
                       name="fecha_inicio" value="{{ request('fecha_inicio', now()->subDays(30)->toDateString()) }}" required>
            </div>
            
            <div class="col-md-4">
                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                <input type="date" class="form-control datepicker" id="fecha_fin" 
                       name="fecha_fin" value="{{ request('fecha_fin', now()->toDateString()) }}" required>
            </div>
            
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Consultar
                </button>
                <a href="{{ route('existencias.historico', $tanqueId) }}" class="btn btn-secondary ms-2">
                    <i class="bi bi-eraser"></i> Limpiar
                </a>
            </div>
        </form>
        
        @if(isset($historico))
        <!-- Resumen del período -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6>Registros en período</h6>
                        <h3>{{ $historico['total_registros'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6>Volumen Inicial</h6>
                        <h3>{{ number_format($historico['volumen_inicial'] ?? 0, 3) }} L</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6>Volumen Final</h6>
                        <h3>{{ number_format($historico['volumen_final'] ?? 0, 3) }} L</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h6>Variación</h6>
                        @php
                            $variacion = ($historico['volumen_final'] ?? 0) - ($historico['volumen_inicial'] ?? 0);
                            $variacionClass = $variacion >= 0 ? 'success' : 'danger';
                        @endphp
                        <h3 class="text-{{ $variacionClass }}">
                            {{ $variacion >= 0 ? '+' : '' }}{{ number_format($variacion, 3) }} L
                        </h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla de movimientos -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Tipo Movimiento</th>
                        <th>Volumen</th>
                        <th>Volumen Acumulado</th>
                        <th>Temperatura</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historico['movimientos'] ?? [] as $movimiento)
                        <tr>
                            <td>{{ $movimiento['fecha'] }}</td>
                            <td>{{ $movimiento['hora'] }}</td>
                            <td>
                                @php
                                    $movClass = in_array($movimiento['tipo_movimiento'], ['RECEPCION', 'ENTRADA']) ? 'success' : 'danger';
                                @endphp
                                <span class="badge bg-{{ $movClass }}">{{ $movimiento['tipo_movimiento'] }}</span>
                            </td>
                            <td>
                                @if(in_array($movimiento['tipo_movimiento'], ['RECEPCION', 'ENTRADA']))
                                    <span class="text-success">+{{ number_format($movimiento['volumen'], 3) }} L</span>
                                @else
                                    <span class="text-danger">-{{ number_format($movimiento['volumen'], 3) }} L</span>
                                @endif
                            </td>
                            <td><strong>{{ number_format($movimiento['volumen_acumulado'], 3) }} L</strong></td>
                            <td>{{ number_format($movimiento['temperatura'] ?? 0, 1) }} °C</td>
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
                            <td>
                                <a href="{{ route('existencias.show', $movimiento['id']) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay movimientos en el período seleccionado</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Gráfica de evolución -->
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Evolución del Volumen</h5>
            </div>
            <div class="card-body">
                <canvas id="evolucionChart" height="300"></canvas>
            </div>
        </div>
        
        <!-- Estadísticas por tipo de movimiento -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Resumen por Tipo de Movimiento</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Cantidad</th>
                                        <th>Volumen Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($historico['resumen_tipos'] ?? [] as $tipo)
                                        <tr>
                                            <td>{{ $tipo['tipo'] }}</td>
                                            <td>{{ $tipo['cantidad'] }}</td>
                                            <td>{{ number_format($tipo['volumen_total'], 3) }} L</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Distribución de Movimientos</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="distribucionChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        language: 'es',
        autoclose: true
    });
    
    @if(isset($historico))
    // Gráfica de evolución
    new Chart(document.getElementById('evolucionChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($historico['evolucion']['fechas'] ?? []) !!},
            datasets: [{
                label: 'Volumen (L)',
                data: {!! json_encode($historico['evolucion']['volumenes'] ?? []) !!},
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
    
    // Gráfica de distribución
    new Chart(document.getElementById('distribucionChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($historico['distribucion'] ?? [])) !!},
            datasets: [{
                data: {!! json_encode(array_values($historico['distribucion'] ?? [])) !!},
                backgroundColor: ['#198754', '#dc3545', '#ffc107', '#0dcaf0', '#6c757d']
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