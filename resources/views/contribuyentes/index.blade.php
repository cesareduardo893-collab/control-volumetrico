@extends('layouts.app')

@section('title', 'Contribuyentes')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Listado de Contribuyentes</h6>
                <a href="{{ route('contribuyentes.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Contribuyente
                </a>
            </div>
            <div class="card-body">
                <!-- Filtros -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm" id="filterRfc" placeholder="RFC">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm" id="filterRazonSocial" placeholder="Razón Social">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterActivo">
                            <option value="">Todos</option>
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterCaracter">
                            <option value="">Carácter</option>
                            <option value="contratista">Contratista</option>
                            <option value="asignatario">Asignatario</option>
                            <option value="permisionario">Permisionario</option>
                            <option value="usuario">Usuario</option>
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
                    <table class="table table-bordered table-hover" id="contribuyentesTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>RFC</th>
                                <th>Razón Social</th>
                                <th>Carácter</th>
                                <th>Permiso</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Activo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contribuyentes['data'] ?? [] as $contribuyente)
                            <tr>
                                <td>{{ $contribuyente['id'] }}</td>
                                <td>{{ $contribuyente['rfc'] }}</td>
                                <td>{{ $contribuyente['razon_social'] }}</td>
                                <td>{{ ucfirst($contribuyente['caracter_actua']) }}</td>
                                <td>{{ $contribuyente['numero_permiso'] ?? 'N/A' }}</td>
                                <td>{{ $contribuyente['telefono'] ?? 'N/A' }}</td>
                                <td>{{ $contribuyente['email'] ?? 'N/A' }}</td>
                                <td class="text-center">
                                    @if($contribuyente['activo'])
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('contribuyentes.show', $contribuyente['id']) }}" 
                                           class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('contribuyentes.edit', $contribuyente['id']) }}" 
                                           class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('contribuyentes.instalaciones', $contribuyente['id']) }}" 
                                           class="btn btn-secondary btn-sm" title="Instalaciones">
                                            <i class="fas fa-gas-pump"></i>
                                        </a>
                                        <a href="{{ route('contribuyentes.cumplimiento', $contribuyente['id']) }}" 
                                           class="btn btn-success btn-sm" title="Cumplimiento">
                                            <i class="fas fa-check-circle"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm btn-delete" 
                                                data-id="{{ $contribuyente['id'] }}"
                                                data-name="{{ $contribuyente['razon_social'] }}"
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
                @if(isset($contribuyentes['meta']))
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p>Mostrando {{ $contribuyentes['meta']['from'] ?? 0 }} a {{ $contribuyentes['meta']['to'] ?? 0 }} de {{ $contribuyentes['meta']['total'] ?? 0 }} registros</p>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end">
                                @foreach($contribuyentes['meta']['links'] ?? [] as $link)
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
    var table = $('#contribuyentesTable').DataTable({
        pageLength: {{ $contribuyentes['meta']['per_page'] ?? 10 }},
        order: [[0, 'desc']],
        searching: false,
        paging: false,
        info: false,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        }
    });

    // Filtros
    $('#btnFilter').click(function() {
        var params = new URLSearchParams(window.location.search);
        
        if ($('#filterRfc').val()) params.set('rfc', $('#filterRfc').val());
        if ($('#filterRazonSocial').val()) params.set('razon_social', $('#filterRazonSocial').val());
        if ($('#filterActivo').val()) params.set('activo', $('#filterActivo').val());
        if ($('#filterCaracter').val()) params.set('caracter', $('#filterCaracter').val());
        
        window.location.href = window.location.pathname + '?' + params.toString();
    });

    // Botones de eliminar
    $('.btn-delete').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: `¿Deseas eliminar el contribuyente "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                var form = $('#deleteForm');
                form.attr('action', '{{ url("contribuyentes") }}/' + id);
                form.submit();
            }
        });
    });

    // Cargar valores de filtros desde URL
    var urlParams = new URLSearchParams(window.location.search);
    $('#filterRfc').val(urlParams.get('rfc') || '');
    $('#filterRazonSocial').val(urlParams.get('razon_social') || '');
    $('#filterActivo').val(urlParams.get('activo') || '');
    $('#filterCaracter').val(urlParams.get('caracter') || '');
});
</script>
@endpush