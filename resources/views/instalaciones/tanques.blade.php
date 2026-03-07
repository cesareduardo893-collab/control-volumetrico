@extends('layouts.app')

@section('title', 'Tanques de Instalación')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Tanques de la Instalación</h6>
                <div>
                    <a href="{{ route('tanques.create', ['instalacion_id' => $id]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nuevo Tanque
                    </a>
                    <a href="{{ route('instalaciones.show', $id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver a Instalación
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tanquesTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Clave</th>
                                <th>Nombre</th>
                                <th>Producto</th>
                                <th>Capacidad (L)</th>
                                <th>Tipo</th>
                                <th>Forma</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tanques['data'] ?? [] as $tanque)
                            <tr>
                                <td>{{ $tanque['id'] }}</td>
                                <td>{{ $tanque['clave_tanque'] }}</td>
                                <td>{{ $tanque['nombre'] }}</td>
                                <td>{{ $tanque['producto']['nombre'] ?? 'N/A' }}</td>
                                <td class="text-end">{{ number_format($tanque['capacidad'], 2) }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $tanque['tipo_tanque'])) }}</td>
                                <td>{{ ucfirst($tanque['forma']) }}</td>
                                <td class="text-center">
                                    @if($tanque['activo'])
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('tanques.show', $tanque['id']) }}" 
                                           class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('tanques.existencias', $tanque['id']) }}" 
                                           class="btn btn-secondary btn-sm" title="Existencias">
                                            <i class="fas fa-boxes"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if(empty($tanques['data']))
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Esta instalación no tiene tanques registrados.
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
    $('#tanquesTable').DataTable({
        pageLength: 10,
        order: [[0, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        }
    });
});
</script>
@endpush