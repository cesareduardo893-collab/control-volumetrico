@extends('layouts.app')

@section('title', 'Detalle del Rol')
@section('header', 'Detalle del Rol')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ $role['nombre'] }}</h5>
                <div>
                    @if($role['es_administrador'])
                        <span class="badge bg-success">Administrador</span>
                    @endif
                    @if($role['activo'])
                        <span class="badge bg-info">Activo</span>
                    @else
                        <span class="badge bg-secondary">Inactivo</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <!-- Información básica -->
                <h6 class="mb-3">Información del Rol</h6>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nombre</label>
                            <p class="form-control-static">{{ $role['nombre'] }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nivel Jerárquico</label>
                            <p class="form-control-static">
                                <span class="badge bg-info">{{ $role['nivel_jerarquico'] }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label text-muted">Descripción</label>
                    <p class="form-control-static">{{ $role['descripcion'] ?? 'Sin descripción' }}</p>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Es Administrador</label>
                            <p class="form-control-static">
                                @if($role['es_administrador'])
                                    <span class="text-success"><i class="bi bi-check-circle"></i> Sí</span>
                                @else
                                    <span class="text-muted"><i class="bi bi-circle"></i> No</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Estado</label>
                            <p class="form-control-static">
                                @if($role['activo'])
                                    <span class="text-success"><i class="bi bi-check-circle"></i> Activo</span>
                                @else
                                    <span class="text-muted"><i class="bi bi-circle"></i> Inactivo</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <!-- Permisos -->
                <h6 class="mb-3">Permisos Asignados</h6>
                
                @if(!empty($permisosAgrupados))
                    <div class="row">
                        @foreach($permisosAgrupados as $modulo)
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-header bg-light py-2">
                                        <strong>{{ $modulo['modulo'] }}</strong>
                                    </div>
                                    <div class="card-body p-2">
                                        @if(count($modulo['permisos']) > 0)
                                            <ul class="list-unstyled mb-0">
                                                @foreach($modulo['permisos'] as $permiso)
                                                    <li class="mb-1">
                                                        <i class="bi bi-check text-success"></i>
                                                        {{ $permiso['name'] }}
                                                        <small class="text-muted">({{ $permiso['slug'] }})</small>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-muted mb-0 small">No hay permisos en este módulo</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> No se encontraron permisos asignados a este rol.
                    </div>
                @endif
                
                <hr>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver a Roles
                    </a>
                    <div>
                        <a href="{{ route('roles.edit', $role['id']) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Editar Rol
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
