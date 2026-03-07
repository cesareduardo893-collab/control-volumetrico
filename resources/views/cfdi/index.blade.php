@extends('layouts.app')

@section('title', 'CFDI')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Listado de CFDI</h6>
                <a href="{{ route('cfdi.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nuevo CFDI
                </a>
            </div>
            <div class="card-body">
                <!-- Filtros -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm" id="filterUUID" placeholder="UUID">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm" id="filterRFCEmisor" placeholder="RFC Emisor">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm" id="filterRFCReceptor" placeholder="RFC Receptor">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterTipo">
                            <option value="">Todos los tipos</option>
                            <option value="ingreso">Ingreso</option>
                            <option value="egreso">Egreso</option>
                            <option value="traslado">Traslado</option>
                            <option value="pago">Pago</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select form-select-sm" id="filterEstado">
                            <option value="">Todos los estados</option>
                            <option value="vigente">Vigente</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm datepicker" id="filterFechaInicio" placeholder="Fecha Inicio">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm datepicker" id="filterFechaFin" placeholder="Fecha Fin">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterContribuyente">
                            <option value="">Todos los contribuyentes</option>
                            @foreach($contribuyentes ?? [] as $contribuyente)
                                <option value="{{ $contribuyente['id'] }}">{{ $contribuyente['razon_social'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
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
                    <table class="table table-bordered table-hover" id="cfdiTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>UUID</th>
                                <th>Fecha Emisión</th>
                                <th>RFC Emisor</th>
                                <th>RFC Receptor</th>
                                <th>Tipo</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cfdi['data'] ?? [] as $item)
                            <tr>
                                <td>{{ $item['id'] }}</td>
                                <td>
                                    <small>{{ substr($item['uuid'], 0, 8) }}...{{ substr($item['uuid'], -4) }}</small>
                                </td>
                                <td>{{ $item['fecha_emision'] }}</td>
                                <td>{{ $item['rfc_emisor'] }}</td>
                                <td>{{ $item['rfc_receptor'] }}</td>
                                <td>
                                    @switch($item['tipo_cfdi'])
                                        @case('ingreso')
                                            <span class="badge bg-success">Ingreso</span>
                                            @break
                                        @case('egreso')
                                            <span class="badge bg-danger">Egreso</span>
                                            @break
                                        @case('traslado')
                                            <span class="badge bg-info">Traslado</span>
                                            @break
                                        @case('pago')
                                            <span class="badge bg-warning">Pago</span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="text-end">${{ number_format($item['total'], 2) }}</td>
                                <td>
                                    @if($item['estado'] == 'vigente')
                                        <span class="badge bg-success">Vigente</span>
                                    @else
                                        <span class="badge bg-danger">Cancelado</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('cfdi.show', $item['id']) }}" 
                                           class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('cfdi.edit', $item['id']) }}" 
                                           class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-success btn-sm btn-xml" 
                                                data-id="{{ $item['id'] }}"
                                                title="Ver XML">
                                            <i class="fas fa-file-code"></i>
                                        </button>
                                        @if($item['estado'] == 'vigente')
                                        <button type="button" 
                                                class="btn btn-danger btn-sm btn-cancelar" 
                                                data-id="{{ $item['id'] }}"
                                                title="Cancelar">
                                            <i class="fas fa-ban"></i>
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
                @if(isset($cfdi['meta']))
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p>Mostrando {{ $cfdi['meta']['from'] ?? 0 }} a {{ $cfdi['meta']['to'] ?? 0 }} de {{ $cfdi['meta']['total'] ?? 0 }} registros</p>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end">
                                @foreach($cfdi['meta']['links'] ?? [] as $link)
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

