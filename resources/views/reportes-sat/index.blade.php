@extends('layouts.app')

@section('title', 'Reportes SAT')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Listado de Reportes SAT</h6>
                <a href="{{ route('reportes-sat.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Reporte
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
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterPeriodo">
                            <option value="">Todos los periodos</option>
                            <option value="diario">Diario</option>
                            <option value="semanal">Semanal</option>
                            <option value="quincenal">Quincenal</option>
                            <option value="mensual">Mensual</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterEstado">
                            <option value="">Todos los estados</option>
                            <option value="generado">Generado</option>
                            <option value="firmado">Firmado</option>
                            <option value="enviado">Enviado</option>
                            <option value="recibido">Recibido</option>
                            <option value="error">Error</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm" id="filterAnio" placeholder="Año">
                    </div>
                    <div class="col-md-3">
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
                    <table class="table table-bordered table-hover" id="reportesTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Instalación</th>
                                <th>Periodo</th>
                                <th>Año/Mes</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Registros</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportes['data'] ?? [] as $reporte)
                            <tr>
                                <td>{{ $reporte['id'] }}</td>
                                <td>{{ $reporte['instalacion']['nombre'] ?? 'N/A' }}</td>
                                <td>{{ ucfirst($reporte['periodo']) }}</td>
                                <td>{{ $reporte['anio'] }}-{{ str_pad($reporte['mes'], 2, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $reporte['fecha_inicio'] }}</td>
                                <td>{{ $reporte['fecha_fin'] }}</td>
                                <td class="text-center">
                                    <small>
                                        G: {{ $reporte['registros_generados'] }} | 
                                        V: {{ $reporte['registros_validos'] }} | 
                                        I: {{ $reporte['registros_invalidos'] }}
                                    </small>
                                </td>
                                <td>
                                    @if($reporte['estado'] == 'generado')
                                        <span class="badge bg-secondary">Generado</span>
                                    @elseif($reporte['estado'] == 'firmado')
                                        <span class="badge bg-info">Firmado</span>
                                    @elseif($reporte['estado'] == 'enviado')
                                        <span class="badge bg-primary">Enviado</span>
                                    @elseif($reporte['estado'] == 'recibido')
                                        <span class="badge bg-success">Recibido</span>
                                    @else
                                        <span class="badge bg-danger">Error</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('reportes-sat.show', $reporte['id']) }}" 
                                           class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($reporte['estado'] == 'generado')
                                        <a href="{{ route('reportes-sat.edit', $reporte['id']) }}" 
                                           class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-success btn-sm btn-firmar" 
                                                data-id="{{ $reporte['id'] }}"
                                                title="Firmar">
                                            <i class="fas fa-signature"></i>
                                        </button>
                                        @endif
                                        @if($reporte['estado'] == 'firmado')
                                        <button type="button" 
                                                class="btn btn-primary btn-sm btn-enviar" 
                                                data-id="{{ $reporte['id'] }}"
                                                title="Enviar">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                        @endif
                                        @if($reporte['estado'] == 'enviado' || $reporte['estado'] == 'recibido')
                                        <button type="button" 
                                                class="btn btn-secondary btn-sm btn-acuse" 
                                                data-id="{{ $reporte['id'] }}"
                                                title="Acuse">
                                            <i class="fas fa-file-signature"></i>
                                        </button>
                                        @endif
                                        <button type="button" 
                                                class="btn btn-success btn-sm btn-xml" 
                                                data-id="{{ $reporte['id'] }}"
                                                title="Descargar XML">
                                            <i class="fas fa-file-code"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm btn-pdf" 
                                                data-id="{{ $reporte['id'] }}"
                                                title="Descargar PDF">
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
                @if(isset($reportes['meta']))
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p>Mostrando {{ $reportes['meta']['from'] ?? 0 }} a {{ $reportes['meta']['to'] ?? 0 }} de {{ $reportes['meta']['total'] ?? 0 }} registros</p>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end">
                                @foreach($reportes['meta']['links'] ?? [] as $link)
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

<!-- Modal firmar -->
<div class="modal fade" id="firmarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="firmarForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Firmar Reporte SAT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de firmar este reporte?</p>
                    <p class="text-muted">Una vez firmado, podrá ser enviado al SAT.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Firmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal enviar -->
<div class="modal fade" id="enviarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="enviarForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Enviar Reporte SAT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de enviar este reporte al SAT?</p>
                    <p class="text-muted">El reporte será transmitido electrónicamente.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar</button>
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
    var table = $('#reportesTable').DataTable({
        pageLength: {{ $reportes['meta']['per_page'] ?? 10 }},
        order: [[4, 'desc']],
        searching: false,
        paging: false,
        info: false
    });

    // Filtros
    $('#btnFilter').click(function() {
        var params = new URLSearchParams();
        
        if ($('#filterInstalacion').val()) params.set('instalacion_id', $('#filterInstalacion').val());
        if ($('#filterPeriodo').val()) params.set('periodo', $('#filterPeriodo').val());
        if ($('#filterEstado').val()) params.set('estado', $('#filterEstado').val());
        if ($('#filterAnio').val()) params.set('anio', $('#filterAnio').val());
        
        window.location.href = window.location.pathname + '?' + params.toString();
    });

    // Limpiar filtros
    $('#btnClear').click(function() {
        window.location.href = window.location.pathname;
    });

    // Botón firmar
    $('.btn-firmar').click(function() {
        var id = $(this).data('id');
        var form = $('#firmarForm');
        form.attr('action', '{{ url("reportes-sat") }}/' + id + '/firmar');
        $('#firmarModal').modal('show');
    });

    // Botón enviar
    $('.btn-enviar').click(function() {
        var id = $(this).data('id');
        var form = $('#enviarForm');
        form.attr('action', '{{ url("reportes-sat") }}/' + id + '/enviar');
        $('#enviarModal').modal('show');
    });

    // Botón acuse
    $('.btn-acuse').click(function() {
        var id = $(this).data('id');
        window.open('{{ url("reportes-sat") }}/' + id + '/acuse', '_blank');
    });

    // Botón XML
    $('.btn-xml').click(function() {
        var id = $(this).data('id');
        window.open('{{ url("reportes-sat") }}/' + id + '/descargar-xml', '_blank');
    });

    // Botón PDF
    $('.btn-pdf').click(function() {
        var id = $(this).data('id');
        window.open('{{ url("reportes-sat") }}/' + id + '/descargar-pdf', '_blank');
    });

    // Cargar valores de filtros desde URL
    var urlParams = new URLSearchParams(window.location.search);
    $('#filterInstalacion').val(urlParams.get('instalacion_id') || '');
    $('#filterPeriodo').val(urlParams.get('periodo') || '');
    $('#filterEstado').val(urlParams.get('estado') || '');
    $('#filterAnio').val(urlParams.get('anio') || '');
});
</script>
@endpush