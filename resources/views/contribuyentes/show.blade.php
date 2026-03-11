@extends('layouts.app')

@section('title', 'Detalle del Contribuyente')
@section('header', 'Detalle del Contribuyente')

@section('actions')
<a href="{{ route('contribuyentes.edit', $contribuyente['id']) }}" class="btn btn-sm btn-warning">
    <i class="bi bi-pencil"></i> Editar
</a>
<a href="{{ route('contribuyentes.instalaciones', $contribuyente['id']) }}" class="btn btn-sm btn-info">
    <i class="bi bi-building"></i> Instalaciones
</a>
<a href="{{ route('contribuyentes.cumplimiento', $contribuyente['id']) }}" class="btn btn-sm btn-success">
    <i class="bi bi-check-circle"></i> Cumplimiento
</a>
<a href="{{ route('contribuyentes.index') }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Información General</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">RFC:</th>
                        <td><strong>{{ $contribuyente['rfc'] }}</strong></td>
                    </tr>
                    <tr>
                        <th>Razón Social:</th>
                        <td>{{ $contribuyente['razon_social'] }}</td>
                    </tr>
                    <tr>
                        <th>Nombre Comercial:</th>
                        <td>{{ $contribuyente['nombre_comercial'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Régimen Fiscal:</th>
                        <td>{{ $contribuyente['regimen_fiscal'] }}</td>
                    </tr>
                    <tr>
                        <th>Activo:</th>
                        <td>
                            @if($contribuyente['activo'])
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Domicilio Fiscal</h5>
            </div>
            <div class="card-body">
                <address>
                    <strong>{{ $contribuyente['domicilio_fiscal'] }}</strong><br>
                    C.P. {{ $contribuyente['codigo_postal'] }}
                </address>
                
                @if(!empty($contribuyente['telefono']) || !empty($contribuyente['email']))
                    <hr>
                    @if(!empty($contribuyente['telefono']))
                        <p><i class="bi bi-telephone"></i> {{ $contribuyente['telefono'] }}</p>
                    @endif
                    @if(!empty($contribuyente['email']))
                        <p><i class="bi bi-envelope"></i> {{ $contribuyente['email'] }}</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Representante Legal</h5>
            </div>
            <div class="card-body">
                @if(!empty($contribuyente['representante_legal']))
                    <table class="table table-sm">
                        <tr>
                            <th style="width: 40%">Nombre:</th>
                            <td>{{ $contribuyente['representante_legal'] }}</td>
                        </tr>
                        <tr>
                            <th>RFC:</th>
                            <td>{{ $contribuyente['representante_rfc'] ?? 'No especificado' }}</td>
                        </tr>
                    </table>
                @else
                    <p class="text-muted mb-0">No hay información del representante legal</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="card-title mb-0">Permiso</h5>
            </div>
            <div class="card-body">
                @if(!empty($contribuyente['numero_permiso']))
                    <table class="table table-sm">
                        <tr>
                            <th style="width: 40%">Número:</th>
                            <td><strong>{{ $contribuyente['numero_permiso'] }}</strong></td>
                        </tr>
                        <tr>
                            <th>Tipo:</th>
                            <td>{{ $contribuyente['tipo_permiso'] ?? 'No especificado' }}</td>
                        </tr>
                        <tr>
                            <th>Vencimiento:</th>
                            <td>
                                @if(!empty($contribuyente['fecha_vencimiento_permiso']))
                                    @php
                                        $dias = now()->diffInDays($contribuyente['fecha_vencimiento_permiso'], false);
                                        $badgeClass = $dias < 0 ? 'danger' : ($dias < 30 ? 'warning' : 'success');
                                    @endphp
                                    {{ $contribuyente['fecha_vencimiento_permiso'] }}
                                    @if($dias < 0)
                                        <span class="badge bg-danger">Vencido</span>
                                    @else
                                        <span class="badge bg-{{ $badgeClass }}">{{ $dias }} días</span>
                                    @endif
                                @else
                                    No especificado
                                @endif
                            </td>
                        </tr>
                    </table>
                @else
                    <p class="text-muted mb-0">No tiene permiso asignado</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">Verificaciones</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Estatus:</th>
                        <td>
                            @if(isset($contribuyente['estatus_verificacion']))
                                @php
                                    $verifClass = [
                                        'ACREDITADO' => 'success',
                                        'NO_ACREDITADO' => 'danger',
                                        'PENDIENTE' => 'warning',
                                        'EN_PROCESO' => 'info'
                                    ][$contribuyente['estatus_verificacion']] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $verifClass }}">{{ $contribuyente['estatus_verificacion'] }}</span>
                            @else
                                <span class="badge bg-secondary">No definido</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Última verificación:</th>
                        <td>{{ $contribuyente['fecha_ultima_verificacion'] ?? 'No registrada' }}</td>
                    </tr>
                    <tr>
                        <th>Próxima verificación:</th>
                        <td>
                            @if(!empty($contribuyente['fecha_proxima_verificacion']))
                                @php
                                    $diasVerif = now()->diffInDays($contribuyente['fecha_proxima_verificacion'], false);
                                    $badgeVerifClass = $diasVerif < 0 ? 'danger' : ($diasVerif < 15 ? 'warning' : 'success');
                                @endphp
                                {{ $contribuyente['fecha_proxima_verificacion'] }}
                                @if($diasVerif < 0)
                                    <span class="badge bg-danger">Vencida</span>
                                @else
                                    <span class="badge bg-{{ $badgeVerifClass }}">{{ $diasVerif }} días</span>
                                @endif
                            @else
                                No programada
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Estadísticas</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 text-center">
                        <h3>{{ $contribuyente['instalaciones_count'] ?? 0 }}</h3>
                        <small class="text-muted">Instalaciones</small>
                    </div>
                    <div class="col-6 text-center">
                        <h3>{{ $contribuyente['tanques_count'] ?? 0 }}</h3>
                        <small class="text-muted">Tanques</small>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6 text-center">
                        <h3>{{ $contribuyente['dictamenes_count'] ?? 0 }}</h3>
                        <small class="text-muted">Dictámenes</small>
                    </div>
                    <div class="col-6 text-center">
                        <h3>{{ $contribuyente['certificados_count'] ?? 0 }}</h3>
                        <small class="text-muted">Certificados</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Últimas instalaciones -->
@if(!empty($contribuyente['ultimas_instalaciones']))
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Últimas Instalaciones</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Clave</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contribuyente['ultimas_instalaciones'] as $instalacion)
                                <tr>
                                    <td>{{ $instalacion['clave_instalacion'] }}</td>
                                    <td>{{ $instalacion['nombre'] }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $instalacion['tipo_instalacion'])) }}</td>
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
                                    <td>
                                        <a href="{{ route('instalaciones.show', $instalacion['id']) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-end">
                    <a href="{{ route('contribuyentes.instalaciones', $contribuyente['id']) }}" class="btn btn-sm btn-primary">
                        Ver todas las instalaciones
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Botón de eliminar (solo si no tiene instalaciones) -->
@if(($contribuyente['instalaciones_count'] ?? 0) == 0)
<form method="POST" action="{{ route('contribuyentes.destroy', $contribuyente['id']) }}" class="d-inline"
      onsubmit="return confirm('¿Está seguro de eliminar este contribuyente? Esta acción no se puede deshacer.');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">
        <i class="bi bi-trash"></i> Eliminar Contribuyente
    </button>
</form>
@endif
@endsection