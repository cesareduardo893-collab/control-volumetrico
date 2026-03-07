@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Listado de Usuarios</h6>
                <a href="{{ route('usuarios.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-user-plus"></i> Nuevo Usuario
                </a>
            </div>
            <div class="card-body">
                <!-- Filtros -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm" id="filterName" placeholder="Nombre">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm" id="filterEmail" placeholder="Email">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterRole">
                            <option value="">Todos los roles</option>
                            @foreach($roles ?? [] as $role)
                                <option value="{{ $role['id'] }}">{{ $role['display_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterActive">
                            <option value="">Todos</option>
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary btn-sm w-100" id="btnFilter">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="usersTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Último Acceso</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users['data'] ?? [] as $user)
                            <tr>
                                <td>{{ $user['id'] }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2" style="width: 32px; height: 32px; background-color: #e9ecef; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            <span class="text-primary fw-bold">{{ substr($user['name'], 0, 1) }}</span>
                                        </div>
                                        {{ $user['name'] }}
                                    </div>
                                </td>
                                <td>{{ $user['email'] }}</td>
                                <td>
                                    @foreach($user['roles'] ?? [] as $role)
                                        <span class="badge bg-info">{{ $role['display_name'] }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $user['last_login'] ?? 'Nunca' }}</td>
                                <td>
                                    @if($user['is_active'])
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('usuarios.show', $user['id']) }}" 
                                           class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('usuarios.edit', $user['id']) }}" 
                                           class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($user['id'] != session('user.id'))
                                        <button type="button" 
                                                class="btn btn-danger btn-sm btn-delete" 
                                                data-id="{{ $user['id'] }}"
                                                data-name="{{ $user['name'] }}"
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
                @if(isset($users['meta']))
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p>Mostrando {{ $users['meta']['from'] ?? 0 }} a {{ $users['meta']['to'] ?? 0 }} de {{ $users['meta']['total'] ?? 0 }} registros</p>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end">
                                @foreach($users['meta']['links'] ?? [] as $link)
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

<!-- Formulario de eliminación -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('styles')
<style>
.avatar-circle {
    width: 32px;
    height: 32px;
    background-color: #e9ecef;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.avatar-circle span {
    font-size: 14px;
    font-weight: bold;
    color: #0d6efd;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializar DataTable
    var table = $('#usersTable').DataTable({
        pageLength: {{ $users['meta']['per_page'] ?? 10 }},
        order: [[0, 'desc']],
        searching: false,
        paging: false,
        info: false
    });

    // Filtros
    $('#btnFilter').click(function() {
        var params = new URLSearchParams();
        
        if ($('#filterName').val()) params.set('name', $('#filterName').val());
        if ($('#filterEmail').val()) params.set('email', $('#filterEmail').val());
        if ($('#filterRole').val()) params.set('role', $('#filterRole').val());
        if ($('#filterActive').val()) params.set('active', $('#filterActive').val());
        
        window.location.href = window.location.pathname + '?' + params.toString();
    });

    // Botones de eliminar
    $('.btn-delete').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: `¿Deseas eliminar el usuario "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                var form = $('#deleteForm');
                form.attr('action', '{{ url("usuarios") }}/' + id);
                form.submit();
            }
        });
    });

    // Cargar valores de filtros desde URL
    var urlParams = new URLSearchParams(window.location.search);
    $('#filterName').val(urlParams.get('name') || '');
    $('#filterEmail').val(urlParams.get('email') || '');
    $('#filterRole').val(urlParams.get('role') || '');
    $('#filterActive').val(urlParams.get('active') || '');
});
</script>
@endpush