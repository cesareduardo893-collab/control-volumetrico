@extends('layouts.app')

@section('title', 'Detalle del Producto')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Detalle del Producto: {{ $producto['nombre'] }}</h6>
                <div>
                    <a href="{{ route('productos.edit', $producto['id']) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Información General -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Información General
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Clave Producto</small>
                            <h6 class="mb-0">{{ $producto['clave_producto'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Nombre</small>
                            <h6 class="mb-0">{{ $producto['nombre'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Tipo</small>
                            <h6 class="mb-0">{{ ucfirst($producto['tipo']) }}</h6>
                        </div>
                    </div>
                </div>
                
                @if($producto['descripcion'])
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Descripción</small>
                            <h6 class="mb-0">{{ $producto['descripcion'] }}</h6>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Clasificación SAT -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-file-invoice"></i> Clasificación SAT
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Clave SAT</small>
                            <h6 class="mb-0">{{ $producto['clave_sat'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Clave Unidad</small>
                            <h6 class="mb-0">{{ $producto['clave_unidad'] }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Propiedades Físicas -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-flask"></i> Propiedades Físicas
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Densidad de Referencia</small>
                            <h6 class="mb-0">{{ $producto['densidad_referencia'] }} kg/m³</h6>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Temperatura de Referencia</small>
                            <h6 class="mb-0">{{ $producto['temperatura_referencia'] }} °C</h6>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Factor de Corrección</small>
                            <h6 class="mb-0">{{ $producto['factor_correccion'] }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Rangos Operativos -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-chart-line"></i> Rangos Operativos
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Temperatura Mínima</small>
                            <h6 class="mb-0">{{ $producto['rango_temperatura_min'] }} °C</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Temperatura Máxima</small>
                            <h6 class="mb-0">{{ $producto['rango_temperatura_max'] }} °C</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Presión Mínima</small>
                            <h6 class="mb-0">{{ $producto['rango_presion_min'] }} psi</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Presión Máxima</small>
                            <h6 class="mb-0">{{ $producto['rango_presion_max'] }} psi</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Estado -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Estado</small>
                            <h6 class="mb-0">
                                @if($producto['activo'])
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </h6>
                        </div>
                    </div>
                </div>
                
                <!-- Fechas de registro -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <hr>
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> Creado: {{ $producto['created_at'] ?? 'N/A' }} | 
                            Última actualización: {{ $producto['updated_at'] ?? 'N/A' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas -->
<div class="row mt-3">
    <div class="col-md-4">
        <div class="card bg-primary text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Tanques</h6>
                        <h3>{{ $producto['tanques_count'] ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-oil-can fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-success text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Existencias</h6>
                        <h3>{{ $producto['existencias_count'] ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-boxes fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-info text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Volumen Total</h6>
                        <h3>{{ number_format($producto['volumen_total'] ?? 0, 2) }} L</h3>
                    </div>
                    <i class="fas fa-chart-line fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Últimos movimientos -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Últimos Movimientos</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Instalación</th>
                                <th>Tanque</th>
                                <th>Tipo</th>
                                <th>Volumen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($producto['ultimos_movimientos'] ?? [] as $movimiento)
                            <tr>
                                <td>{{ $movimiento['fecha_movimiento'] }}</td>
                                <td>{{ $movimiento['instalacion'] }}</td>
                                <td>{{ $movimiento['tanque'] }}</td>
                                <td>{{ ucfirst($movimiento['tipo_movimiento']) }}</td>
                                <td class="text-end">{{ number_format($movimiento['volumen_neto'], 2) }} L</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No hay movimientos registrados</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection