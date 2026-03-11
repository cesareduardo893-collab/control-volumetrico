@extends('layouts.app')

@section('title', 'Medidores de la Instalación')
@section('header', 'Medidores de la Instalación')

@section('actions')
<a href="{{ route('instalaciones.show', $instalacion_id) }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver a la Instalación
</a>
<a href="{{ route('medidores.create', ['instalacion_id' => $instalacion_id]) }}" class="btn btn-sm btn-primary">
    <i class="bi bi-plus-circle"></i> Nuevo Medidor
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
                        <th>N° Serie</th>
                        <th>Modelo</th>
                        <th>Tipo</th>
                        <th>Elemento</th>
                        <th>Tanque Asignado</th>
                        <th>Precisión</th>
                        <th>Estado</th>
                        <th>Activo</th>
                        <th>Próx. Calibración</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medidores as $medidor)
                        <tr>
                            <td>{{ $medidor['clave'] }}</td>
                            <td>{{ $medidor['numero_serie'] }}</td>
                            <td>{{ $medidor['modelo'] ?? '-' }}</td>
                            <td>{{ $medidor['tipo_medicion'] }}</td>
                            <td>{{ ucfirst($medidor['elemento_tipo']) }}</td>
                            <td>
                                @if(isset($medidor['tanque']))
                                    {{ $medidor['tanque']['identificador'] }}
                                @else
                                    <span class="text-muted">No asignado</span>
                                @endif
                            </td>
                            <td>{{ $medidor['precision'] }}%</td>
                            <td>
                                @php
                                    $estadoClass = [
                                        'OPERATIVO' => 'success',
                                        'CALIBRACION' => 'info',
                                        'MANTENIMIENTO' => 'warning',
                                        'FUERA_SERVICIO' => 'danger',
                                        'FALLA_COMUNICACION' => 'secondary'
                                    ][$medidor['estado']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $estadoClass }}">{{ $medidor['estado'] }}</span>
                            </td>
                            <td>
                                @if($medidor['activo'])
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($medidor['fecha_proxima_calibracion']))
                                    @php
                                        $dias = now()->diffInDays($medidor['fecha_proxima_calibracion'], false);
                                        $badgeClass = $dias < 7 ? 'danger' : ($dias < 15 ? 'warning' : 'success');
                                    @endphp
                                    {{ $medidor['fecha_proxima_calibracion'] }}
                                    <span class="badge bg-{{ $badgeClass }}">{{ round($dias) }} días</span>
                                @else
                                    No programada
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('medidores.show', $medidor['id']) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('medidores.edit', $medidor['id']) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('medidores.probar-comunicacion', $medidor['id']) }}" class="btn btn-sm btn-secondary" title="Probar Comunicación">
                                        <i class="bi bi-wifi"></i>
                                    </a>
                                    <a href="{{ route('medidores.verificar-estado', $medidor['id']) }}" class="btn btn-sm btn-primary" title="Verificar Estado">
                                        <i class="bi bi-check-circle"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center">
                                No hay medidores registrados para esta instalación.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection