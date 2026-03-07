@extends('layouts.app')

@section('title', 'Detalle de Instalación')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Detalle de Instalación: {{ $instalacion['nombre'] }}</h6>
                <div>
                    <a href="{{ route('instalaciones.edit', $instalacion['id']) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('instalaciones.index') }}" class="btn btn-secondary btn-sm">
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
                            <small class="text-muted">Clave Instalación</small>
                            <h6 class="mb-0">{{ $instalacion['clave_instalacion'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Nombre</small>
                            <h6 class="mb-0">{{ $instalacion['nombre'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Tipo</small>
                            <h6 class="mb-0">{{ ucfirst(str_replace('_', ' ', $instalacion['tipo_instalacion'])) }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Contribuyente</small>
                            <h6 class="mb-0">{{ $instalacion['contribuyente']['razon_social'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Domicilio -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-map-marker-alt"></i> Ubicación
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Domicilio</small>
                            <h6 class="mb-0">{{ $instalacion['domicilio'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Código Postal</small>
                            <h6 class="mb-0">{{ $instalacion['codigo_postal'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Latitud</small>
                            <h6 class="mb-0">{{ $instalacion['latitud'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Longitud</small>
                            <h6 class="mb-0">{{ $instalacion['longitud'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Contacto -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-phone"></i> Contacto
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Teléfono</small>
                            <h6 class="mb-0">{{ $instalacion['telefono'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Email</small>
                            <h6 class="mb-0">{{ $instalacion['email'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Horario</small>
                            <h6 class="mb-0">{{ $instalacion['horario_atencion'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Configuración de Red -->
                @if($instalacion['ip_servidor'] || $instalacion['protocolo_comunicacion'])
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-network-wired"></i> Configuración de Red
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">IP Servidor</small>
                            <h6 class="mb-0">{{ $instalacion['ip_servidor'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Puerto</small>
                            <h6 class="mb-0">{{ $instalacion['puerto_servidor'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Protocolo</small>
                            <h6 class="mb-0">{{ $instalacion['protocolo_comunicacion'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Intervalo</small>
                            <h6 class="mb-0">{{ $instalacion['intervalo_comunicacion'] ?? 'N/A' }} min</h6>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Timeout</small>
                            <h6 class="mb-0">{{ $instalacion['timeout_comunicacion'] ?? 'N/A' }} seg</h6>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Umbrales de Alarma -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-exclamation-triangle"></i> Umbrales de Alarma
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Temperatura</small>
                            <h6 class="mb-0">{{ $instalacion['umbral_temperatura_min'] ?? '-' }}°C - {{ $instalacion['umbral_temperatura_max'] ?? '-' }}°C</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Presión</small>
                            <h6 class="mb-0">{{ $instalacion['umbral_presion_min'] ?? '-' }} psi - {{ $instalacion['umbral_presion_max'] ?? '-' }} psi</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Nivel</small>
                            <h6 class="mb-0">{{ $instalacion['umbral_nivel_min'] ?? '-' }}% - {{ $instalacion['umbral_nivel_max'] ?? '-' }}%</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Flujo</small>
                            <h6 class="mb-0">{{ $instalacion['umbral_flujo_min'] ?? '-' }} - {{ $instalacion['umbral_flujo_max'] ?? '-' }} L/min</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Estado -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Estado</small>
                            <h6 class="mb-0">
                                @if($instalacion['activo'])
                                    <span class="badge bg-success">Activa</span>
                                @else
                                    <span class="badge bg-danger">Inactiva</span>
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
                            <i class="fas fa-clock"></i> Creado: {{ $instalacion['created_at'] ?? 'N/A' }} | 
                            Última actualización: {{ $instalacion['updated_at'] ?? 'N/A' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Acciones rápidas -->
<div class="row mt-3">
    <div class="col-md-3">
        <div class="card bg-primary text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Tanques</h6>
                        <h3>{{ $instalacion['tanques_count'] ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-oil-can fa-2x"></i>
                </div>
                <a href="{{ route('instalaciones.tanques', $instalacion['id']) }}" class="text-white stretched-link"></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Medidores</h6>
                        <h3>{{ $instalacion['medidores_count'] ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-tachometer-alt fa-2x"></i>
                </div>
                <a href="{{ route('instalaciones.medidores', $instalacion['id']) }}" class="text-white stretched-link"></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Dispensarios</h6>
                        <h3>{{ $instalacion['dispensarios_count'] ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-gas-pump fa-2x"></i>
                </div>
                <a href="{{ route('instalaciones.dispensarios', $instalacion['id']) }}" class="text-white stretched-link"></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Comunicación</h6>
                        <h3>
                            @if($instalacion['comunicacion_activa'] ?? false)
                                <span class="badge bg-success">Activa</span>
                            @else
                                <span class="badge bg-danger">Inactiva</span>
                            @endif
                        </h3>
                    </div>
                    <i class="fas fa-wifi fa-2x"></i>
                </div>
                <a href="{{ route('instalaciones.verificar-comunicacion', $instalacion['id']) }}" class="text-white stretched-link"></a>
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
                                <th>Tipo</th>
                                <th>Producto</th>
                                <th>Volumen</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($instalacion['ultimos_movimientos'] ?? [] as $movimiento)
                            <tr>
                                <td>{{ $movimiento['fecha_movimiento'] }}</td>
                                <td>{{ ucfirst($movimiento['tipo_movimiento']) }}</td>
                                <td>{{ $movimiento['producto'] }}</td>
                                <td class="text-end">{{ number_format($movimiento['volumen_neto'], 2) }} L</td>
                                <td>
                                    @if($movimiento['estado'] == 'validado')
                                        <span class="badge bg-success">Validado</span>
                                    @elseif($movimiento['estado'] == 'pendiente')
                                        <span class="badge bg-warning">Pendiente</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $movimiento['estado'] }}</span>
                                    @endif
                                </td>
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