@extends('layouts.app')

@section('title', 'Detalle del Usuario')
@section('header', 'Detalle del Usuario')

@section('actions')
<a href="{{ route('users.edit', $user['id']) }}" class="btn btn-sm btn-warning">
    <i class="bi bi-pencil"></i> Editar
</a>
<a href="{{ route('users.permisos', $user['id']) }}" class="btn btn-sm btn-info">
    <i class="bi bi-key"></i> Permisos
</a>
<a href="{{ route('users.actividad', $user['id']) }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-clock-history"></i> Actividad
</a>
<a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Información Personal</h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-person-circle fs-1"></i>
                </div>
                <h4>{{ $user['nombres'] }} {{ $user['apellidos'] }}</h4>
                <p class="text-muted">{{ $user['email'] }}</p>
                
                <hr>
                
                <table class="table table-sm text-start">
                    <tr>
                        <th>Identificación:</th>
                        <td>{{ $user['identificacion'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Teléfono:</th>
                        <td>{{ $user['telefono'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Dirección:</th>
                        <td>{{ $user['direccion'] ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Estado de la Cuenta</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th>Estado:</th>
                                <td>
                                    @if($user['bloqueado_hasta'] && now() < \Carbon\Carbon::parse($user['bloqueado_hasta']))
                                        <span class="badge bg-danger">Bloqueado</span>
                                    @elseif(!$user['activo'])
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @else
                                        <span class="badge bg-success">Activo</span>
                                    @endif
                                </td>
                            </tr>
                            @if($user['bloqueado_hasta'] && now() < \Carbon\Carbon::parse($user['bloqueado_hasta']))
                                <tr>
                                    <th>Bloqueado hasta:</th>
                                    <td>{{ $user['bloqueado_hasta'] }}</td>
                                </tr>
                                <tr>
                                    <th>Motivo:</th>
                                    <td>{{ $user['motivo_bloqueo'] ?? 'No especificado' }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Último acceso:</th>
                                <td>{{ $user['ultimo_acceso'] ?? 'Nunca' }}</td>
                            </tr>
                            <tr>
                                <th>Última IP:</th>
                                <td>{{ $user['ultima_ip'] ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th>Email verificado:</th>
                                <td>
                                    @if($user['email_verified_at'])
                                        <span class="badge bg-success">Sí</span>
                                        <small>{{ $user['email_verified_at'] }}</small>
                                    @else
                                        <span class="badge bg-warning">No</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Cambio de contraseña:</th>
                                <td>
                                    @if($user['force_password_change'] ?? false)
                                        <span class="badge bg-warning">Requerido</span>
                                    @else
                                        <span class="badge bg-success">No requerido</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Contraseña actualizada:</th>
                                <td>{{ $user['password_changed_at'] ?? 'Nunca' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Roles Asignados</h5>
            </div>
            <div class="card-body">
                @if(!empty($user['roles']))
                    <div class="row">
                        @foreach($user['roles'] as $rol)
                            <div class="col-md-4 mb-2">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <strong>{{ $rol['nombre'] }}</strong>
                                        <p class="small text-muted mb-0">{{ $rol['descripcion'] ?? '' }}</p>
                                        <small>Nivel: {{ $rol['nivel_jerarquico'] }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No tiene roles asignados</p>
                @endif
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">Actividad Reciente</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Fecha/Hora</th>
                                <th>Evento</th>
                                <th>Módulo</th>
                                <th>IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user['actividad_reciente'] ?? [] as $actividad)
                                <tr>
                                    <td>{{ $actividad['fecha_hora'] }}</td>
                                    <td>{{ $actividad['tipo_evento'] }}</td>
                                    <td>{{ $actividad['modulo'] ?? '-' }}</td>
                                    <td>{{ $actividad['ip_address'] ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No hay actividad reciente</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="text-end">
                    <a href="{{ route('users.actividad', $user['id']) }}" class="btn btn-sm btn-primary">
                        Ver toda la actividad
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Acciones adicionales -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Acciones</h5>
            </div>
            <div class="card-body">
                @if($user['activo'] && !$user['bloqueado_hasta'])
                    <button type="button" class="btn btn-danger" onclick="confirmarBloqueo({{ $user['id'] }})">
                        <i class="bi bi-lock"></i> Bloquear Usuario
                    </button>
                @endif
                
                @if($user['bloqueado_hasta'] && now() < \Carbon\Carbon::parse($user['bloqueado_hasta']))
                    <form method="POST" action="{{ route('users.desbloquear', $user['id']) }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="motivo" value="Desbloqueo por administrador">
                        <button type="submit" class="btn btn-success" onclick="return confirm('¿Está seguro de desbloquear este usuario?')">
                            <i class="bi bi-unlock"></i> Desbloquear Usuario
                        </button>
                    </form>
                @endif
                
                @if($user['activo'])
                    <form method="POST" action="{{ route('users.destroy', $user['id']) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-warning" onclick="return confirm('¿Está seguro de desactivar este usuario?')">
                            <i class="bi bi-person-x"></i> Desactivar Usuario
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('users.update', $user['id']) }}" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="activo" value="1">
                        <button type="submit" class="btn btn-success" onclick="return confirm('¿Está seguro de activar este usuario?')">
                            <i class="bi bi-person-check"></i> Activar Usuario
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de bloqueo -->
<div class="modal fade" id="bloquearModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Bloquear Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('users.bloquear', $user['id']) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="motivo" class="form-label">Motivo del Bloqueo</label>
                        <textarea class="form-control" id="motivo" name="motivo" 
                                  rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="minutos_bloqueo" class="form-label">Duración del Bloqueo (minutos)</label>
                        <select class="form-select" id="minutos_bloqueo" name="minutos_bloqueo">
                            <option value="30">30 minutos</option>
                            <option value="60">1 hora</option>
                            <option value="120">2 horas</option>
                            <option value="1440">24 horas</option>
                            <option value="4320">3 días</option>
                            <option value="10080">7 días</option>
                            <option value="0">Permanente</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Bloquear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmarBloqueo(id) {
    new bootstrap.Modal(document.getElementById('bloquearModal')).show();
}
</script>
@endpush