<!-- Modal XML -->
<div class="modal fade" id="xmlModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">XML del CFDI</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <pre id="xmlContent" style="max-height: 400px; overflow: auto; background: #f8f9fa; padding: 15px; border-radius: 5px;"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnDownloadXml">
                    <i class="fas fa-download"></i> Descargar XML
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal PDF -->
<div class="modal fade" id="pdfModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">PDF del CFDI</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <iframe id="pdfFrame" style="width: 100%; height: 500px;" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Modal cancelar -->
<div class="modal fade" id="cancelarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="cancelarForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Cancelar CFDI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de cancelar este CFDI?</p>
                    <p class="text-muted">Una vez cancelado, no podrá ser revertido.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Cancelación</button>
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
    var table = $('#cfdiTable').DataTable({
        pageLength: {{ $cfdi['meta']['per_page'] ?? 10 }},
        order: [[2, 'desc']],
        searching: false,
        paging: false,
        info: false
    });

    // Filtros
    $('#btnFilter').click(function() {
        var params = new URLSearchParams();
        
        if ($('#filterUUID').val()) params.set('uuid', $('#filterUUID').val());
        if ($('#filterRFCEmisor').val()) params.set('rfc_emisor', $('#filterRFCEmisor').val());
        if ($('#filterRFCReceptor').val()) params.set('rfc_receptor', $('#filterRFCReceptor').val());
        if ($('#filterTipo').val()) params.set('tipo_cfdi', $('#filterTipo').val());
        if ($('#filterEstado').val()) params.set('estado', $('#filterEstado').val());
        if ($('#filterFechaInicio').val()) params.set('fecha_inicio', $('#filterFechaInicio').val());
        if ($('#filterFechaFin').val()) params.set('fecha_fin', $('#filterFechaFin').val());
        if ($('#filterContribuyente').val()) params.set('contribuyente_id', $('#filterContribuyente').val());
        
        window.location.href = window.location.pathname + '?' + params.toString();
    });

    // Limpiar filtros
    $('#btnClear').click(function() {
        window.location.href = window.location.pathname;
    });

    // Botón XML
    $('.btn-xml').click(function() {
        var id = $(this).data('id');
        
        $.get('/cfdi/' + id + '/xml', function(data) {
            var xmlContent = data.xml_content;
            // Formatear XML para mejor visualización
            var formattedXml = formatXml(xmlContent);
            $('#xmlContent').text(formattedXml);
            
            $('#btnDownloadXml').off('click').on('click', function() {
                downloadXml(xmlContent, 'cfdi_' + id + '.xml');
            });
            
            $('#xmlModal').modal('show');
        });
    });

    // Botón PDF
    $('.btn-pdf').click(function() {
        var id = $(this).data('id');
        $('#pdfFrame').attr('src', '/cfdi/' + id + '/pdf');
        $('#pdfModal').modal('show');
    });

    // Botón cancelar
    $('.btn-cancelar').click(function() {
        var id = $(this).data('id');
        var form = $('#cancelarForm');
        form.attr('action', '{{ url("cfdi") }}/' + id + '/cancelar');
        $('#cancelarModal').modal('show');
    });

    // Función para formatear XML
    function formatXml(xml) {
        var formatted = '';
        var reg = /(>)(<)(\/*)/g;
        xml = xml.replace(reg, '$1\r\n$2$3');
        var pad = 0;
        jQuery.each(xml.split('\r\n'), function(index, node) {
            var indent = 0;
            if (node.match(/.+<\/\w[^>]*>$/)) {
                indent = 0;
            } else if (node.match(/^<\/\w/)) {
                if (pad != 0) {
                    pad -= 1;
                }
            } else if (node.match(/^<\w[^>]*[^\/]>.*$/)) {
                indent = 1;
            } else {
                indent = 0;
            }

            var padding = '';
            for (var i = 0; i < pad; i++) {
                padding += '  ';
            }

            formatted += padding + node + '\r\n';
            pad += indent;
        });

        return formatted;
    }

    // Función para descargar XML
    function downloadXml(content, filename) {
        var element = document.createElement('a');
        element.setAttribute('href', 'data:text/xml;charset=utf-8,' + encodeURIComponent(content));
        element.setAttribute('download', filename);
        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
    }

    // Cargar valores de filtros desde URL
    var urlParams = new URLSearchParams(window.location.search);
    $('#filterUUID').val(urlParams.get('uuid') || '');
    $('#filterRFCEmisor').val(urlParams.get('rfc_emisor') || '');
    $('#filterRFCReceptor').val(urlParams.get('rfc_receptor') || '');
    $('#filterTipo').val(urlParams.get('tipo_cfdi') || '');
    $('#filterEstado').val(urlParams.get('estado') || '');
    $('#filterFechaInicio').val(urlParams.get('fecha_inicio') || '');
    $('#filterFechaFin').val(urlParams.get('fecha_fin') || '');
    $('#filterContribuyente').val(urlParams.get('contribuyente_id') || '');
});
</script>
@endpush