@extends('layouts.app')

@section('title', 'Detalle del Permiso')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Detalle del Permiso: {{ $permiso['display_name'] }}</h6>
                <div>
                    <a href="{{ route('permissions.edit', $permiso['id']) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('permissions.index') }}" class="btn btn-secondary btn-sm">
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
                            <small class="text-muted">ID</small>
                            <h6 class="mb-0">{{ $permiso['id'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Nombre</small>
                            <h6 class="mb-0"><code>{{ $permiso['name'] }}</code></h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Nombre para mostrar</small>
                            <h6 class="mb-0">{{ $permiso['display_name'] }}</h6>
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
                            <p class="mb-0">{{ $permiso['description'] ?? 'Sin descripción' }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Roles con este permiso -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-users"></i> Roles con este Permiso
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Nombre para mostrar</th>
                                        <th>Descripción</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($permiso['roles'] ?? [] as $role)
                                    <tr>
                                        <td>{{ $role['id'] }}</td>
                                        <td><code>{{ $role['name'] }}</code></td>
                                        <td>{{ $role['display_name'] }}</td>
                                        <td>{{ $role['description'] ?? 'Sin descripción' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('roles.show', $role['id']) }}" 
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No hay roles con este permiso</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Estadísticas -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="border p-3 rounded text-center">
                            <h3 class="text-primary">{{ count($permiso['roles'] ?? []) }}</h3>
                            <small class="text-muted">Roles que tienen este permiso</small>
                        </div>
                    </div>
                </div>
                
                <!-- Fechas de registro -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <hr>
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> Creado: {{ $permiso['created_at'] ?? 'N/A' }} | 
                            Última actualización: {{ $permiso['updated_at'] ?? 'N/A' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection