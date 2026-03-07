@extends('layouts.app')

@section('title', 'Roles')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Listado de Roles</h6>
                <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Rol
                </a>
            </div>
            <div class="card-body">
                <!-- Tabla -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="rolesTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Nombre para mostrar</th>
                                <th>Descripción</th>
                                <th>Usuarios</th>
                                <th>Permisos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles['data'] ?? [] as $role)
                            <tr>
                                <td>{{ $role['id'] }}</td>
                                <td>{{ $role['name'] }}</td>
                                <td>{{ $role['display_name'] }}</td>
                                <td>{{ $role['description'] ?? 'Sin descripción' }}</td>
                                <td class="text-center">{{ $role['users_count'] ?? 0 }}</td>
                                <td class="text-center">{{ count($role['permissions'] ?? []) }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('roles.show', $role['id']) }}" 
                                           class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('roles.edit', $role['id']) }}" 
                                           class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-success btn-sm btn-permisos" 
                                                data-id="{{ $role['id'] }}"
                                                data-name="{{ $role['display_name'] }}"
                                                title="Asignar Permisos">
                                            <i class="fas fa-shield-alt"></i>
                                        </button>
                                        @if(($role['users_count'] ?? 0) == 0)
                                        <button type="button" 
                                                class="btn btn-danger btn-sm btn-delete" 
                                                data-id="{{ $role['id'] }}"
                                                data-name="{{ $role['display_name'] }}"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if(isset($roles['meta']))
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p>Mostrando {{ $roles['meta']['from'] ?? 0 }} a {{ $roles['meta']['to'] ?? 0 }} de {{ $roles['meta']['total'] ?? 0 }} registros</p>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end">
                                @foreach($roles['meta']['links'] ?? [] as $link)
                                    <li class="page-item {{ $link['active'] ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $link['url'] }}" {!! !$link['url'] ? 'disabled' : '' !!}>
                                            {!! $link['label'] !!}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </nav>
                    </div>
                </div>
                @endif
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
                    <h5 class="modal-title">Asignar Permisos al Rol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @foreach($permisos ?? [] as $permiso)
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input permiso-checkbox" 
                                       name="permission_ids[]" 
                                       value="{{ $permiso['id'] }}"
                                       id="permiso_{{ $permiso['id'] }}">
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

<!-- Formulario de eliminación -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializar DataTable
    var table = $('#rolesTable').DataTable({
        pageLength: {{ $roles['meta']['per_page'] ?? 10 }},
        order: [[0, 'desc']],
        searching: false,
        paging: false,
        info: false
    });

    // Botón asignar permisos
    $('.btn-permisos').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var form = $('#permisosForm');
        
        form.attr('action', '{{ url("roles") }}/' + id + '/asignar-permisos');
        $('#permisosModal .modal-title').text('Asignar Permisos al Rol: ' + name);
        
        // Cargar permisos actuales
        $.get('/roles/' + id + '/permisos', function(data) {
            $('.permiso-checkbox').prop('checked', false);
            
            $.each(data, function(index, permisoId) {
                $('#permiso_' + permisoId).prop('checked', true);
            });
        });
        
        $('#permisosModal').modal('show');
    });

    // Botones de eliminar
    $('.btn-delete').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: `¿Deseas eliminar el rol "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                var form = $('#deleteForm');
                form.attr('action', '{{ url("roles") }}/' + id);
                form.submit();
            }
        });
    });
});
</script>
@endpush