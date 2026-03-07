@extends('layouts.app')

@section('title', 'Alarmas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Listado de Alarmas</h6>
                <div>
                    <a href="{{ route('alarmas.dashboard') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-chart-pie"></i> Dashboard
                    </a>
                </div>
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
                        <select class="form-select form-select-sm" id="filterTipo">
                            <option value="">Todos los tipos</option>
                            <option value="nivel">Nivel</option>
                            <option value="temperatura">Temperatura</option>
                            <option value="presion">Presión</option>
                            <option value="flujo">Flujo</option>
                            <option value="equipo">Equipo</option>
                            <option value="comunicacion">Comunicación</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterEstado">
                            <option value="">Todos los estados</option>
                            <option value="activa">Activa</option>
                            <option value="atendida">Atendida</option>
                            <option value="descartada">Descartada</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm datepicker" id="filterFechaInicio" placeholder="Fecha Inicio">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm datepicker" id="filterFechaFin" placeholder="Fecha Fin">
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-primary btn-sm w-100" id="btnFilter">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="alarmasTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha/Hora</th>
                                <th>Instalación</th>
                                <th>Tipo</th>
                                <th>Mensaje</th>
                                <th>Valor</th>
                                <th>Umbral</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alarmas['data'] ?? [] as $alarma)
                            <tr class="{{ $alarma['estado'] == 'activa' ? 'table-danger' : '' }}">
                                <td>{{ $alarma['id'] }}</td>
                                <td>{{ $alarma['fecha_alarma'] }} {{ $alarma['hora_alarma'] }}</td>
                                <td>{{ $alarma['instalacion']['nombre'] ?? 'N/A' }}</td>
                                <td>
                                    @switch($alarma['tipo_alarma'])
                                        @case('nivel')
                                            <span class="badge bg-warning">Nivel</span>
                                            @break
                                        @case('temperatura')
                                            <span class="badge bg-danger">Temperatura</span>
                                            @break
                                        @case('presion')
                                            <span class="badge bg-info">Presión</span>
                                            @break
                                        @case('flujo')
                                            <span class="badge bg-primary">Flujo</span>
                                            @break
                                        @case('equipo')
                                            <span class="badge bg-secondary">Equipo</span>
                                            @break
                                        @case('comunicacion')
                                            <span class="badge bg-dark">Comunicación</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $alarma['mensaje'] }}</td>
                                <td class="text-end">{{ $alarma['valor'] ?? '-' }} {{ $alarma['unidad'] ?? '' }}</td>
                                <td class="text-end">{{ $alarma['umbral'] ?? '-' }} {{ $alarma['unidad'] ?? '' }}</td>
                                <td>
                                    @if($alarma['estado'] == 'activa')
                                        <span class="badge bg-danger">Activa</span>
                                    @elseif($alarma['estado'] == 'atendida')
                                        <span class="badge bg-success">Atendida</span>
                                    @else
                                        <span class="badge bg-secondary">Descartada</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('alarmas.show', $alarma['id']) }}" 
                                           class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($alarma['estado'] == 'activa')
                                        <button type="button" 
                                                class="btn btn-success btn-sm btn-atender" 
                                                data-id="{{ $alarma['id'] }}"
                                                title="Atender">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-warning btn-sm btn-descartar" 
                                                data-id="{{ $alarma['id'] }}"
                                                title="Descartar">
                                            <i class="fas fa-times"></i>
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
                @if(isset($alarmas['meta']))
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p>Mostrando {{ $alarmas['meta']['from'] ?? 0 }} a {{ $alarmas['meta']['to'] ?? 0 }} de {{ $alarmas['meta']['total'] ?? 0 }} registros</p>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end">
                                @foreach($alarmas['meta']['links'] ?? [] as $link)
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

<!-- Modal atender alarma -->
<div class="modal fade" id="atenderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="atenderForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Atender Alarma</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="estado" value="atendida">
                    <input type="hidden" name="usuario_atencion" value="{{ session('user.id') }}">
                    <input type="hidden" name="fecha_atencion" value="{{ date('Y-m-d') }}">
                    <input type="hidden" name="hora_atencion" value="{{ date('H:i:s') }}">
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Atender</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal descartar alarma -->
<div class="modal fade" id="descartarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="descartarForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Descartar Alarma</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="estado" value="descartada">
                    <input type="hidden" name="usuario_atencion" value="{{ session('user.id') }}">
                    <input type="hidden" name="fecha_atencion" value="{{ date('Y-m-d') }}">
                    <input type="hidden" name="hora_atencion" value="{{ date('H:i:s') }}">
                    
                    <div class="mb-3">
                        <label for="observaciones_descartar" class="form-label">Motivo del descarte</label>
                        <textarea class="form-control" id="observaciones_descartar" name="observaciones" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Descartar</button>
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
    var table = $('#alarmasTable').DataTable({
        pageLength: {{ $alarmas['meta']['per_page'] ?? 10 }},
        order: [[1, 'desc']],
        searching: false,
        paging: false,
        info: false
    });

    // Filtros
    $('#btnFilter').click(function() {
        var params = new URLSearchParams();
        
        if ($('#filterInstalacion').val()) params.set('instalacion_id', $('#filterInstalacion').val());
        if ($('#filterTipo').val()) params.set('tipo_alarma', $('#filterTipo').val());
        if ($('#filterEstado').val()) params.set('estado', $('#filterEstado').val());
        if ($('#filterFechaInicio').val()) params.set('fecha_inicio', $('#filterFechaInicio').val());
        if ($('#filterFechaFin').val()) params.set('fecha_fin', $('#filterFechaFin').val());
        
        window.location.href = window.location.pathname + '?' + params.toString();
    });

    // Botón atender
    $('.btn-atender').click(function() {
        var id = $(this).data('id');
        var form = $('#atenderForm');
        form.attr('action', '{{ url("alarmas") }}/' + id);
        $('#atenderModal').modal('show');
    });

    // Botón descartar
    $('.btn-descartar').click(function() {
        var id = $(this).data('id');
        var form = $('#descartarForm');
        form.attr('action', '{{ url("alarmas") }}/' + id);
        $('#descartarModal').modal('show');
    });

    // Actualización automática cada 30 segundos
    setInterval(function() {
        if (document.visibilityState === 'visible') {
            location.reload();
        }
    }, 30000);

    // Cargar valores de filtros desde URL
    var urlParams = new URLSearchParams(window.location.search);
    $('#filterInstalacion').val(urlParams.get('instalacion_id') || '');
    $('#filterTipo').val(urlParams.get('tipo_alarma') || '');
    $('#filterEstado').val(urlParams.get('estado') || '');
    $('#filterFechaInicio').val(urlParams.get('fecha_inicio') || '');
    $('#filterFechaFin').val(urlParams.get('fecha_fin') || '');
});
</script>
@endpush