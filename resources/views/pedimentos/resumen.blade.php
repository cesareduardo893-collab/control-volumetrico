@extends('layouts.app')

@section('title', 'Resumen de Comercio Exterior')
@section('header', 'Resumen de Comercio Exterior')

@section('actions')
<a href="{{ route('pedimentos.index') }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver a Pedimentos
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('pedimentos.resumen-comercio-exterior') }}" class="row g-3">
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
                    
                    <div class="col-md-3">
                        <label for="mes" class="form-label">Mes (opcional)</label>
                        <select class="form-select" id="mes" name="mes">
                            <option value="">Todos</option>
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ request('mes') == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->locale('es')->monthName }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-graph-up"></i> Generar Resumen
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
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Total Pedimentos</h6>
                <h2>{{ $resumen['total_pedimentos'] }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Volumen Total</h6>
                <h2>{{ number_format($resumen['volumen_total'], 3) }} L</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Valor Total</h6>
                <h2>${{ number_format($resumen['valor_total'], 2) }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6>Países Involucrados</h6>
                <h2>{{ count($resumen['paises']) }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Pedimentos por Mes</h5>
            </div>
            <div class="card-body">
                <canvas id="mesesChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Pedimentos por País</h5>
            </div>
            <div class="card-body">
                <canvas id="paisesChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Top 5 Productos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Volumen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resumen['top_productos'] as $producto)
                                <tr>
                                    <td>{{ $producto['nombre'] }}</td>
                                    <td>{{ $producto['cantidad'] }}</td>
                                    <td>{{ number_format($producto['volumen'], 3) }} L</td>
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
                <h5 class="card-title mb-0">Resumen por Estado</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Estado</th>
                                <th>Cantidad</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resumen['por_estado'] as $estado => $cantidad)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ $estado == 'ACTIVO' ? 'success' : ($estado == 'UTILIZADO' ? 'info' : 'secondary') }}">
                                            {{ $estado }}
                                        </span>
                                    </td>
                                    <td>{{ $cantidad }}</td>
                                    <td>{{ round(($cantidad / $resumen['total_pedimentos']) * 100, 1) }}%</td>
                                </tr>
                            @endforeach
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
            <div class="card-header">
                <h5 class="card-title mb-0">Detalle Mensual</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Mes</th>
                                <th>Pedimentos</th>
                                <th>Volumen Total</th>
                                <th>Valor Total</th>
                                <th>Promedio por Pedimento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resumen['detalle_mensual'] as $mes)
                                <tr>
                                    <td><strong>{{ $mes['mes_nombre'] }}</strong></td>
                                    <td>{{ $mes['cantidad'] }}</td>
                                    <td>{{ number_format($mes['volumen'], 3) }} L</td>
                                    <td>${{ number_format($mes['valor'], 2) }}</td>
                                    <td>${{ number_format($mes['valor'] / $mes['cantidad'], 2) }}</td>
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
    
    @if(isset($resumen))
    // Gráfico por mes
    new Chart(document.getElementById('mesesChart'), {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            datasets: [{
                label: 'Pedimentos',
                data: {{ json_encode($resumen['por_mes'] ?? array_fill(0, 12, 0)) }},
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
    
    // Gráfico por país
    new Chart(document.getElementById('paisesChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($resumen['por_pais'])) !!},
            datasets: [{
                data: {!! json_encode(array_values($resumen['por_pais'])) !!},
                backgroundColor: ['#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#fd7e14']
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