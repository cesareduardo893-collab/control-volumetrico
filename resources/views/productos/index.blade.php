@extends('layouts.app')

@section('title', 'Productos')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Listado de Productos</h6>
                <a href="{{ route('productos.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Producto
                </a>
            </div>
            <div class="card-body">
                <!-- Filtros -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm" id="filterClave" placeholder="Clave Producto">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control form-control-sm" id="filterNombre" placeholder="Nombre">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select form-select-sm" id="filterTipo">
                            <option value="">Todos los tipos</option>
                            <option value="gasolina">Gasolina</option>
                            <option value="diesel">Diesel</option>
                            <option value="combustoleo">Combustóleo</option>
                            <option value="petroleo">Petróleo</option>
                            <option value="queroseno">Queroseno</option>
                            <option value="otros">Otros</option>
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
                    <table class="table table-bordered table-hover" id="productosTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Clave</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Clave SAT</th>
                                <th>Unidad</th>
                                <th>Densidad Ref.</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productos['data'] ?? [] as $producto)
                            <tr>
                                <td>{{ $producto['id'] }}</td>
                                <td>{{ $producto['clave_producto'] }}</td>
                                <td>{{ $producto['nombre'] }}</td>
                                <td>{{ ucfirst($producto['tipo']) }}</td>
                                <td>{{ $producto['clave_sat'] }}</td>
                                <td>{{ $producto['clave_unidad'] }}</td>
                                <td class="text-end">{{ $producto['densidad_referencia'] }}</td>
                                <td class="text-center">
                                    @if($producto['activo'])
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('productos.show', $producto['id']) }}" 
                                           class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('productos.edit', $producto['id']) }}" 
                                           class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('productos.by-tipo', $producto['tipo']) }}" 
                                           class="btn btn-secondary btn-sm" title="Ver por tipo">
                                            <i class="fas fa-filter"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm btn-delete" 
                                                data-id="{{ $producto['id'] }}"
                                                data-name="{{ $producto['nombre'] }}"
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
                @if(isset($productos['meta']))
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p>Mostrando {{ $productos['meta']['from'] ?? 0 }} a {{ $productos['meta']['to'] ?? 0 }} de {{ $productos['meta']['total'] ?? 0 }} registros</p>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end">
                                @foreach($productos['meta']['links'] ?? [] as $link)
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
    var table = $('#productosTable').DataTable({
        pageLength: {{ $productos['meta']['per_page'] ?? 10 }},
        order: [[0, 'desc']],
        searching: false,
        paging: false,
        info: false
    });

    // Filtros
    $('#btnFilter').click(function() {
        var params = new URLSearchParams(window.location.search);
        
        if ($('#filterClave').val()) params.set('clave_producto', $('#filterClave').val());
        if ($('#filterNombre').val()) params.set('nombre', $('#filterNombre').val());
        if ($('#filterTipo').val()) params.set('tipo', $('#filterTipo').val());
        
        window.location.href = window.location.pathname + '?' + params.toString();
    });

    // Botones de eliminar
    $('.btn-delete').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: `¿Deseas eliminar el producto "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                var form = $('#deleteForm');
                form.attr('action', '{{ url("productos") }}/' + id);
                form.submit();
            }
        });
    });

    // Cargar valores de filtros desde URL
    var urlParams = new URLSearchParams(window.location.search);
    $('#filterClave').val(urlParams.get('clave_producto') || '');
    $('#filterNombre').val(urlParams.get('nombre') || '');
    $('#filterTipo').val(urlParams.get('tipo') || '');
});
</script>
@endpush