@extends('layouts.app')

@section('title', 'Pedimentos')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Listado de Pedimentos</h6>
                <a href="{{ route('pedimentos.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Pedimento
                </a>
            </div>
            <div class="card-body">
                <!-- Filtros -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm" id="filterNumero" placeholder="Número de Pedimento">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterAduana">
                            <option value="">Todas las aduanas</option>
                            @foreach($aduanas ?? [] as $aduana)
                                <option value="{{ $aduana }}">{{ $aduana }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm" id="filterPatente" placeholder="Patente">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterEstado">
                            <option value="">Todos los estados</option>
                            <option value="activo">Activo</option>
                            <option value="liquidado">Liquidado</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm" id="filterContribuyente" placeholder="Contribuyente">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm datepicker" id="filterFechaInicio" placeholder="Fecha Inicio">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm datepicker" id="filterFechaFin" placeholder="Fecha Fin">
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-sm" id="btnFilter">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <button class="btn btn-secondary btn-sm" id="btnClear">
                            <i class="fas fa-eraser"></i> Limpiar
                        </button>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="pedimentosTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Número</th>
                                <th>Aduana</th>
                                <th>Patente</th>
                                <th>Ejercicio</th>
                                <th>Fecha Importación</th>
                                <th>Contribuyente</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedimentos['data'] ?? [] as $pedimento)
                            <tr>
                                <td>{{ $pedimento['id'] }}</td>
                                <td>{{ $pedimento['numero_pedimento'] }}</td>
                                <td>{{ $pedimento['aduana'] }}</td>
                                <td>{{ $pedimento['patente'] }}</td>
                                <td>{{ $pedimento['ejercicio'] }}</td>
                                <td>{{ $pedimento['fecha_importacion'] }}</td>
                                <td>{{ $pedimento['contribuyente']['razon_social'] ?? 'N/A' }}</td>
                                <td>{{ $pedimento['producto']['nombre'] ?? 'N/A' }}</td>
                                <td class="text-end">{{ number_format($pedimento['cantidad_importada'], 2) }} L</td>
                                <td>
                                    @if($pedimento['estado'] == 'activo')
                                        <span class="badge bg-success">Activo</span>
                                    @elseif($pedimento['estado'] == 'liquidado')
                                        <span class="badge bg-info">Liquidado</span>
                                    @else
                                        <span class="badge bg-danger">Cancelado</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('pedimentos.show', $pedimento['id']) }}" 
                                           class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($pedimento['estado'] == 'activo')
                                        <a href="{{ route('pedimentos.edit', $pedimento['id']) }}" 
                                           class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                        <button type="button" 
                                                class="btn btn-secondary btn-sm btn-asociar" 
                                                data-id="{{ $pedimento['id'] }}"
                                                title="Asociar a Existencia">
                                            <i class="fas fa-link"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if(isset($pedimentos['meta']))
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p>Mostrando {{ $pedimentos['meta']['from'] ?? 0 }} a {{ $pedimentos['meta']['to'] ?? 0 }} de {{ $pedimentos['meta']['total'] ?? 0 }} registros</p>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end">
                                @foreach($pedimentos['meta']['links'] ?? [] as $link)
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

<!-- Modal asociar a existencia -->
<div class="modal fade" id="asociarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="asociarForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Asociar a Existencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="existencia_id" class="form-label">Seleccionar Existencia</label>
                        <select class="form-select select2" id="existencia_id" name="registro_volumetrico_id" required>
                            <option value="">Buscar existencia...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad a aplicar (L)</label>
                        <input type="number" class="form-control" id="cantidad" min="0" step="0.001" required>
                        <small class="text-muted">Cantidad disponible: <span id="cantidad_disponible">0</span> L</small>
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
    var table = $('#pedimentosTable').DataTable({
        pageLength: {{ $pedimentos['meta']['per_page'] ?? 10 }},
        order: [[5, 'desc']],
        searching: false,
        paging: false,
        info: false
    });

    // Filtros
    $('#btnFilter').click(function() {
        var params = new URLSearchParams();
        
        if ($('#filterNumero').val()) params.set('numero_pedimento', $('#filterNumero').val());
        if ($('#filterAduana').val()) params.set('aduana', $('#filterAduana').val());
        if ($('#filterPatente').val()) params.set('patente', $('#filterPatente').val());
        if ($('#filterEstado').val()) params.set('estado', $('#filterEstado').val());
        if ($('#filterContribuyente').val()) params.set('contribuyente', $('#filterContribuyente').val());
        if ($('#filterFechaInicio').val()) params.set('fecha_inicio', $('#filterFechaInicio').val());
        if ($('#filterFechaFin').val()) params.set('fecha_fin', $('#filterFechaFin').val());
        
        window.location.href = window.location.pathname + '?' + params.toString();
    });

    // Limpiar filtros
    $('#btnClear').click(function() {
        window.location.href = window.location.pathname;
    });

    // Botón asociar
    $('.btn-asociar').click(function() {
        var id = $(this).data('id');
        var form = $('#asociarForm');
        form.attr('action', '{{ url("pedimentos") }}/' + id + '/asociar-registro');
        
        // Cargar existencias disponibles
        $.get('/api/existencias/disponibles', function(data) {
            var select = $('#existencia_id');
            select.empty().append('<option value="">Seleccione una existencia...</option>');
            
            $.each(data, function(key, existencia) {
                select.append('<option value="' + existencia.id + '" data-cantidad="' + existencia.volumen_neto + '">' + 
                    existencia.fecha_medicion + ' - ' + existencia.tanque.nombre + ' (' + existencia.volumen_neto + ' L)</option>');
            });
        });
        
        $('#asociarModal').modal('show');
    });

    // Actualizar cantidad disponible al seleccionar existencia
    $('#existencia_id').change(function() {
        var selected = $(this).find('option:selected');
        var cantidad = selected.data('cantidad') || 0;
        $('#cantidad_disponible').text(cantidad.toFixed(2));
        $('#cantidad').attr('max', cantidad);
    });

    // Validar cantidad antes de enviar
    $('#asociarForm').submit(function(e) {
        var cantidad = parseFloat($('#cantidad').val());
        var disponible = parseFloat($('#cantidad_disponible').text());
        
        if (cantidad > disponible) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La cantidad no puede ser mayor a la disponible'
            });
        }
    });

    // Cargar valores de filtros desde URL
    var urlParams = new URLSearchParams(window.location.search);
    $('#filterNumero').val(urlParams.get('numero_pedimento') || '');
    $('#filterAduana').val(urlParams.get('aduana') || '');
    $('#filterPatente').val(urlParams.get('patente') || '');
    $('#filterEstado').val(urlParams.get('estado') || '');
    $('#filterContribuyente').val(urlParams.get('contribuyente') || '');
    $('#filterFechaInicio').val(urlParams.get('fecha_inicio') || '');
    $('#filterFechaFin').val(urlParams.get('fecha_fin') || '');
});
</script>
@endpush