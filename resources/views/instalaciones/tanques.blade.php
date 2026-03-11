@extends('layouts.app')

@section('title', 'Tanques de la Instalación')
@section('header', 'Tanques de la Instalación')

@section('actions')
<a href="{{ route('instalaciones.show', $instalacion_id) }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver a la Instalación
</a>
<a href="{{ route('tanques.create', ['instalacion_id' => $instalacion_id]) }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-circle"></i> Nuevo Tanque
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Identificador</th>
                        <th>Producto</th>
                        <th>Capacidad Total</th>
                        <th>Capacidad Util</th>
                        <th>Volumen Actual</th>
                        <th>% Ocupación</th>
                        <th>Estado</th>
                        <th>Activo</th>
                        <th>Próx. Calibración</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tanques as $tanque)
                        <tr>
                            <td>{{ $tanque['identificador'] }}</td>
                            <td>
                                @if(isset($tanque['producto']))
                                    {{ $tanque['producto']['nombre'] }}
                                @else
                                    <span class="text-muted">No asignado</span>
                                @endif
                            </td>
                            <td>{{ number_format($tanque['capacidad_total'], 3) }} L</td>
                            <td>{{ number_format($tanque['capacidad_util'], 3) }} L</td>
                            <td>
                                @if(isset($tanque['volumen_actual']))
                                    <strong>{{ number_format($tanque['volumen_actual'], 3) }} L</strong>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($tanque['volumen_actual']) && $tanque['capacidad_util'] > 0)
                                    @php
                                        $porcentaje = ($tanque['volumen_actual'] / $tanque['capacidad_util']) * 100;
                                        $barClass = $porcentaje > 90 ? 'danger' : ($porcentaje > 75 ? 'warning' : 'success');
                                    @endphp
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-{{ $barClass }}" role="progressbar" 
                                             style="width: {{ $porcentaje }}%">{{ number_format($porcentaje, 1) }}%</div>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @php
                                    $estadoClass = [
                                        'OPERATIVO' => 'success',
                                        'MANTENIMIENTO' => 'warning',
                                        'FUERA_SERVICIO' => 'danger',
                                        'CALIBRACION' => 'info'
                                    ][$tanque['estado']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $estadoClass }}">{{ $tanque['estado'] }}</span>
                            </td>
                            <td>
                                @if($tanque['activo'])
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($tanque['fecha_proxima_calibracion']))
                                    @php
                                        $dias = now()->diffInDays($tanque['fecha_proxima_calibracion'], false);
                                        $badgeClass = $dias < 7 ? 'danger' : ($dias < 15 ? 'warning' : 'success');
                                    @endphp
                                    {{ $tanque['fecha_proxima_calibracion'] }}
                                    <span class="badge bg-{{ $badgeClass }}">{{ round($dias) }} días</span>
                                @else
                                    No programada
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('tanques.show', $tanque['id']) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('tanques.edit', $tanque['id']) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('tanques.curva-calibracion', $tanque['id']) }}" class="btn btn-sm btn-secondary" title="Curva Calibración">
                                        <i class="bi bi-graph-up"></i>
                                    </a>
                                    <a href="{{ route('tanques.verificar-estado', $tanque['id']) }}" class="btn btn-sm btn-primary" title="Verificar Estado">
                                        <i class="bi bi-check-circle"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">
                                No hay tanques registrados para esta instalación.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection