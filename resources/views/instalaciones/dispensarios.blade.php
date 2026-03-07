@extends('layouts.app')

@section('title', 'Dispensarios de Instalación')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Dispensarios de la Instalación</h6>
                <div>
                    <a href="{{ route('dispensarios.create', ['instalacion_id' => $id]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nuevo Dispensario
                    </a>
                    <a href="{{ route('instalaciones.show', $id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver a Instalación
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dispensariosTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Clave</th>
                                <th>Nombre</th>
                                <th>Marca/Modelo</th>
                                <th>Serie</th>
                                <th>N° Mangueras</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dispensarios['data'] ?? [] as $dispensario)
                            <tr>
                                <td>{{ $dispensario['id'] }}</td>
                                <td>{{ $dispensario['clave_dispensario'] }}</td>
                                <td>{{ $dispensario['nombre'] }}</td>
                                <td>{{ $dispensario['marca'] }} / {{ $dispensario['modelo'] }}</td>
                                <td>{{ $dispensario['serie'] }}</td>
                                <td class="text-center">{{ $dispensario['mangueras_count'] ?? 0 }}</td>
                                <td class="text-center">
                                    @if($dispensario['activo'])
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('dispensarios.show', $dispensario['id']) }}" 
                                           class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('dispensarios.edit', $dispensario['id']) }}" 
                                           class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if(empty($dispensarios['data']))
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Esta instalación no tiene dispensarios registrados.
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
    $('#dispensariosTable').DataTable({
        pageLength: 10,
        order: [[0, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        }
    });
});
</script>
@endpush