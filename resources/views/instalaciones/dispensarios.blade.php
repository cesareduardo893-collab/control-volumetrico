@extends('layouts.app')

@section('title', 'Dispensarios de la Instalación')
@section('header', 'Dispensarios de la Instalación')

@section('actions')
<a href="{{ route('instalaciones.show', $instalacion_id) }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver a la Instalación
</a>
<a href="{{ route('dispensarios.create', ['instalacion_id' => $instalacion_id]) }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-circle"></i> Nuevo Dispensario
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
                        <th>Descripción</th>
                        <th>Modelo</th>
                        <th>Fabricante</th>
                        <th>N° Mangueras</th>
                        <th>Estado</th>
                        <th>Activo</th>
                        <th>Próx. Mantenimiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dispensarios as $dispensario)
                        <tr>
                            <td>{{ $dispensario['clave'] }}</td>
                            <td>{{ $dispensario['descripcion'] ?? '-' }}</td>
                            <td>{{ $dispensario['modelo'] ?? '-' }}</td>
                            <td>{{ $dispensario['fabricante'] ?? '-' }}</td>
                            <td><span class="badge bg-info">{{ $dispensario['mangueras_count'] ?? 0 }}</span></td>
                            <td>
                                @php
                                    $estadoClass = [
                                        'OPERATIVO' => 'success',
                                        'MANTENIMIENTO' => 'warning',
                                        'FUERA_SERVICIO' => 'danger'
                                    ][$dispensario['estado']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $estadoClass }}">{{ $dispensario['estado'] }}</span>
                            </td>
                            <td>
                                @if($dispensario['activo'])
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($dispensario['fecha_proximo_mantenimiento']))
                                    @php
                                        $dias = now()->diffInDays($dispensario['fecha_proximo_mantenimiento'], false);
                                        $badgeClass = $dias < 7 ? 'danger' : ($dias < 15 ? 'warning' : 'success');
                                    @endphp
                                    {{ $dispensario['fecha_proximo_mantenimiento'] }}
                                    <span class="badge bg-{{ $badgeClass }}">{{ round($dias) }} días</span>
                                @else
                                    No programado
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('dispensarios.show', $dispensario['id']) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('dispensarios.edit', $dispensario['id']) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('dispensarios.mangueras', $dispensario['id']) }}" class="btn btn-sm btn-secondary" title="Mangueras">
                                        <i class="bi bi-pip"></i>
                                    </a>
                                    <a href="{{ route('dispensarios.verificar-estado', $dispensario['id']) }}" class="btn btn-sm btn-primary" title="Verificar Estado">
                                        <i class="bi bi-check-circle"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                No hay dispensarios registrados para esta instalación.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection