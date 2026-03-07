@extends('layouts.app')

@section('title', 'Detalle del Dictamen')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Detalle del Dictamen: {{ $dictamen['numero_dictamen'] }}</h6>
                <div>
                    <a href="{{ route('dictamenes.edit', $dictamen['id']) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('dictamenes.index') }}" class="btn btn-secondary btn-sm">
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
                            <small class="text-muted">Número de Dictamen</small>
                            <h6 class="mb-0">{{ $dictamen['numero_dictamen'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Instalación</small>
                            <h6 class="mb-0">{{ $dictamen['instalacion']['nombre'] ?? 'N/A' }}</h6>
                            <small>{{ $dictamen['instalacion']['clave_instalacion'] ?? '' }}</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Tipo de Dictamen</small>
                            <h6 class="mb-0">
                                @switch($dictamen['tipo_dictamen'])
                                    @case('inicial')
                                        Inicial
                                        @break
                                    @case('periodico')
                                        Periódico
                                        @break
                                    @case('extraordinario')
                                        Extraordinario
                                        @break
                                @endswitch
                            </h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Estatus</small>
                            <h6 class="mb-0">
                                @if($dictamen['estatus'] == 'aprobado')
                                    <span class="badge bg-success">Aprobado</span>
                                @elseif($dictamen['estatus'] == 'rechazado')
                                    <span class="badge bg-danger">Rechazado</span>
                                @else
                                    <span class="badge bg-warning">Pendiente</span>
                                @endif
                            </h6>
                        </div>
                    </div>
                </div>
                
                <!-- Fechas -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-calendar-alt"></i> Fechas
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Fecha de Emisión</small>
                            <h6 class="mb-0">{{ $dictamen['fecha_emision'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Fecha de Vigencia</small>
                            <h6 class="mb-0">{{ $dictamen['fecha_vigencia'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Vigencia</small>
                            @php
                                $hoy = new DateTime();
                                $vigencia = new DateTime($dictamen['fecha_vigencia']);
                                $dias_restantes = $hoy->diff($vigencia)->days;
                                $vigente = $vigencia > $hoy;
                            @endphp
                            <h6 class="mb-0">
                                @if($vigente)
                                    <span class="badge bg-success">Vigente ({{ $dias_restantes }} días restantes)</span>
                                @else
                                    <span class="badge bg-danger">Vencido</span>
                                @endif
                            </h6>
                        </div>
                    </div>
                </div>
                
                <!-- Resultados -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-clipboard-check"></i> Resultados
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Resultado</small>
                            <h6 class="mb-0">
                                @if($dictamen['resultado'] == 'aprobado')
                                    <span class="badge bg-success">Aprobado</span>
                                @elseif($dictamen['resultado'] == 'rechazado')
                                    <span class="badge bg-danger">Rechazado</span>
                                @else
                                    <span class="badge bg-info">Con Observaciones</span>
                                @endif
                            </h6>
                        </div>
                    </div>
                </div>
                
                <!-- Puntos de Verificación -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-exclamation-triangle"></i> Puntos de Verificación
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="border p-3 rounded text-center">
                            <h2 class="text-danger mb-0">{{ $dictamen['puntos_criticos'] ?? 0 }}</h2>
                            <small class="text-muted">Puntos Críticos</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded text-center">
                            <h2 class="text-warning mb-0">{{ $dictamen['puntos_atencion'] ?? 0 }}</h2>
                            <small class="text-muted">Puntos de Atención</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded text-center">
                            <h2 class="text-info mb-0">{{ $dictamen['puntos_leves'] ?? 0 }}</h2>
                            <small class="text-muted">Puntos Leves</small>
                        </div>
                    </div>
                </div>
                
                <!-- Usuarios -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-users"></i> Usuarios Responsables
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Elaborado por</small>
                            <h6 class="mb-0">{{ $dictamen['usuario_elaboracion']['name'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    @if($dictamen['usuario_autorizacion'])
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Autorizado por</small>
                            <h6 class="mb-0">{{ $dictamen['usuario_autorizacion']['name'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Observaciones -->
                @if($dictamen['observaciones'])
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-comment"></i> Observaciones
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="border p-3 rounded">
                            <p class="mb-0">{{ $dictamen['observaciones'] }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Estado -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Estado</small>
                            <h6 class="mb-0">
                                @if($dictamen['activo'])
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
                            <i class="fas fa-clock"></i> Creado: {{ $dictamen['created_at'] ?? 'N/A' }} | 
                            Última actualización: {{ $dictamen['updated_at'] ?? 'N/A' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Documentos asociados -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Registros Volumétricos Asociados</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Instalación</th>
                                <th>Tanque</th>
                                <th>Volumen</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dictamen['registros_volumetricos'] ?? [] as $registro)
                            <tr>
                                <td>{{ $registro['id'] }}</td>
                                <td>{{ $registro['fecha_movimiento'] }}</td>
                                <td>{{ $registro['instalacion']['nombre'] ?? 'N/A' }}</td>
                                <td>{{ $registro['tanque']['nombre'] ?? 'N/A' }}</td>
                                <td class="text-end">{{ number_format($registro['volumen_neto'], 2) }} L</td>
                                <td class="text-center">
                                    <a href="{{ route('registros-volumetricos.show', $registro['id']) }}" 
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay registros asociados</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Acciones adicionales -->
<div class="row mt-3">
    <div class="col-md-4">
        <div class="card bg-primary text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Generar PDF</h6>
                    </div>
                    <i class="fas fa-file-pdf fa-2x"></i>
                </div>
                <a href="{{ route('dictamenes.pdf', $dictamen['id']) }}" class="btn btn-light btn-sm" target="_blank">
                    <i class="fas fa-download"></i> Descargar
                </a>
            </div>
        </div>
    </div>
    
    @if($dictamen['estatus'] == 'aprobado')
    <div class="col-md-4">
        <div class="card bg-success text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Certificado</h6>
                        <small>Generar certificado</small>
                    </div>
                    <i class="fas fa-certificate fa-2x"></i>
                </div>
                <a href="{{ route('certificados-verificacion.create', ['dictamen_id' => $dictamen['id']]) }}" 
                   class="btn btn-light btn-sm">
                    <i class="fas fa-plus"></i> Crear
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection