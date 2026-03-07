@extends('layouts.app')

@section('title', 'Detalle del Contribuyente')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Detalle del Contribuyente</h6>
                <div>
                    <a href="{{ route('contribuyentes.edit', $contribuyente['id']) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('contribuyentes.index') }}" class="btn btn-secondary btn-sm">
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
                            <small class="text-muted">RFC</small>
                            <h6 class="mb-0">{{ $contribuyente['rfc'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Razón Social</small>
                            <h6 class="mb-0">{{ $contribuyente['razon_social'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Nombre Comercial</small>
                            <h6 class="mb-0">{{ $contribuyente['nombre_comercial'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Régimen Fiscal</small>
                            <h6 class="mb-0">{{ $contribuyente['regimen_fiscal'] }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Domicilio Fiscal -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-map-marker-alt"></i> Domicilio Fiscal
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Domicilio</small>
                            <h6 class="mb-0">{{ $contribuyente['domicilio_fiscal'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Código Postal</small>
                            <h6 class="mb-0">{{ $contribuyente['codigo_postal'] }}</h6>
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
                            <h6 class="mb-0">{{ $contribuyente['telefono'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Email</small>
                            <h6 class="mb-0">{{ $contribuyente['email'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Representante Legal -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-user-tie"></i> Representante Legal
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Nombre</small>
                            <h6 class="mb-0">{{ $contribuyente['representante_legal'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">RFC</small>
                            <h6 class="mb-0">{{ $contribuyente['representante_rfc'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Carácter y Permisos -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-file-signature"></i> Carácter y Permisos
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Carácter</small>
                            <h6 class="mb-0">{{ ucfirst($contribuyente['caracter_actua']) }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Número de Permiso</small>
                            <h6 class="mb-0">{{ $contribuyente['numero_permiso'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Tipo de Permiso</small>
                            <h6 class="mb-0">{{ $contribuyente['tipo_permiso'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Estado</small>
                            <h6 class="mb-0">
                                @if($contribuyente['activo'])
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </h6>
                        </div>
                    </div>
                </div>
                
                <!-- Proveedor de Equipos -->
                @if($contribuyente['proveedor_equipos_rfc'] || $contribuyente['proveedor_equipos_nombre'])
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-tools"></i> Proveedor de Equipos
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">RFC Proveedor</small>
                            <h6 class="mb-0">{{ $contribuyente['proveedor_equipos_rfc'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Nombre del Proveedor</small>
                            <h6 class="mb-0">{{ $contribuyente['proveedor_equipos_nombre'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Fechas de registro -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <hr>
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> Creado: {{ $contribuyente['created_at'] ?? 'N/A' }} | 
                            Última actualización: {{ $contribuyente['updated_at'] ?? 'N/A' }}
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
                        <h6 class="mb-0">Instalaciones</h6>
                        <h3>{{ $contribuyente['instalaciones_count'] ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-gas-pump fa-2x"></i>
                </div>
                <a href="{{ route('contribuyentes.instalaciones', $contribuyente['id']) }}" class="text-white stretched-link"></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Cumplimiento</h6>
                        <h3>{{ $contribuyente['cumplimiento_porcentaje'] ?? 0 }}%</h3>
                    </div>
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
                <a href="{{ route('contribuyentes.cumplimiento', $contribuyente['id']) }}" class="text-white stretched-link"></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Tanques</h6>
                        <h3>{{ $contribuyente['tanques_count'] ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-oil-can fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Volumen Total</h6>
                        <h3>{{ number_format($contribuyente['volumen_total'] ?? 0, 2) }} L</h3>
                    </div>
                    <i class="fas fa-chart-line fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection