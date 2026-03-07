@extends('layouts.app')

@section('title', 'Detalle del Usuario')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Detalle del Usuario</h6>
                <div>
                    <a href="{{ route('usuarios.edit', $user['id']) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Avatar e Información Principal -->
                <div class="row mb-4">
                    <div class="col-md-2 text-center">
                        <div class="avatar-circle-large mb-3" style="width: 120px; height: 120px; background-color: #e9ecef; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <span class="text-primary" style="font-size: 48px; font-weight: bold;">{{ substr($user['name'], 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <h3>{{ $user['name'] }}</h3>
                        <p class="text-muted">{{ $user['email'] }}</p>
                        <div>
                            @foreach($user['roles'] ?? [] as $role)
                                <span class="badge bg-info me-1">{{ $role['display_name'] }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Información de Contacto -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-address-card"></i> Información de Contacto
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Teléfono</small>
                            <h6 class="mb-0">{{ $user['telefono'] ?? 'No registrado' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Dirección</small>
                            <h6 class="mb-0">{{ $user['direccion'] ?? 'No registrada' }}</h6>
                        </div>
                    </div>
                </div>
                
                <!-- Actividad del Usuario -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-history"></i> Actividad del Usuario
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Último Acceso</small>
                            <h6 class="mb-0">{{ $user['last_login'] ?? 'Nunca' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Última IP</small>
                            <h6 class="mb-0">{{ $user['last_ip'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Registrado desde</small>
                            <h6 class="mb-0">{{ $user['created_at'] ?? 'N/A' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Estado</small>
                            <h6 class="mb-0">
                                @if($user['is_active'])
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </h6>
                        </div>
                    </div>
                </div>
                
                <!-- Permisos Directos -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-shield-alt"></i> Permisos Directos
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="border p-3 rounded">
                            @forelse($user['permissions'] ?? [] as $permission)
                                <span class="badge bg-secondary me-1 mb-1">{{ $permission['display_name'] }}</span>
                            @empty
                                <p class="text-muted mb-0">No tiene permisos directos asignados</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                <!-- Últimas Actividades en Bitácora -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-clipboard-list"></i> Últimas Actividades
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Fecha/Hora</th>
                                        <th>Tipo</th>
                                        <th>Módulo</th>
                                        <th>Acción</th>
                                        <th>IP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($user['ultimas_actividades'] ?? [] as $actividad)
                                    <tr>
                                        <td>{{ $actividad['fecha'] }} {{ $actividad['hora'] }}</td>
                                        <td>
                                            @switch($actividad['tipo_evento'])
                                                @case('login')
                                                    <span class="badge bg-success">Login</span>
                                                    @break
                                                @case('logout')
                                                    <span class="badge bg-secondary">Logout</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-info">{{ $actividad['tipo_evento'] }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $actividad['modulo'] }}</td>
                                        <td>{{ $actividad['accion'] }}</td>
                                        <td>{{ $actividad['ip_address'] }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No hay actividades registradas</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Fechas de registro -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <hr>
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> Creado: {{ $user['created_at'] ?? 'N/A' }} | 
                            Última actualización: {{ $user['updated_at'] ?? 'N/A' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-circle-large {
    width: 120px;
    height: 120px;
    background-color: #e9ecef;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.avatar-circle-large span {
    font-size: 48px;
    font-weight: bold;
    color: #0d6efd;
}
</style>
@endpush