@extends('layouts.app')

@section('title', 'Detalle del Tanque')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Detalle del Tanque: {{ $tanque['nombre'] }}</h6>
                <div>
                    <a href="{{ route('tanques.edit', $tanque['id']) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('tanques.index') }}" class="btn btn-secondary btn-sm">
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
                            <small class="text-muted">Clave Tanque</small>
                            <h6 class="mb-0">{{ $tanque['clave_tanque'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Nombre</small>
                            <h6 class="mb-0">{{ $tanque['nombre'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Instalación</small>
                            <h6 class="mb-0">{{ $tanque['instalacion']['nombre'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Producto</small>
                            <h6 class="mb-0">{{ $tanque['producto']['nombre'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Ubicación</small>
                            <h6 class="mb-0">{{ $tanque['ubicacion'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Tipo</small>
                            <h6 class="mb-0">{{ ucfirst(str_replace('_', ' ', $tanque['tipo_tanque'])) }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Forma</small>
                            <h6 class="mb-0">{{ ucfirst($tanque['forma']) }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Material</small>
                            <h6 class="mb-0">{{ $tanque['material'] }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Capacidades -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-weight-hanging"></i> Capacidades
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Capacidad Total</small>
                            <h6 class="mb-0">{{ number_format($tanque['capacidad'], 2) }} L</h6>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Capacidad Operativa</small>
                            <h6 class="mb-0">{{ number_format($tanque['capacidad_operativa'], 2) }} L</h6>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Capacidad de Seguridad</small>
                            <h6 class="mb-0">{{ number_format($tanque['capacidad_seguridad'], 2) }} L</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Dimensiones -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-ruler"></i> Dimensiones
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    @if($tanque['diametro'])
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Diámetro</small>
                            <h6 class="mb-0">{{ $tanque['diametro'] }} m</h6>
                        </div>
                    </div>
                    @endif
                    
                    @if($tanque['longitud'])
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Longitud</small>
                            <h6 class="mb-0">{{ $tanque['longitud'] }} m</h6>
                        </div>
                    </div>
                    @endif
                    
                    @if($tanque['altura_total'])
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Altura Total</small>
                            <h6 class="mb-0">{{ $tanque['altura_total'] }} m</h6>
                        </div>
                    </div>
                    @endif
                    
                    @if($tanque['altura_operativa'])
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Altura Operativa</small>
                            <h6 class="mb-0">{{ $tanque['altura_operativa'] }} m</h6>
                        </div>
                    </div>
                    @endif
                    
                    @if($tanque['altura_seguridad'])
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Altura Seguridad</small>
                            <h6 class="mb-0">{{ $tanque['altura_seguridad'] }} m</h6>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Configuración de Medición -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-tachometer-alt"></i> Configuración de Medición
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Tipo de Medición</small>
                            <h6 class="mb-0">{{ ucfirst($tanque['tipo_medicion']) }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Unidad de Medida</small>
                            <h6 class="mb-0">{{ ucfirst($tanque['unidad_medida']) }}</h6>
                        </div>
                    </div>
                    @if($tanque['precision_medicion'])
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Precisión</small>
                            <h6 class="mb-0">{{ $tanque['precision_medicion'] }}</h6>
                        </div>
                    </div>
                    @endif
                    
                    @if($tanque['factor_correccion'])
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Factor de Corrección</small>
                            <h6 class="mb-0">{{ $tanque['factor_correccion'] }}</h6>
                        </div>
                    </div>
                    @endif
                </div>
                
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
                            <small class="text-muted">Nivel</small>
                            <h6 class="mb-0">{{ $tanque['umbral_nivel_min'] ?? '-' }}% - {{ $tanque['umbral_nivel_max'] ?? '-' }}%</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Temperatura</small>
                            <h6 class="mb-0">{{ $tanque['umbral_temperatura_min'] ?? '-' }}°C - {{ $tanque['umbral_temperatura_max'] ?? '-' }}°C</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Presión</small>
                            <h6 class="mb-0">{{ $tanque['umbral_presion_min'] ?? '-' }} psi - {{ $tanque['umbral_presion_max'] ?? '-' }} psi</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Ubicación Geográfica -->
                @if($tanque['latitud'] && $tanque['longitud'])
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-map-marker-alt"></i> Ubicación Geográfica
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Latitud</small>
                            <h6 class="mb-0">{{ $tanque['latitud'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Longitud</small>
                            <h6 class="mb-0">{{ $tanque['longitud'] }}</h6>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <div id="map" style="height: 300px;"></div>
                    </div>
                </div>
                @endif
                
                <!-- Estado -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Estado</small>
                            <h6 class="mb-0">
                                @if($tanque['activo'])
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
                            <i class="fas fa-clock"></i> Creado: {{ $tanque['created_at'] ?? 'N/A' }} | 
                            Última actualización: {{ $tanque['updated_at'] ?? 'N/A' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Acciones rápidas -->
<div class="row mt-3">
    <div class="col-md-4">
        <div class="card bg-primary text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Existencias</h6>
                        <h3>{{ $tanque['existencias_count'] ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-boxes fa-2x"></i>
                </div>
                <a href="{{ route('tanques.existencias', $tanque['id']) }}" class="text-white stretched-link"></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-success text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Medidores</h6>
                        <h3>{{ $tanque['medidores_count'] ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-tachometer-alt fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-info text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Volumen Actual</h6>
                        <h3>{{ number_format($tanque['volumen_actual'] ?? 0, 2) }} L</h3>
                    </div>
                    <i class="fas fa-chart-line fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Últimas existencias -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Últimas Existencias</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Volumen Bruto</th>
                                <th>Volumen Neto</th>
                                <th>Temperatura</th>
                                <th>Densidad</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tanque['ultimas_existencias'] ?? [] as $existencia)
                            <tr>
                                <td>{{ $existencia['fecha_medicion'] }}</td>
                                <td>{{ $existencia['hora_medicion'] }}</td>
                                <td class="text-end">{{ number_format($existencia['volumen_bruto'], 2) }} L</td>
                                <td class="text-end">{{ number_format($existencia['volumen_neto'], 2) }} L</td>
                                <td class="text-end">{{ $existencia['temperatura'] }} °C</td>
                                <td class="text-end">{{ $existencia['densidad'] }}</td>
                                <td>
                                    @if($existencia['estado'] == 'valida')
                                        <span class="badge bg-success">Válida</span>
                                    @elseif($existencia['estado'] == 'invalida')
                                        <span class="badge bg-danger">Inválida</span>
                                    @else
                                        <span class="badge bg-warning">Pendiente</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No hay existencias registradas</td>
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

@push('scripts')
@if($tanque['latitud'] && $tanque['longitud'])
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
<script>
function initMap() {
    var location = { lat: {{ $tanque['latitud'] }}, lng: {{ $tanque['longitud'] }} };
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: location
    });
    var marker = new google.maps.Marker({
        position: location,
        map: map
    });
}
initMap();
</script>
@endif
@endpush