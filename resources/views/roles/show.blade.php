@extends('layouts.app')

@section('title', 'Detalle del Rol')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Detalle del Rol: {{ $role['display_name'] }}</h6>
                <div>
                    <a href="{{ route('roles.edit', $role['id']) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <button type="button" class="btn btn-success btn-sm btn-permisos" data-id="{{ $role['id'] }}">
                        <i class="fas fa-shield-alt"></i> Asignar Permisos
                    </button>
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-sm">
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
                            <h6 class="mb-0">{{ $role['id'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Nombre</small>
                            <h6 class="mb-0">{{ $role['name'] }}</h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <small class="text-muted">Nombre para mostrar</small>
                            <h6 class="mb-0">{{ $role['display_name'] }}</h6>
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
                            <p class="mb-0">{{ $role['description'] ?? 'Sin descripción' }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Usuarios con este rol -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-users"></i> Usuarios con este Rol
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
                                        <th>Email</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($role['users'] ?? [] as $user)
                                    <tr>
                                        <td>{{ $user['id'] }}</td>
                                        <td>{{ $user['name'] }}</td>
                                        <td>{{ $user['email'] }}</td>
                                        <td>
                                            @if($user['is_active'])
                                                <span class="badge bg-success">Activo</span>
                                            @else
                                                <span class="badge bg-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('usuarios.show', $user['id']) }}" 
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No hay usuarios con este rol</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Permisos del rol -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-shield-alt"></i> Permisos del Rol
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="border p-3 rounded">
                            <div class="row">
                                @forelse($role['permissions'] ?? [] as $permiso)
                                <div class="col-md-4 mb-2">
                                    <span class="badge bg-primary p-2">
                                        <i class="fas fa-check-circle"></i> {{ $permiso['display_name'] }}
                                    </span>
                                </div>
                                @empty
                                <div class="col-12">
                                    <p class="text-muted mb-0">Este rol no tiene permisos asignados</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Estadísticas -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="border p-3 rounded text-center">
                            <h3 class="text-primary">{{ count($role['users'] ?? []) }}</h3>
                            <small class="text-muted">Usuarios asignados</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border p-3 rounded text-center">
                            <h3 class="text-success">{{ count($role['permissions'] ?? []) }}</h3>
                            <small class="text-muted">Permisos asignados</small>
                        </div>
                    </div>
                </div>
                
                <!-- Fechas de registro -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <hr>
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> Creado: {{ $role['created_at'] ?? 'N/A' }} | 
                            Última actualización: {{ $role['updated_at'] ?? 'N/A' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Asignar Permisos -->
<div class="modal fade" id="permisosModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="permisosForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Asignar Permisos al Rol: {{ $role['display_name'] }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="modalSelectAll">
                            Seleccionar Todos
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="modalDeselectAll">
                            Deseleccionar Todos
                        </button>
                    </div>
                    
                    <div class="row">
                        @foreach($permisos ?? [] as $permiso)
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input modal-permiso-checkbox" 
                                       name="permission_ids[]" 
                                       value="{{ $permiso['id'] }}"
                                       id="permiso_{{ $permiso['id'] }}"
                                       {{ in_array($permiso['id'], collect($role['permissions'] ?? [])->pluck('id')->toArray()) ? 'checked' : '' }}>
                                <label class="form-check-label" for="permiso_{{ $permiso['id'] }}">
                                    {{ $permiso['display_name'] }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Permisos</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Botón asignar permisos
    $('.btn-permisos').click(function() {
        $('#permisosModal').modal('show');
    });
    
    // Seleccionar/Deseleccionar todos los permisos en el modal
    $('#modalSelectAll').click(function() {
        $('.modal-permiso-checkbox').prop('checked', true);
    });
    
    $('#modalDeselectAll').click(function() {
        $('.modal-permiso-checkbox').prop('checked', false);
    });
    
    // Enviar formulario de permisos
    $('#permisosForm').submit(function(e) {
        e.preventDefault();
        
        var form = $(this);
        var url = '{{ route("roles.asignar-permisos", $role["id"]) }}';
        var data = form.serialize();
        
        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            success: function(response) {
                $('#permisosModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Permisos asignados correctamente',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    location.reload();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Error al asignar permisos'
                });
            }
        });
    });
});
</script>
@endpush