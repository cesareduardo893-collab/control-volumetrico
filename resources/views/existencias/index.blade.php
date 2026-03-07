@extends('layouts.app')

@section('title', 'Existencias')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Listado de Existencias</h6>
                <a href="{{ route('existencias.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nueva Existencia
                </a>
            </div>
            <div class="card-body">
                <!-- Filtros -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-select form-select-sm" id="filterInstalacion">
                            <option value="">Todas las instalaciones</option>
                            @foreach($instalaciones ?? [] as $instalacion)
                                <option value="{{ $instalacion['id'] }}">{{ $instalacion['nombre'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
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
                        <input type="text" class="form-control form-control-sm datepicker" id="filterFecha" placeholder="Fecha">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterEstado">
                            <option value="">Todos los estados</option>
                            <option value="valida">Válida</option>
                            <option value="invalida">Inválida</option>
                            <option value="pendiente">Pendiente</option>
                        </select>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterMetodo">
                            <option value="">Todos los métodos</option>
                            <option value="manual">Manual</option>
                            <option value="automatica">Automática</option>
                        </select>
                    </div>
                    <div class="col-md-10">
                        <button class="btn btn-primary btn-sm" id="btnFilter">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <button class="btn btn-secondary btn-sm" id="btnClear">
                            <i class="fas fa-eraser"></i> Limpiar
                        </button>
                        <a href="{{ route('existencias.inventario-diario') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-chart-line"></i> Inventario Diario
                        </a>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="existenciasTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Instalación</th>
                                <th>Tanque</th>
                                <th>Producto</th>
                                <th>Volumen Neto</th>
                                <th>Temperatura</th>
                                <th>Método</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($existencias['data'] ?? [] as $existencia)
                            <tr>
                                <td>{{ $existencia['id'] }}</td>
                                <td>{{ $existencia['fecha_medicion'] }}</td>
                                <td>{{ $existencia['hora_medicion'] }}</td>
                                <td>{{ $existencia['tanque']['instalacion']['nombre'] ?? 'N/A' }}</td>
                                <td>{{ $existencia['tanque']['nombre'] ?? 'N/A' }}</td>
                                <td>{{ $existencia['producto']['nombre'] ?? 'N/A' }}</td>
                                <td class="text-end">{{ number_format($existencia['volumen_neto'], 2) }} L</td>
                                <td class="text-end">{{ $existencia['temperatura'] }} °C</td>
                                <td>{{ ucfirst($existencia['metodo_medicion']) }}</td>
                                <td>
                                    @if($existencia['estado'] == 'valida')
                                        <span class="badge bg-success">Válida</span>
                                    @elseif($existencia['estado'] == 'invalida')
                                        <span class="badge bg-danger">Inválida</span>
                                    @else
                                        <span class="badge bg-warning">Pendiente</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('existencias.show', $existencia['id']) }}" 
                                           class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($existencia['estado'] == 'pendiente')
                                        <a href="{{ route('existencias.edit', $existencia['id']) }}" 
                                           class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-success btn-sm btn-validar" 
                                                data-id="{{ $existencia['id'] }}"
                                                title="Validar">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        @endif
                                        @if($existencia['estado'] == 'valida' && !$existencia['cfdi_id'])
                                        <button type="button" 
                                                class="btn btn-primary btn-sm btn-asociar-cfdi" 
                                                data-id="{{ $existencia['id'] }}"
                                                title="Asociar CFDI">
                                            <i class="fas fa-file-invoice"></i>
                                        </button>
                                        @endif
                                        @if($existencia['estado'] == 'valida' && !$existencia['pedimento_id'])
                                        <button type="button" 
                                                class="btn btn-secondary btn-sm btn-asociar-pedimento" 
                                                data-id="{{ $existencia['id'] }}"
                                                title="Asociar Pedimento">
                                            <i class="fas fa-file-alt"></i>
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
                @if(isset($existencias['meta']))
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p>Mostrando {{ $existencias['meta']['from'] ?? 0 }} a {{ $existencias['meta']['to'] ?? 0 }} de {{ $existencias['meta']['total'] ?? 0 }} registros</p>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end">
                                @foreach($existencias['meta']['links'] ?? [] as $link)
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
                    <h5 class="modal-title">Validar Existencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de validar esta existencia?</p>
                    <p class="text-muted">Una vez validada, no podrá ser modificada.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Validar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal asociar CFDI -->
<div class="modal fade" id="asociarCfdiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="asociarCfdiForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Asociar CFDI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="cfdi_id" class="form-label">Seleccionar CFDI</label>
                        <select class="form-select select2" id="cfdi_id" name="cfdi_id" required>
                            <option value="">Buscar CFDI...</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Asociar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal asociar Pedimento -->
<div class="modal fade" id="asociarPedimentoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="asociarPedimentoForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Asociar Pedimento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="pedimento_id" class="form-label">Seleccionar Pedimento</label>
                        <select class="form-select select2" id="pedimento_id" name="pedimento_id" required>
                            <option value="">Buscar pedimento...</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Asociar</button>
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
    var table = $('#existenciasTable').DataTable({
        pageLength: {{ $existencias['meta']['per_page'] ?? 10 }},
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
        if ($('#filterFecha').val()) params.set('fecha_medicion', $('#filterFecha').val());
        if ($('#filterEstado').val()) params.set('estado', $('#filterEstado').val());
        if ($('#filterMetodo').val()) params.set('metodo_medicion', $('#filterMetodo').val());
        
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
        form.attr('action', '{{ url("existencias") }}/' + id + '/validar');
        $('#validarModal').modal('show');
    });

    // Botones asociar CFDI
    $('.btn-asociar-cfdi').click(function() {
        var id = $(this).data('id');
        var form = $('#asociarCfdiForm');
        form.attr('action', '{{ url("existencias") }}/' + id + '/asociar-cfdi');
        
        // Cargar CFDI disponibles
        $.get('/api/cfdi/disponibles', function(data) {
            var select = $('#cfdi_id');
            select.empty().append('<option value="">Seleccione un CFDI...</option>');
            
            $.each(data, function(key, cfdi) {
                select.append('<option value="' + cfdi.id + '">' + cfdi.uuid + ' - $' + cfdi.total + '</option>');
            });
        });
        
        $('#asociarCfdiModal').modal('show');
    });

    // Botones asociar Pedimento
    $('.btn-asociar-pedimento').click(function() {
        var id = $(this).data('id');
        var form = $('#asociarPedimentoForm');
        form.attr('action', '{{ url("existencias") }}/' + id + '/asociar-pedimento');
        
        // Cargar pedimentos disponibles
        $.get('/api/pedimentos/disponibles', function(data) {
            var select = $('#pedimento_id');
            select.empty().append('<option value="">Seleccione un pedimento...</option>');
            
            $.each(data, function(key, pedimento) {
                select.append('<option value="' + pedimento.id + '">' + pedimento.numero_pedimento + ' - ' + pedimento.aduana + '</option>');
            });
        });
        
        $('#asociarPedimentoModal').modal('show');
    });

    // Cargar valores de filtros desde URL
    var urlParams = new URLSearchParams(window.location.search);
    $('#filterInstalacion').val(urlParams.get('instalacion_id') || '');
    $('#filterProducto').val(urlParams.get('producto_id') || '');
    $('#filterFecha').val(urlParams.get('fecha_medicion') || '');
    $('#filterEstado').val(urlParams.get('estado') || '');
    $('#filterMetodo').val(urlParams.get('metodo_medicion') || '');
    
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