@extends('layouts.app')

@section('title', 'Instalaciones')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Listado de Instalaciones</h6>
                <a href="{{ route('instalaciones.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nueva Instalación
                </a>
            </div>
            <div class="card-body">
                <!-- Filtros -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm" id="filterClave" placeholder="Clave Instalación">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm" id="filterNombre" placeholder="Nombre">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterTipo">
                            <option value="">Todos los tipos</option>
                            <option value="estacion_servicio">Estación de Servicio</option>
                            <option value="almacenamiento">Almacenamiento</option>
                            <option value="transporte">Transporte</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterActivo">
                            <option value="">Todos</option>
                            <option value="1">Activas</option>
                            <option value="0">Inactivas</option>
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
                    <table class="table table-bordered table-hover" id="instalacionesTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Clave</th>
                                <th>Nombre</th>
                                <th>Contribuyente</th>
                                <th>Tipo</th>
                                <th>Domicilio</th>
                                <th>CP</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($instalaciones['data'] ?? [] as $instalacion)
                            <tr>
                                <td>{{ $instalacion['id'] }}</td>
                                <td>{{ $instalacion['clave_instalacion'] }}</td>
                                <td>{{ $instalacion['nombre'] }}</td>
                                <td>{{ $instalacion['contribuyente']['razon_social'] ?? 'N/A' }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $instalacion['tipo_instalacion'])) }}</td>
                                <td>{{ $instalacion['domicilio'] }}</td>
                                <td>{{ $instalacion['codigo_postal'] }}</td>
                                <td class="text-center">
                                    @if($instalacion['activo'])
                                        <span class="badge bg-success">Activa</span>
                                    @else
                                        <span class="badge bg-danger">Inactiva</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('instalaciones.show', $instalacion['id']) }}" 
                                           class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('instalaciones.edit', $instalacion['id']) }}" 
                                           class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('instalaciones.tanques', $instalacion['id']) }}" 
                                           class="btn btn-secondary btn-sm" title="Tanques">
                                            <i class="fas fa-oil-can"></i>
                                        </a>
                                        <a href="{{ route('instalaciones.medidores', $instalacion['id']) }}" 
                                           class="btn btn-primary btn-sm" title="Medidores">
                                            <i class="fas fa-tachometer-alt"></i>
                                        </a>
                                        <a href="{{ route('instalaciones.dispensarios', $instalacion['id']) }}" 
                                           class="btn btn-success btn-sm" title="Dispensarios">
                                            <i class="fas fa-gas-pump"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm btn-delete" 
                                                data-id="{{ $instalacion['id'] }}"
                                                data-name="{{ $instalacion['nombre'] }}"
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
                @if(isset($instalaciones['meta']))
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p>Mostrando {{ $instalaciones['meta']['from'] ?? 0 }} a {{ $instalaciones['meta']['to'] ?? 0 }} de {{ $instalaciones['meta']['total'] ?? 0 }} registros</p>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end">
                                @foreach($instalaciones['meta']['links'] ?? [] as $link)
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
    var table = $('#instalacionesTable').DataTable({
        pageLength: {{ $instalaciones['meta']['per_page'] ?? 10 }},
        order: [[0, 'desc']],
        searching: false,
        paging: false,
        info: false
    });

    // Filtros
    $('#btnFilter').click(function() {
        var params = new URLSearchParams(window.location.search);
        
        if ($('#filterClave').val()) params.set('clave_instalacion', $('#filterClave').val());
        if ($('#filterNombre').val()) params.set('nombre', $('#filterNombre').val());
        if ($('#filterTipo').val()) params.set('tipo_instalacion', $('#filterTipo').val());
        if ($('#filterActivo').val()) params.set('activo', $('#filterActivo').val());
        
        window.location.href = window.location.pathname + '?' + params.toString();
    });

    // Botones de eliminar
    $('.btn-delete').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: `¿Deseas eliminar la instalación "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                var form = $('#deleteForm');
                form.attr('action', '{{ url("instalaciones") }}/' + id);
                form.submit();
            }
        });
    });

    // Cargar valores de filtros desde URL
    var urlParams = new URLSearchParams(window.location.search);
    $('#filterClave').val(urlParams.get('clave_instalacion') || '');
    $('#filterNombre').val(urlParams.get('nombre') || '');
    $('#filterTipo').val(urlParams.get('tipo_instalacion') || '');
    $('#filterActivo').val(urlParams.get('activo') || '');
});
</script>
@endpush