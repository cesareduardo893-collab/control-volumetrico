@extends('layouts.app')

@section('title', 'Usuarios')
@section('header', 'Administración de Usuarios')

@section('actions')
<a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-circle"></i> Nuevo Usuario
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Filtros -->
        <form method="GET" action="{{ route('users.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="identificacion" class="form-label">Identificación</label>
                <input type="text" class="form-control" id="identificacion" name="identificacion" 
                       value="{{ request('identificacion') }}" placeholder="Buscar por identificación">
            </div>
            
            <div class="col-md-3">
                <label for="nombres" class="form-label">Nombres</label>
                <input type="text" class="form-control" id="nombres" name="nombres" 
                       value="{{ request('nombres') }}" placeholder="Buscar por nombres">
            </div>
            
            <div class="col-md-3">
                <label for="apellidos" class="form-label">Apellidos</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" 
                       value="{{ request('apellidos') }}" placeholder="Buscar por apellidos">
            </div>
            
            <div class="col-md-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email" 
                       value="{{ request('email') }}" placeholder="Buscar por email">
            </div>
            
            <div class="col-md-3">
                <label for="role_id" class="form-label">Rol</label>
                <select class="form-select select2" id="role_id" name="role_id">
                    <option value="">Todos</option>
                    @foreach($roles ?? [] as $rol)
                        <option value="{{ $rol['id'] }}" {{ request('role_id') == $rol['id'] ? 'selected' : '' }}>
                            {{ $rol['nombre'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="activo" class="form-label">Activo</label>
                <select class="form-select" id="activo" name="activo">
                    <option value="">Todos</option>
                    <option value="1" {{ request('activo') == '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('activo') == '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="bloqueados" class="form-label">Bloqueados</label>
                <select class="form-select" id="bloqueados" name="bloqueados">
                    <option value="">Todos</option>
                    <option value="1" {{ request('bloqueados') == '1' ? 'selected' : '' }}>Bloqueados</option>
                    <option value="0" {{ request('bloqueados') == '0' ? 'selected' : '' }}>No Bloqueados</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="per_page" class="form-label">Registros por página</label>
                <select class="form-select" id="per_page" name="per_page">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
            
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filtrar
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="bi bi-eraser"></i> Limpiar
                </a>
            </div>
        </form>
        
        <!-- Tabla de usuarios -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Identificación</th>
                        <th>Nombre Completo</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Roles</th>
                        <th>Estado</th>
                        <th>Último Acceso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user['identificacion'] ?? 'N/A' }}</td>
                            <td>
                                <strong>{{ $user['nombres'] }} {{ $user['apellidos'] }}</strong>
                            </td>
                            <td>{{ $user['email'] }}</td>
                            <td>{{ $user['telefono'] ?? 'N/A' }}</td>
                            <td>
                                @foreach($user['roles'] ?? [] as $rol)
                                    <span class="badge bg-primary">{{ $rol['nombre'] }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if($user['locked_until'] && now() < \Carbon\Carbon::parse($user['locked_until']))
                                    <span class="badge bg-danger">Bloqueado</span>
                                    <small class="d-block">Hasta: {{ $user['locked_until'] }}</small>
                                @elseif(!$user['activo'])
                                    <span class="badge bg-secondary">Inactivo</span>
                                @else
                                    <span class="badge bg-success">Activo</span>
                                @endif
                            </td>
                            <td>{{ $user['ultimo_acceso'] ?? 'Nunca' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('users.show', $user['id']) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user['id']) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('users.permisos', $user['id']) }}" class="btn btn-sm btn-secondary" title="Permisos">
                                        <i class="bi bi-key"></i>
                                    </a>
                                    <a href="{{ route('users.actividad', $user['id']) }}" class="btn btn-sm btn-primary" title="Actividad">
                                        <i class="bi bi-clock-history"></i>
                                    </a>
                                    @if($user['activo'] && !$user['locked_until'])
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="confirmarBloqueo({{ $user['id'] }})" title="Bloquear">
                                            <i class="bi bi-lock"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay usuarios registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        @if(isset($meta))
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Mostrando {{ $meta['from'] ?? 0 }} - {{ $meta['to'] ?? 0 }} de {{ $meta['total'] ?? 0 }} registros
            </div>
            <nav>
                <ul class="pagination">
                    @if(isset($links['prev']))
                        <li class="page-item">
                            <a class="page-link" href="{{ $links['prev'] }}" aria-label="Anterior">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    @endif
                    
                    @for($i = 1; $i <= ($meta['last_page'] ?? 1); $i++)
                        <li class="page-item {{ $i == ($meta['current_page'] ?? 1) ? 'active' : '' }}">
                            <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    
                    @if(isset($links['next']))
                        <li class="page-item">
                            <a class="page-link" href="{{ $links['next'] }}" aria-label="Siguiente">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
        @endif
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
            <form id="bloquearForm" method="POST">
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
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
});

function confirmarBloqueo(id) {
    $('#bloquearForm').attr('action', `{{ url('users') }}/${id}/bloquear`);
    new bootstrap.Modal(document.getElementById('bloquearModal')).show();
}
</script>
@endpush