@extends('layouts.app')

@section('title', 'Bitácora de Eventos')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Bitácora de Eventos</h6>
                <div>
                    <a href="{{ route('bitacora.dashboard') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-chart-pie"></i> Dashboard
                    </a>
                    <a href="{{ route('bitacora.exportar-csv') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-file-csv"></i> Exportar CSV
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filtros -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-select form-select-sm" id="filterUsuario">
                            <option value="">Todos los usuarios</option>
                            @foreach($usuarios ?? [] as $usuario)
                                <option value="{{ $usuario['id'] }}">{{ $usuario['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select form-select-sm" id="filterTipo">
                            <option value="">Todos los tipos</option>
                            <option value="login">Login</option>
                            <option value="logout">Logout</option>
                            <option value="create">Creación</option>
                            <option value="update">Actualización</option>
                            <option value="delete">Eliminación</option>
                            <option value="view">Visualización</option>
                            <option value="export">Exportación</option>
                            <option value="import">Importación</option>
                            <option value="sync">Sincronización</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm datepicker" id="filterFechaInicio" placeholder="Fecha Inicio">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm datepicker" id="filterFechaFin" placeholder="Fecha Fin">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary btn-sm w-100" id="btnFilter">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="bitacoraTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha/Hora</th>
                                <th>Usuario</th>
                                <th>Tipo</th>
                                <th>Módulo</th>
                                <th>Acción</th>
                                <th>Descripción</th>
                                <th>IP</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventos['data'] ?? [] as $evento)
                            <tr>
                                <td>{{ $evento['id'] }}</td>
                                <td>{{ $evento['fecha'] }} {{ $evento['hora'] }}</td>
                                <td>{{ $evento['usuario']['name'] ?? 'Sistema' }}</td>
                                <td>
                                    @switch($evento['tipo_evento'])
                                        @case('login')
                                            <span class="badge bg-success">Login</span>
                                            @break
                                        @case('logout')
                                            <span class="badge bg-secondary">Logout</span>
                                            @break
                                        @case('create')
                                            <span class="badge bg-primary">Creación</span>
                                            @break
                                        @case('update')
                                            <span class="badge bg-warning">Actualización</span>
                                            @break
                                        @case('delete')
                                            <span class="badge bg-danger">Eliminación</span>
                                            @break
                                        @default
                                            <span class="badge bg-info">{{ $evento['tipo_evento'] }}</span>
                                    @endswitch
                                </td>
                                <td>{{ $evento['modulo'] }}</td>
                                <td>{{ $evento['accion'] }}</td>
                                <td>{{ Str::limit($evento['descripcion'], 50) }}</td>
                                <td>{{ $evento['ip_address'] }}</td>
                                <td class="text-center">
                                    <a href="{{ route('bitacora.show', $evento['id']) }}" 
                                       class="btn btn-info btn-sm" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if(isset($eventos['meta']))
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p>Mostrando {{ $eventos['meta']['from'] ?? 0 }} a {{ $eventos['meta']['to'] ?? 0 }} de {{ $eventos['meta']['total'] ?? 0 }} registros</p>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end">
                                @foreach($eventos['meta']['links'] ?? [] as $link)
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
    var table = $('#bitacoraTable').DataTable({
        pageLength: {{ $eventos['meta']['per_page'] ?? 10 }},
        order: [[1, 'desc']],
        searching: false,
        paging: false,
        info: false
    });

    // Filtros
    $('#btnFilter').click(function() {
        var params = new URLSearchParams();
        
        if ($('#filterUsuario').val()) params.set('usuario_id', $('#filterUsuario').val());
        if ($('#filterTipo').val()) params.set('tipo_evento', $('#filterTipo').val());
        if ($('#filterFechaInicio').val()) params.set('fecha_inicio', $('#filterFechaInicio').val());
        if ($('#filterFechaFin').val()) params.set('fecha_fin', $('#filterFechaFin').val());
        
        window.location.href = window.location.pathname + '?' + params.toString();
    });

    // Cargar valores de filtros desde URL
    var urlParams = new URLSearchParams(window.location.search);
    $('#filterUsuario').val(urlParams.get('usuario_id') || '');
    $('#filterTipo').val(urlParams.get('tipo_evento') || '');
    $('#filterFechaInicio').val(urlParams.get('fecha_inicio') || '');
    $('#filterFechaFin').val(urlParams.get('fecha_fin') || '');
});
</script>
@endpush