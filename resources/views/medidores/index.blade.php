@extends('layouts.app')

@section('title', 'Medidores')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Listado de Medidores</h6>
                <a href="{{ route('medidores.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Medidor
                </a>
            </div>
            <div class="card-body">
                <!-- Filtros -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm" id="filterClave" placeholder="Clave Medidor">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm" id="filterNombre" placeholder="Nombre">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterTipo">
                            <option value="">Todos los tipos</option>
                            <option value="flotador">Flotador</option>
                            <option value="ultrasonico">Ultrasónico</option>
                            <option value="radar">Radar</option>
                            <option value="electromagnetico">Electromagnético</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" id="filterEstado">
                            <option value="">Todos</option>
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
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
                    <table class="table table-bordered table-hover" id="medidoresTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Clave</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Marca/Modelo</th>
                                <th>Serie</th>
                                <th>Asignado a</th>
                                <th>Estado Calibración</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medidores['data'] ?? [] as $medidor)
                            <tr>
                                <td>{{ $medidor['id'] }}</td>
                                <td>{{ $medidor['clave_medidor'] }}</td>
                                <td>{{ $medidor['nombre'] }}</td>
                                <td>{{ ucfirst($medidor['tipo_medidor']) }}</td>
                                <td>{{ $medidor['marca'] }} / {{ $medidor['modelo'] }}</td>
                                <td>{{ $medidor['serie'] }}</td>
                                <td>
                                    @if($medidor['tanque_id'])
                                        Tanque: {{ $medidor['tanque']['nombre'] ?? 'N/A' }}
                                    @elseif($medidor['dispensario_id'])
                                        Dispensario: {{ $medidor['dispensario']['nombre'] ?? 'N/A' }}
                                    @else
                                        Sin asignar
                                    @endif
                                </td>
                                <td>
                                    @if($medidor['estado_calibracion'] == 'calibrado')
                                        <span class="badge bg-success">Calibrado</span>
                                    @elseif($medidor['estado_calibracion'] == 'no_calibrado')
                                        <span class="badge bg-danger">No calibrado</span>
                                    @else
                                        <span class="badge bg-warning">Pendiente</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($medidor['activo'])
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('medidores.show', $medidor['id']) }}" 
                                           class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('medidores.edit', $medidor['id']) }}" 
                                           class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-success btn-sm btn-calibrar" 
                                                data-id="{{ $medidor['id'] }}"
                                                data-name="{{ $medidor['nombre'] }}"
                                                title="Calibrar">
                                            <i class="fas fa-tools"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm btn-delete" 
                                                data-id="{{ $medidor['id'] }}"
                                                data-name="{{ $medidor['nombre'] }}"
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
                @if(isset($medidores['meta']))
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p>Mostrando {{ $medidores['meta']['from'] ?? 0 }} a {{ $medidores['meta']['to'] ?? 0 }} de {{ $medidores['meta']['total'] ?? 0 }} registros</p>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end">
                                @foreach($medidores['meta']['links'] ?? [] as $link)
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

<!-- Modal de calibración -->
<div class="modal fade" id="calibrarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="calibrarForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Calibrar Medidor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="valor_referencia" class="form-label">Valor de Referencia</label>
                        <input type="number" class="form-control" id="valor_referencia" name="valor_referencia" step="0.001" required>
                    </div>
                    <div class="mb-3">
                        <label for="valor_medido" class="form-label">Valor Medido</label>
                        <input type="number" class="form-control" id="valor_medido" name="valor_medido" step="0.001" required>
                    </div>
                    <div class="mb-3">
                        <label for="factor_correccion" class="form-label">Factor de Corrección</label>
                        <input type="number" class="form-control" id="factor_correccion" name="factor_correccion" step="0.001" required>
                    </div>
                    <div class="mb-3">
                        <label for="tecnico_calibracion" class="form-label">Técnico</label>
                        <input type="text" class="form-control" id="tecnico_calibracion" name="tecnico_calibracion" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_calibracion" class="form-label">Fecha de Calibración</label>
                        <input type="text" class="form-control datepicker" id="fecha_calibracion" name="fecha_calibracion" required>
                    </div>
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Calibrar</button>
                </div>
            </form>
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
    var table = $('#medidoresTable').DataTable({
        pageLength: {{ $medidores['meta']['per_page'] ?? 10 }},
        order: [[0, 'desc']],
        searching: false,
        paging: false,
        info: false
    });

    // Filtros
    $('#btnFilter').click(function() {
        var params = new URLSearchParams(window.location.search);
        
        if ($('#filterClave').val()) params.set('clave_medidor', $('#filterClave').val());
        if ($('#filterNombre').val()) params.set('nombre', $('#filterNombre').val());
        if ($('#filterTipo').val()) params.set('tipo_medidor', $('#filterTipo').val());
        if ($('#filterEstado').val()) params.set('activo', $('#filterEstado').val());
        
        window.location.href = window.location.pathname + '?' + params.toString();
    });

    // Botones de calibrar
    $('.btn-calibrar').click(function() {
        var id = $(this).data('id');
        var form = $('#calibrarForm');
        form.attr('action', '{{ url("medidores") }}/' + id + '/calibrar');
        $('#calibrarModal').modal('show');
    });

    // Calcular factor de corrección automáticamente
    $('#valor_referencia, #valor_medido').on('input', function() {
        var referencia = parseFloat($('#valor_referencia').val()) || 0;
        var medido = parseFloat($('#valor_medido').val()) || 0;
        
        if (referencia > 0 && medido > 0) {
            var factor = referencia / medido;
            $('#factor_correccion').val(factor.toFixed(4));
        }
    });

    // Botones de eliminar
    $('.btn-delete').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: `¿Deseas eliminar el medidor "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                var form = $('#deleteForm');
                form.attr('action', '{{ url("medidores") }}/' + id);
                form.submit();
            }
        });
    });

    // Cargar valores de filtros desde URL
    var urlParams = new URLSearchParams(window.location.search);
    $('#filterClave').val(urlParams.get('clave_medidor') || '');
    $('#filterNombre').val(urlParams.get('nombre') || '');
    $('#filterTipo').val(urlParams.get('tipo_medidor') || '');
    $('#filterEstado').val(urlParams.get('activo') || '');
});
</script>
@endpush