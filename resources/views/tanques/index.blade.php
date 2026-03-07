@extends('layouts.app')

@section('title', 'Tanques')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Listado de Tanques</h6>
                <a href="{{ route('tanques.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Tanque
                </a>
            </div>
            <div class="card-body">
                <!-- Filtros -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm" id="filterClave" placeholder="Clave Tanque">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm" id="filterNombre" placeholder="Nombre">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterInstalacion">
                            <option value="">Todas las instalaciones</option>
                            @foreach($instalaciones ?? [] as $instalacion)
                                <option value="{{ $instalacion['id'] }}">{{ $instalacion['nombre'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterProducto">
                            <option value="">Todos los productos</option>
                            @foreach($productos ?? [] as $producto)
                                <option value="{{ $producto['id'] }}">{{ $producto['nombre'] }}</option>
                            @endforeach
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
                    <table class="table table-bordered table-hover" id="tanquesTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Clave</th>
                                <th>Nombre</th>
                                <th>Instalación</th>
                                <th>Producto</th>
                                <th>Capacidad (L)</th>
                                <th>Tipo</th>
                                <th>Forma</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tanques['data'] ?? [] as $tanque)
                            <tr>
                                <td>{{ $tanque['id'] }}</td>
                                <td>{{ $tanque['clave_tanque'] }}</td>
                                <td>{{ $tanque['nombre'] }}</td>
                                <td>{{ $tanque['instalacion']['nombre'] ?? 'N/A' }}</td>
                                <td>{{ $tanque['producto']['nombre'] ?? 'N/A' }}</td>
                                <td class="text-end">{{ number_format($tanque['capacidad'], 2) }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $tanque['tipo_tanque'])) }}</td>
                                <td>{{ ucfirst($tanque['forma']) }}</td>
                                <td class="text-center">
                                    @if($tanque['activo'])
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('tanques.show', $tanque['id']) }}" 
                                           class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('tanques.edit', $tanque['id']) }}" 
                                           class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('tanques.existencias', $tanque['id']) }}" 
                                           class="btn btn-secondary btn-sm" title="Existencias">
                                            <i class="fas fa-boxes"></i>
                                        </a>
                                        <a href="{{ route('tanques.ultima-existencia', $tanque['id']) }}" 
                                           class="btn btn-success btn-sm" title="Última Existencia">
                                            <i class="fas fa-chart-line"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm btn-delete" 
                                                data-id="{{ $tanque['id'] }}"
                                                data-name="{{ $tanque['nombre'] }}"
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
                @if(isset($tanques['meta']))
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p>Mostrando {{ $tanques['meta']['from'] ?? 0 }} a {{ $tanques['meta']['to'] ?? 0 }} de {{ $tanques['meta']['total'] ?? 0 }} registros</p>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end">
                                @foreach($tanques['meta']['links'] ?? [] as $link)
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
    var table = $('#tanquesTable').DataTable({
        pageLength: {{ $tanques['meta']['per_page'] ?? 10 }},
        order: [[0, 'desc']],
        searching: false,
        paging: false,
        info: false
    });

    // Filtros
    $('#btnFilter').click(function() {
        var params = new URLSearchParams(window.location.search);
        
        if ($('#filterClave').val()) params.set('clave_tanque', $('#filterClave').val());
        if ($('#filterNombre').val()) params.set('nombre', $('#filterNombre').val());
        if ($('#filterInstalacion').val()) params.set('instalacion_id', $('#filterInstalacion').val());
        if ($('#filterProducto').val()) params.set('producto_id', $('#filterProducto').val());
        
        window.location.href = window.location.pathname + '?' + params.toString();
    });

    // Botones de eliminar
    $('.btn-delete').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: `¿Deseas eliminar el tanque "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                var form = $('#deleteForm');
                form.attr('action', '{{ url("tanques") }}/' + id);
                form.submit();
            }
        });
    });

    // Cargar valores de filtros desde URL
    var urlParams = new URLSearchParams(window.location.search);
    $('#filterClave').val(urlParams.get('clave_tanque') || '');
    $('#filterNombre').val(urlParams.get('nombre') || '');
    $('#filterInstalacion').val(urlParams.get('instalacion_id') || '');
    $('#filterProducto').val(urlParams.get('producto_id') || '');
});
</script>
@endpush