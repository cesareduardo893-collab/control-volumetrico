@extends('layouts.app')

@section('title', 'Instalaciones del Contribuyente')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Instalaciones del Contribuyente</h6>
                <div>
                    <a href="{{ route('contribuyentes.show', $id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver al Contribuyente
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="instalacionesTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Clave</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Domicilio</th>
                                <th>CP</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($instalaciones['data'] ?? [] as $instalacion)
                            <tr>
                                <td>{{ $instalacion['id'] }}</td>
                                <td>{{ $instalacion['clave_instalacion'] }}</td>
                                <td>{{ $instalacion['nombre'] }}</td>
                                <td>{{ ucfirst($instalacion['tipo_instalacion']) }}</td>
                                <td>{{ $instalacion['domicilio'] }}</td>
                                <td>{{ $instalacion['codigo_postal'] }}</td>
                                <td class="text-center">
                                    @if($instalacion['activo'])
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('instalaciones.show', $instalacion['id']) }}" 
                                           class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('instalaciones.tanques', $instalacion['id']) }}" 
                                           class="btn btn-secondary btn-sm" title="Tanques">
                                            <i class="fas fa-oil-can"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if(empty($instalaciones['data']))
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Este contribuyente no tiene instalaciones registradas.
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
    $('#instalacionesTable').DataTable({
        pageLength: 10,
        order: [[0, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        }
    });
});
</script>
@endpush