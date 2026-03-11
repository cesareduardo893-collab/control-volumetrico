@extends('layouts.app')

@section('title', 'Instalaciones del Contribuyente')
@section('header', 'Instalaciones del Contribuyente')

@section('actions')
<a href="{{ route('contribuyentes.show', $contribuyente_id) }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver al Contribuyente
</a>
<a href="{{ route('instalaciones.create', ['contribuyente_id' => $contribuyente_id]) }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-circle"></i> Nueva Instalación
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Domicilio</th>
                        <th>Municipio</th>
                        <th>Estado</th>
                        <th>Estatus</th>
                        <th>Tanques</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($instalaciones as $instalacion)
                        <tr>
                            <td><strong>{{ $instalacion['clave_instalacion'] }}</strong></td>
                            <td>{{ $instalacion['nombre'] }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $instalacion['tipo_instalacion'])) }}</td>
                            <td>{{ $instalacion['domicilio'] }}</td>
                            <td>{{ $instalacion['municipio'] }}</td>
                            <td>{{ $instalacion['estado'] }}</td>
                            <td>
                                @php
                                    $estatusClass = [
                                        'OPERACION' => 'success',
                                        'SUSPENDIDA' => 'warning',
                                        'CANCELADA' => 'danger'
                                    ][$instalacion['estatus']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $estatusClass }}">{{ $instalacion['estatus'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $instalacion['tanques_count'] ?? 0 }}</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('instalaciones.show', $instalacion['id']) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('instalaciones.edit', $instalacion['id']) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                Este contribuyente no tiene instalaciones registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection