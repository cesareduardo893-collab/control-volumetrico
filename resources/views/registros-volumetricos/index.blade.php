@extends('layouts.app')

@section('title', 'Registros Volumétricos')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Listado de Registros Volumétricos</h6>
                <a href="{{ route('registros-volumetricos.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Registro
                </a>
            </div>
            <div class="card-body">
                <!-- Filtros -->
                <div class="row mb-3">
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterInstalacion">
                            <option value="">Todas las instalaciones</option>
                            @foreach($instalaciones ?? [] as $instalacion)
                                <option value="{{ $instalacion['id'] }}">{{ $instalacion['nombre'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterTanque">
                            <option value="">Todos los tanques</option>
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
                        <select class="form-select form-select-sm" id="filterTipoMovimiento">
                            <option value="">Todos los tipos</option>
                            <option value="entrada">Entrada</option>
                            <option value="salida">Salida</option>
                            <option value="trasiego">Trasiego</option>
                            <option value="ajuste">Ajuste</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm datepicker" id="filterFechaInicio" placeholder="Fecha Inicio">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm datepicker" id="filterFechaFin" placeholder="Fecha Fin">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterEstado">
                            <option value="">Todos los estados</option>
                            <option value="registrado">Registrado</option>
                            <option value="validado">Validado</option>
                            <option value="anulado">Anulado</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <button class="btn btn-primary btn-sm" id="btnFilter">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <button class="btn btn-secondary btn-sm" id="btnClear">
                            <i class="fas fa-eraser"></i> Limpiar
                        </button>
                        <a href="{{ route('registros-volumetricos.exportar') }}" class="btn btn-success btn-sm" id="btnExport">
                            <i class="fas fa-file-excel"></i> Exportar
                        </a>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="registrosTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Instalación</th>
                                <th>Tanque</th>
                                <th>Producto</th>
                                <th>Tipo</th>
                                <th>Volumen Neto</th>
                                <th>Temperatura</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registros['data'] ?? [] as $registro)
                            <tr>
                                <td>{{ $registro['id'] }}</td>
                                <td>{{ $registro['fecha_movimiento'] }}</td>
                                <td>{{ $registro['hora_movimiento'] }}</td>
                                <td>{{ $registro['instalacion']['nombre'] ?? 'N/A' }}</td>
                                <td>{{ $registro['tanque']['nombre'] ?? 'N/A' }}</td>
                                <td>{{ $registro['producto']['nombre'] ?? 'N/A' }}</td>
                                <td>
                                    @switch($registro['tipo_movimiento'])
                                        @case('entrada')
                                            <span class="badge bg-success">Entrada</span>
                                            @break
                                        @case('salida')
                                            <span class="badge bg-danger">Salida</span>
                                            @break
                                        @case('trasiego')
                                            <span class="badge bg-info">Trasiego</span>
                                            @break
                                        @case('ajuste')
                                            <span class="badge bg-warning">Ajuste</span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="text-end">{{ number_format($registro['volumen_neto'], 2) }} L</td>
                                <td class="text-end">{{ $registro['temperatura'] }} °C</td>
                                <td>
                                    @if($registro['estado'] == 'validado')
                                        <span class="badge bg-success">Validado</span>
                                    @elseif($registro['estado'] == 'registrado')
                                        <span class="badge bg-warning">Registrado</span>
                                    @else
                                        <span class="badge bg-danger">Anulado</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('registros-volumetricos.show', $registro['id']) }}" 
                                           class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($registro['estado'] == 'registrado')
                                        <a href="{{ route('registros-volumetricos.edit', $registro['id']) }}" 
                                           class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-success btn-sm btn-validar" 
                                                data-id="{{ $registro['id'] }}"
                                                title="Validar">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        @endif
                                        @if($registro['estado'] != 'anulado')
                                        <button type="button" 
                                                class="btn btn-danger btn-sm btn-anular" 
                                                data-id="{{ $registro['id'] }}"
                                                title="Anular">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                        @endif
                                        <button type="button" 
                                                class="btn btn-secondary btn-sm btn-pdf" 
                                                data-id="{{ $registro['id'] }}"
                                                title="PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if(isset($registros['meta']))
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p>Mostrando {{ $registros['meta']['from'] ?? 0 }} a {{ $registros['meta']['to'] ?? 0 }} de {{ $registros['meta']['total'] ?? 0 }} registros</p>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end">
                                @foreach($registros['meta']['links'] ?? [] as $link)
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

<!-- Modal de validación -->
<div class="modal fade" id="validarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="validarForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Validar Registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de validar este registro volumétrico?</p>
                    <p class="text-muted">Una vez validado, no podrá ser modificado.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Validar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de anulación -->
<div class="modal fade" id="anularModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="anularForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Anular Registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="motivo_anulacion" class="form-label">Motivo de anulación <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="motivo_anulacion" name="motivo_anulacion" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Anular</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializar DataTable
    var table = $('#registrosTable').DataTable({
        pageLength: {{ $registros['meta']['per_page'] ?? 10 }},
        order: [[1, 'desc'], [2, 'desc']],
        searching: false,
        paging: false,
        info: false
    });

    // Cargar tanques por instalación
    $('#filterInstalacion').change(function() {
        var instalacionId = $(this).val();
        var tanqueSelect = $('#filterTanque');
        
        tanqueSelect.empty().append('<option value="">Todos los tanques</option>');
        
        if (instalacionId) {
            $.get('/api/tanques/por-instalacion/' + instalacionId, function(data) {
                $.each(data, function(key, tanque) {
                    tanqueSelect.append('<option value="' + tanque.id + '">' + tanque.nombre + '</option>');
                });
            });
        }
    });

    // Filtros
    $('#btnFilter').click(function() {
        var params = new URLSearchParams();
        
        if ($('#filterInstalacion').val()) params.set('instalacion_id', $('#filterInstalacion').val());
        if ($('#filterTanque').val()) params.set('tanque_id', $('#filterTanque').val());
        if ($('#filterProducto').val()) params.set('producto_id', $('#filterProducto').val());
        if ($('#filterTipoMovimiento').val()) params.set('tipo_movimiento', $('#filterTipoMovimiento').val());
        if ($('#filterFechaInicio').val()) params.set('fecha_inicio', $('#filterFechaInicio').val());
        if ($('#filterFechaFin').val()) params.set('fecha_fin', $('#filterFechaFin').val());
        if ($('#filterEstado').val()) params.set('estado', $('#filterEstado').val());
        
        window.location.href = window.location.pathname + '?' + params.toString();
    });

    // Limpiar filtros
    $('#btnClear').click(function() {
        window.location.href = window.location.pathname;
    });

    // Botones de validar
    $('.btn-validar').click(function() {
        var id = $(this).data('id');
        var form = $('#validarForm');
        form.attr('action', '{{ url("registros-volumetricos") }}/' + id + '/validar');
        $('#validarModal').modal('show');
    });

    // Botones de anular
    $('.btn-anular').click(function() {
        var id = $(this).data('id');
        var form = $('#anularForm');
        form.attr('action', '{{ url("registros-volumetricos") }}/' + id + '/cancelar');
        $('#anularModal').modal('show');
    });

    // Botones de PDF
    $('.btn-pdf').click(function() {
        var id = $(this).data('id');
        window.open('{{ url("registros-volumetricos") }}/' + id + '/pdf', '_blank');
    });

    // Cargar valores de filtros desde URL
    var urlParams = new URLSearchParams(window.location.search);
    $('#filterInstalacion').val(urlParams.get('instalacion_id') || '');
    $('#filterProducto').val(urlParams.get('producto_id') || '');
    $('#filterTipoMovimiento').val(urlParams.get('tipo_movimiento') || '');
    $('#filterFechaInicio').val(urlParams.get('fecha_inicio') || '');
    $('#filterFechaFin').val(urlParams.get('fecha_fin') || '');
    $('#filterEstado').val(urlParams.get('estado') || '');
    
    // Cargar tanques si hay instalación seleccionada
    if ($('#filterInstalacion').val()) {
        $('#filterInstalacion').trigger('change');
        setTimeout(function() {
            $('#filterTanque').val(urlParams.get('tanque_id') || '');
        }, 500);
    }
});
</script>
@endpush