@extends('layouts.app')

@section('title', 'Permisos')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Listado de Permisos</h6>
                <a href="{{ route('permissions.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Permiso
                </a>
            </div>
            <div class="card-body">
                <!-- Tabla -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="permissionsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Nombre para mostrar</th>
                                <th>Descripción</th>
                                <th>Roles</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permisos['data'] ?? [] as $permiso)
                            <tr>
                                <td>{{ $permiso['id'] }}</td>
                                <td><code>{{ $permiso['name'] }}</code></td>
                                <td>{{ $permiso['display_name'] }}</td>
                                <td>{{ $permiso['description'] ?? 'Sin descripción' }}</td>
                                <td class="text-center">{{ count($permiso['roles'] ?? []) }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('permissions.show', $permiso['id']) }}" 
                                           class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('permissions.edit', $permiso['id']) }}" 
                                           class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm btn-delete" 
                                                data-id="{{ $permiso['id'] }}"
                                                data-name="{{ $permiso['display_name'] }}"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if(isset($permisos['meta']))
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p>Mostrando {{ $permisos['meta']['from'] ?? 0 }} a {{ $permisos['meta']['to'] ?? 0 }} de {{ $permisos['meta']['total'] ?? 0 }} registros</p>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end">
                                @foreach($permisos['meta']['links'] ?? [] as $link)
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

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializar DataTable
    var table = $('#permissionsTable').DataTable({
        pageLength: {{ $permisos['meta']['per_page'] ?? 10 }},
        order: [[0, 'desc']],
        searching: false,
        paging: false,
        info: false
    });

    // Botones de eliminar
    $('.btn-delete').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: `¿Deseas eliminar el permiso "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                var form = $('#deleteForm');
                form.attr('action', '{{ url("permissions") }}/' + id);
                form.submit();
            }
        });
    });
});
</script>
@endpush