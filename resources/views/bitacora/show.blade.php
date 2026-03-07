@extends('layouts.app')

@section('title', 'Detalle del Evento')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Detalle del Evento #{{ $evento['id'] }}</h6>
                <a href="{{ route('bitacora.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
            <div class="card-body">
                <!-- Información General -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Información del Evento
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">ID del Evento</small>
                            <h6 class="mb-0">{{ $evento['id'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Fecha/Hora</small>
                            <h6 class="mb-0">{{ $evento['fecha'] }} {{ $evento['hora'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Usuario</small>
                            <h6 class="mb-0">{{ $evento['usuario']['name'] ?? 'Sistema' }}</h6>
                            <small>{{ $evento['usuario']['email'] ?? '' }}</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">IP Address</small>
                            <h6 class="mb-0">{{ $evento['ip_address'] }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Tipo de Evento -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-tag"></i> Tipo de Evento
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Tipo</small>
                            <h6 class="mb-0">
                                @switch($evento['tipo_evento'])
                                    @case('login')
                                        <span class="badge bg-success">Login</span>
                                        @break
                                    @case('logout')
                                        <span class="badge bg-secondary">Logout</span>
                                        @break
                                    @case('create')
                                        <span class="badge bg-primary">Creación</span>
                                        @break
                                    @case('update')
                                        <span class="badge bg-warning">Actualización</span>
                                        @break
                                    @case('delete')
                                        <span class="badge bg-danger">Eliminación</span>
                                        @break
                                    @default
                                        <span class="badge bg-info">{{ $evento['tipo_evento'] }}</span>
                                @endswitch
                            </h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Módulo</small>
                            <h6 class="mb-0">{{ $evento['modulo'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Acción</small>
                            <h6 class="mb-0">{{ $evento['accion'] }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Descripción -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-align-left"></i> Descripción
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="border p-3 rounded">
                            <p class="mb-0">{{ $evento['descripcion'] }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Detalles Adicionales -->
                @if($evento['detalles'])
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-list"></i> Detalles Adicionales
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="border p-3 rounded">
                            <pre class="mb-0" style="white-space: pre-wrap;">{{ json_encode($evento['detalles'], JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Datos de Auditoría -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-clipboard-list"></i> Datos de Auditoría
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">User Agent</small>
                            <h6 class="mb-0 small">{{ $evento['user_agent'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Método HTTP</small>
                            <h6 class="mb-0">{{ $evento['http_method'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">URL</small>
                            <h6 class="mb-0 small">{{ $evento['url'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Fechas de registro -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <hr>
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> Registrado: {{ $evento['created_at'] ?? 'N/A' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection