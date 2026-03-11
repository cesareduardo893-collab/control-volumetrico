@extends('layouts.app')

@section('title', 'Mi Perfil')
@section('header', 'Mi Perfil')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-person-circle fs-1 text-primary"></i>
                </div>
                <h5 class="card-title">{{ $user['nombres'] }} {{ $user['apellidos'] }}</h5>
                <p class="text-muted">{{ $user['email'] }}</p>
                
                <hr>
                
                <div class="text-start">
                    <p><strong>Identificación:</strong> {{ $user['identificacion'] ?? 'No especificada' }}</p>
                    <p><strong>Teléfono:</strong> {{ $user['telefono'] ?? 'No especificado' }}</p>
                    <p><strong>Dirección:</strong> {{ $user['direccion'] ?? 'No especificada' }}</p>
                    <p><strong>Último acceso:</strong> {{ $user['ultimo_acceso'] ?? 'Nunca' }}</p>
                </div>
                
                <hr>
                
                <a href="{{ route('password.change.form') }}" class="btn btn-warning w-100">
                    <i class="bi bi-key"></i> Cambiar Contraseña
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Roles y Permisos</h5>
            </div>
            <div class="card-body">
                <h6>Roles Asignados:</h6>
                <div class="mb-3">
                    @foreach($user['roles'] ?? [] as $rol)
                        <span class="badge bg-primary me-1">{{ $rol['nombre'] }}</span>
                    @endforeach
                </div>
                
                <h6>Permisos:</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Módulo</th>
                                <th>Permisos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $permisosAgrupados = collect($user['permissions'] ?? [])->groupBy('modulo');
                            @endphp
                            
                            @forelse($permisosAgrupados as $modulo => $permisos)
                                <tr>
                                    <td><strong>{{ $modulo }}</strong></td>
                                    <td>
                                        @foreach($permisos as $permiso)
                                            <span class="badge bg-secondary me-1">{{ $permiso['name'] }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">No tiene permisos asignados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Actividad Reciente</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Evento</th>
                                <th>Módulo</th>
                                <th>IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user['actividad_reciente'] ?? [] as $actividad)
                                <tr>
                                    <td>{{ $actividad['fecha_hora'] }}</td>
                                    <td>{{ $actividad['tipo_evento'] }}</td>
                                    <td>{{ $actividad['modulo'] ?? '-' }}</td>
                                    <td>{{ $actividad['ip_address'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection