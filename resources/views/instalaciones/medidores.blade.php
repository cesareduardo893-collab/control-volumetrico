@extends('layouts.app')

@section('title', 'Medidores de Instalación')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Medidores de la Instalación</h6>
                <div>
                    <a href="{{ route('medidores.create', ['instalacion_id' => $id]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nuevo Medidor
                    </a>
                    <a href="{{ route('instalaciones.show', $id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver a Instalación
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="medidoresTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Clave</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Marca/Modelo</th>
                                <th>Serie</th>
                                <th>Rango</th>
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
                                <td>{{ $medidor['rango_medicion_min'] }} - {{ $medidor['rango_medicion_max'] }} {{ $medidor['unidad_medida'] }}</td>
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
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if(empty($medidores['data']))
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Esta instalación no tiene medidores registrados.
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
    $('#medidoresTable').DataTable({
        pageLength: 10,
        order: [[0, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        }
    });
});
</script>
@endpush