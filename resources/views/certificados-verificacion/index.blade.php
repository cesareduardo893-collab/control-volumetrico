@extends('layouts.app')

@section('title', 'Certificados de Verificación')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Listado de Certificados de Verificación</h6>
                <a href="{{ route('certificados-verificacion.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Certificado
                </a>
            </div>
            <div class="card-body">
                <!-- Filtros -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm" id="filterNumero" placeholder="Número de Certificado">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select form-select-sm" id="filterInstalacion">
                            <option value="">Todas las instalaciones</option>
                            @foreach($instalaciones ?? [] as $instalacion)
                                <option value="{{ $instalacion['id'] }}">{{ $instalacion['nombre'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterTipo">
                            <option value="">Todos los tipos</option>
                            <option value="inicial">Inicial</option>
                            <option value="periodico">Periódico</option>
                            <option value="extraordinario">Extraordinario</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterEstatus">
                            <option value="">Todos los estatus</option>
                            <option value="aprobado">Aprobado</option>
                            <option value="rechazado">Rechazado</option>
                            <option value="pendiente">Pendiente</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterResultado">
                            <option value="">Todos los resultados</option>
                            <option value="aprobado">Aprobado</option>
                            <option value="rechazado">Rechazado</option>
                            <option value="observaciones">Con Observaciones</option>
                        </select>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm datepicker" id="filterFechaInicio" placeholder="Fecha Emisión Inicio">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm datepicker" id="filterFechaFin" placeholder="Fecha Emisión Fin">
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
                    <table class="table table-bordered table-hover" id="certificadosTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Número</th>
                                <th>Instalación</th>
                                <th>Tipo</th>
                                <th>Fecha Emisión</th>
                                <th>Fecha Vigencia</th>
                                <th>Estatus</th>
                                <th>Resultado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($certificados['data'] ?? [] as $certificado)
                            <tr>
                                <td>{{ $certificado['id'] }}</td>
                                <td>{{ $certificado['numero_certificado'] }}</td>
                                <td>{{ $certificado['instalacion']['nombre'] ?? 'N/A' }}</td>
                                <td>
                                    @switch($certificado['tipo_certificado'])
                                        @case('inicial')
                                            Inicial
                                            @break
                                        @case('periodico')
                                            Periódico
                                            @break
                                        @case('extraordinario')
                                            Extraordinario
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $certificado['fecha_emision'] }}</td>
                                <td>{{ $certificado['fecha_vigencia'] }}</td>
                                <td>
                                    @if($certificado['estatus'] == 'aprobado')
                                        <span class="badge bg-success">Aprobado</span>
                                    @elseif($certificado['estatus'] == 'rechazado')
                                        <span class="badge bg-danger">Rechazado</span>
                                    @else
                                        <span class="badge bg-warning">Pendiente</span>
                                    @endif
                                </td>
                                <td>
                                    @if($certificado['resultado'] == 'aprobado')
                                        <span class="badge bg-success">Aprobado</span>
                                    @elseif($certificado['resultado'] == 'rechazado')
                                        <span class="badge bg-danger">Rechazado</span>
                                    @else
                                        <span class="badge bg-info">Con Observaciones</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('certificados-verificacion.show', $certificado['id']) }}" 
                                           class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('certificados-verificacion.edit', $certificado['id']) }}" 
                                           class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-success btn-sm btn-pdf" 
                                                data-id="{{ $certificado['id'] }}"
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
                @if(isset($certificados['meta']))
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p>Mostrando {{ $certificados['meta']['from'] ?? 0 }} a {{ $certificados['meta']['to'] ?? 0 }} de {{ $certificados['meta']['total'] ?? 0 }} registros</p>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end">
                                @foreach($certificados['meta']['links'] ?? [] as $link)
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
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializar DataTable
    var table = $('#certificadosTable').DataTable({
        pageLength: {{ $certificados['meta']['per_page'] ?? 10 }},
        order: [[4, 'desc']],
        searching: false,
        paging: false,
        info: false
    });

    // Filtros
    $('#btnFilter').click(function() {
        var params = new URLSearchParams();
        
        if ($('#filterNumero').val()) params.set('numero_certificado', $('#filterNumero').val());
        if ($('#filterInstalacion').val()) params.set('instalacion_id', $('#filterInstalacion').val());
        if ($('#filterTipo').val()) params.set('tipo_certificado', $('#filterTipo').val());
        if ($('#filterEstatus').val()) params.set('estatus', $('#filterEstatus').val());
        if ($('#filterResultado').val()) params.set('resultado', $('#filterResultado').val());
        if ($('#filterFechaInicio').val()) params.set('fecha_inicio', $('#filterFechaInicio').val());
        if ($('#filterFechaFin').val()) params.set('fecha_fin', $('#filterFechaFin').val());
        
        window.location.href = window.location.pathname + '?' + params.toString();
    });

    // Limpiar filtros
    $('#btnClear').click(function() {
        window.location.href = window.location.pathname;
    });

    // Botón PDF
    $('.btn-pdf').click(function() {
        var id = $(this).data('id');
        window.open('{{ url("certificados-verificacion") }}/' + id + '/pdf', '_blank');
    });

    // Cargar valores de filtros desde URL
    var urlParams = new URLSearchParams(window.location.search);
    $('#filterNumero').val(urlParams.get('numero_certificado') || '');
    $('#filterInstalacion').val(urlParams.get('instalacion_id') || '');
    $('#filterTipo').val(urlParams.get('tipo_certificado') || '');
    $('#filterEstatus').val(urlParams.get('estatus') || '');
    $('#filterResultado').val(urlParams.get('resultado') || '');
    $('#filterFechaInicio').val(urlParams.get('fecha_inicio') || '');
    $('#filterFechaFin').val(urlParams.get('fecha_fin') || '');
});
</script>
@endpush