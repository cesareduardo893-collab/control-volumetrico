@extends('layouts.app')

@section('title', 'Existencias del Tanque')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Existencias del Tanque</h6>
                <div>
                    <a href="{{ route('existencias.create', ['tanque_id' => $id]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nueva Existencia
                    </a>
                    <a href="{{ route('tanques.show', $id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver al Tanque
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filtros -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm datepicker" id="filterFechaInicio" placeholder="Fecha Inicio">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm datepicker" id="filterFechaFin" placeholder="Fecha Fin">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterEstado">
                            <option value="">Todos los estados</option>
                            <option value="valida">Válida</option>
                            <option value="invalida">Inválida</option>
                            <option value="pendiente">Pendiente</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterMetodo">
                            <option value="">Todos los métodos</option>
                            <option value="manual">Manual</option>
                            <option value="automatica">Automática</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary btn-sm w-100" id="btnFilter">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>

                <!-- Gráfica de existencias -->
                <div class="row mb-4">
                    <div class="col-12">
                        <canvas id="existenciasChart"></canvas>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="existenciasTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Volumen Bruto</th>
                                <th>Volumen Neto</th>
                                <th>Temperatura</th>
                                <th>Densidad</th>
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
                                <td class="text-end">{{ number_format($existencia['volumen_bruto'], 2) }} L</td>
                                <td class="text-end">{{ number_format($existencia['volumen_neto'], 2) }} L</td>
                                <td class="text-end">{{ $existencia['temperatura'] }} °C</td>
                                <td class="text-end">{{ $existencia['densidad'] }}</td>
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Inicializar DataTable
    var table = $('#existenciasTable').DataTable({
        pageLength: 10,
        order: [[1, 'desc'], [2, 'desc']],
        searching: false,
        paging: false,
        info: false
    });

    // Gráfica de existencias
    var ctx = document.getElementById('existenciasChart').getContext('2d');
    var fechas = [];
    var volumenes = [];
    
    @foreach($existencias['data'] ?? [] as $existencia)
        fechas.push('{{ $existencia['fecha_medicion'] }}');
        volumenes.push({{ $existencia['volumen_neto'] }});
    @endforeach

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: fechas.slice(0, 30),
            datasets: [{
                label: 'Volumen Neto (L)',
                data: volumenes.slice(0, 30),
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Histórico de Volumen'
                }
            }
        }
    });

    // Filtros
    $('#btnFilter').click(function() {
        var params = new URLSearchParams(window.location.search);
        
        if ($('#filterFechaInicio').val()) params.set('fecha_inicio', $('#filterFechaInicio').val());
        if ($('#filterFechaFin').val()) params.set('fecha_fin', $('#filterFechaFin').val());
        if ($('#filterEstado').val()) params.set('estado', $('#filterEstado').val());
        if ($('#filterMetodo').val()) params.set('metodo_medicion', $('#filterMetodo').val());
        
        window.location.href = window.location.pathname + '?' + params.toString();
    });

    // Botones de validar
    $('.btn-validar').click(function() {
        var id = $(this).data('id');
        var form = $('#validarForm');
        form.attr('action', '{{ url("existencias") }}/' + id + '/validar');
        $('#validarModal').modal('show');
    });

    // Cargar valores de filtros desde URL
    var urlParams = new URLSearchParams(window.location.search);
    $('#filterFechaInicio').val(urlParams.get('fecha_inicio') || '');
    $('#filterFechaFin').val(urlParams.get('fecha_fin') || '');
    $('#filterEstado').val(urlParams.get('estado') || '');
    $('#filterMetodo').val(urlParams.get('metodo_medicion') || '');
});
</script>
@endpush