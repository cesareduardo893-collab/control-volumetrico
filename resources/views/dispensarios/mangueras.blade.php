@extends('layouts.app')

@section('title', 'Mangueras del Dispensario')
@section('header', 'Mangueras del Dispensario')

@section('actions')
<a href="{{ route('dispensarios.show', $dispensario_id) }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver al Dispensario
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
                        <th>Medidor Asignado</th>
                        <th>Estado</th>
                        <th>Activo</th>
                        <th>Última Calibración</th>
                        <th>Próxima Calibración</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mangueras as $manguera)
                        <tr>
                            <td>{{ $manguera['clave'] }}</td>
                            <td>{{ $manguera['descripcion'] ?? '-' }}</td>
                            <td>
                                @if(isset($manguera['medidor']))
                                    <strong>{{ $manguera['medidor']['clave'] }}</strong><br>
                                    <small class="text-muted">{{ $manguera['medidor']['numero_serie'] }}</small><br>
                                    <span class="badge bg-{{ $manguera['medidor']['estado'] == 'OPERATIVO' ? 'success' : 'warning' }}">
                                        {{ $manguera['medidor']['estado'] }}
                                    </span>
                                @else
                                    <span class="text-muted">No asignado</span>
                                    <a href="{{ route('mangueras.edit', $manguera['id']) }}" class="btn btn-sm btn-primary mt-1">
                                        Asignar
                                    </a>
                                @endif
                            </td>
                            <td>
                                @php
                                    $estadoClass = [
                                        'OPERATIVO' => 'success',
                                        'MANTENIMIENTO' => 'warning',
                                        'FUERA_SERVICIO' => 'danger'
                                    ][$manguera['estado']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $estadoClass }}">{{ $manguera['estado'] }}</span>
                            </td>
                            <td>
                                @if($manguera['activo'])
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>{{ $manguera['fecha_ultima_calibracion'] ?? 'No registrada' }}</td>
                            <td>
                                @if(isset($manguera['fecha_proxima_calibracion']))
                                    @php
                                        $dias = now()->diffInDays($manguera['fecha_proxima_calibracion'], false);
                                        $badgeClass = $dias < 7 ? 'danger' : ($dias < 15 ? 'warning' : 'success');
                                    @endphp
                                    {{ $manguera['fecha_proxima_calibracion'] }}
                                    <span class="badge bg-{{ $badgeClass }}">{{ round($dias) }} días</span>
                                @else
                                    No programada
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('mangueras.show', $manguera['id']) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('mangueras.edit', $manguera['id']) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                No hay mangueras registradas para este dispensario.
                                <br>
                                <a href="{{ route('mangueras.index') }}" class="btn btn-sm btn-primary mt-2">
                                    Ver todas las mangueras
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection