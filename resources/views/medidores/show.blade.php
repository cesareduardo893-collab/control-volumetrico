@extends('layouts.app')

@section('title', 'Detalle del Medidor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Detalle del Medidor: {{ $medidor['nombre'] }}</h6>
                <div>
                    <a href="{{ route('medidores.edit', $medidor['id']) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('medidores.index') }}" class="btn btn-secondary btn-sm">
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
                            <small class="text-muted">Clave Medidor</small>
                            <h6 class="mb-0">{{ $medidor['clave_medidor'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Nombre</small>
                            <h6 class="mb-0">{{ $medidor['nombre'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Tipo</small>
                            <h6 class="mb-0">{{ ucfirst($medidor['tipo_medidor']) }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Asignación -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-link"></i> Asignación
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    @if($medidor['tanque_id'])
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Tanque Asignado</small>
                            <h6 class="mb-0">{{ $medidor['tanque']['clave_tanque'] ?? '' }} - {{ $medidor['tanque']['nombre'] ?? '' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Instalación</small>
                            <h6 class="mb-0">{{ $medidor['tanque']['instalacion']['nombre'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    @elseif($medidor['dispensario_id'])
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Dispensario Asignado</small>
                            <h6 class="mb-0">{{ $medidor['dispensario']['clave_dispensario'] ?? '' }} - {{ $medidor['dispensario']['nombre'] ?? '' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Instalación</small>
                            <h6 class="mb-0">{{ $medidor['dispensario']['instalacion']['nombre'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    @else
                    <div class="col-md-12">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Asignación</small>
                            <h6 class="mb-0">Sin asignar</h6>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Especificaciones Técnicas -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-cog"></i> Especificaciones Técnicas
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Marca</small>
                            <h6 class="mb-0">{{ $medidor['marca'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Modelo</small>
                            <h6 class="mb-0">{{ $medidor['modelo'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Serie</small>
                            <h6 class="mb-0">{{ $medidor['serie'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Unidad</small>
                            <h6 class="mb-0">{{ ucfirst($medidor['unidad_medida']) }}</h6>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Rango Mínimo</small>
                            <h6 class="mb-0">{{ $medidor['rango_medicion_min'] }} {{ $medidor['unidad_medida'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Rango Máximo</small>
                            <h6 class="mb-0">{{ $medidor['rango_medicion_max'] }} {{ $medidor['unidad_medida'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Precisión</small>
                            <h6 class="mb-0">{{ $medidor['precision'] }}%</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Resolución</small>
                            <h6 class="mb-0">{{ $medidor['resolucion'] }} {{ $medidor['unidad_medida'] }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Comunicación -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-network-wired"></i> Comunicación
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Tipo</small>
                            <h6 class="mb-0">{{ strtoupper($medidor['tipo_comunicacion']) }}</h6>
                        </div>
                    </div>
                    @if($medidor['protocolo_comunicacion'])
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Protocolo</small>
                            <h6 class="mb-0">{{ $medidor['protocolo_comunicacion'] }}</h6>
                        </div>
                    </div>
                    @endif
                    @if($medidor['direccion_comunicacion'])
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Dirección</small>
                            <h6 class="mb-0">{{ $medidor['direccion_comunicacion'] }}</h6>
                        </div>
                    </div>
                    @endif
                    @if($medidor['frecuencia_muestreo'])
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Frecuencia</small>
                            <h6 class="mb-0">Cada {{ $medidor['frecuencia_muestreo'] }} seg</h6>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Calibración -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-tools"></i> Calibración
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Estado</small>
                            <h6 class="mb-0">
                                @if($medidor['estado_calibracion'] == 'calibrado')
                                    <span class="badge bg-success">Calibrado</span>
                                @elseif($medidor['estado_calibracion'] == 'no_calibrado')
                                    <span class="badge bg-danger">No calibrado</span>
                                @else
                                    <span class="badge bg-warning">Pendiente</span>
                                @endif
                            </h6>
                        </div>
                    </div>
                    @if($medidor['fecha_calibracion'])
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Última Calibración</small>
                            <h6 class="mb-0">{{ $medidor['fecha_calibracion'] }}</h6>
                        </div>
                    </div>
                    @endif
                    @if($medidor['fecha_proxima_calibracion'])
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Próxima Calibración</small>
                            <h6 class="mb-0">{{ $medidor['fecha_proxima_calibracion'] }}</h6>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Umbrales de Alarma -->
                @if($medidor['umbral_flujo_min'] || $medidor['umbral_flujo_max'] || $medidor['umbral_presion_min'] || $medidor['umbral_presion_max'] || $medidor['umbral_temperatura_min'] || $medidor['umbral_temperatura_max'])
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-exclamation-triangle"></i> Umbrales de Alarma
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    @if($medidor['umbral_flujo_min'] || $medidor['umbral_flujo_max'])
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Flujo</small>
                            <h6 class="mb-0">{{ $medidor['umbral_flujo_min'] ?? '-' }} - {{ $medidor['umbral_flujo_max'] ?? '-' }} {{ $medidor['unidad_medida'] }}/min</h6>
                        </div>
                    </div>
                    @endif
                    
                    @if($medidor['umbral_presion_min'] || $medidor['umbral_presion_max'])
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Presión</small>
                            <h6 class="mb-0">{{ $medidor['umbral_presion_min'] ?? '-' }} - {{ $medidor['umbral_presion_max'] ?? '-' }} psi</h6>
                        </div>
                    </div>
                    @endif
                    
                    @if($medidor['umbral_temperatura_min'] || $medidor['umbral_temperatura_max'])
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Temperatura</small>
                            <h6 class="mb-0">{{ $medidor['umbral_temperatura_min'] ?? '-' }} - {{ $medidor['umbral_temperatura_max'] ?? '-' }} °C</h6>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
                
                <!-- Estado -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Estado</small>
                            <h6 class="mb-0">
                                @if($medidor['activo'])
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
                            <i class="fas fa-clock"></i> Creado: {{ $medidor['created_at'] ?? 'N/A' }} | 
                            Última actualización: {{ $medidor['updated_at'] ?? 'N/A' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Historial de calibraciones -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Historial de Calibraciones</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Valor Referencia</th>
                                <th>Valor Medido</th>
                                <th>Factor Corrección</th>
                                <th>Técnico</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($medidor['calibraciones'] ?? [] as $calibracion)
                            <tr>
                                <td>{{ $calibracion['fecha_calibracion'] }}</td>
                                <td class="text-end">{{ $calibracion['valor_referencia'] }}</td>
                                <td class="text-end">{{ $calibracion['valor_medido'] }}</td>
                                <td class="text-end">{{ $calibracion['factor_correccion'] }}</td>
                                <td>{{ $calibracion['tecnico_calibracion'] }}</td>
                                <td>{{ $calibracion['observaciones'] ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay calibraciones registradas</td>
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