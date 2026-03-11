@extends('layouts.app')

@section('title', 'Detalle del Producto')
@section('header', 'Detalle del Producto')

@section('actions')
<a href="{{ route('productos.edit', $producto['id']) }}" class="btn btn-sm btn-warning">
    <i class="bi bi-pencil"></i> Editar
</a>
<a href="{{ route('productos.index') }}" class="btn btn-sm btn-secondary">
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
                        <th style="width: 40%">Clave SAT:</th>
                        <td><strong>{{ $producto['clave_sat'] }}</strong></td>
                    </tr>
                    <tr>
                        <th>Código Interno:</th>
                        <td>{{ $producto['codigo'] }}</td>
                    </tr>
                    <tr>
                        <th>Clave Identificación:</th>
                        <td>{{ $producto['clave_identificacion'] }}</td>
                    </tr>
                    <tr>
                        <th>Nombre:</th>
                        <td>{{ $producto['nombre'] }}</td>
                    </tr>
                    <tr>
                        <th>Tipo Hidrocarburo:</th>
                        <td>
                            <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $producto['tipo_hidrocarburo'])) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Unidad de Medida:</th>
                        <td>{{ $producto['unidad_medida'] }}</td>
                    </tr>
                    <tr>
                        <th>Activo:</th>
                        <td>
                            @if($producto['activo'])
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
                <h5 class="card-title mb-0">Propiedades Físicas</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th style="width: 40%">Densidad Referencia:</th>
                        <td>{{ $producto['densidad_referencia'] ?? 'No especificada' }} kg/L</td>
                    </tr>
                    <tr>
                        <th>Temperatura Referencia:</th>
                        <td>{{ $producto['temperatura_referencia'] ?? '15' }} °C</td>
                    </tr>
                    <tr>
                        <th>Factor de Conversión:</th>
                        <td>{{ $producto['factor_conversion'] ?? '1' }}</td>
                    </tr>
                    @if(isset($producto['octanaje']))
                        <tr>
                            <th>Octanaje:</th>
                            <td>{{ $producto['octanaje'] }}</td>
                        </tr>
                    @endif
                    @if(isset($producto['numero_octano']))
                        <tr>
                            <th>Número de Octano:</th>
                            <td>{{ $producto['numero_octano'] }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

@if(!empty($producto['descripcion']))
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Descripción</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $producto['descripcion'] }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Especificaciones técnicas -->
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="card-title mb-0">Especificaciones Técnicas</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Parámetro</th>
                                <th>Valor Mínimo</th>
                                <th>Valor Máximo</th>
                                <th>Unidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($producto['especificaciones'] ?? [] as $espec)
                                <tr>
                                    <td>{{ $espec['parametro'] }}</td>
                                    <td>{{ $espec['minimo'] ?? '-' }}</td>
                                    <td>{{ $espec['maximo'] ?? '-' }}</td>
                                    <td>{{ $espec['unidad'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No hay especificaciones registradas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">Normas Aplicables</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @forelse($producto['normas'] ?? [] as $norma)
                        <li class="list-group-item">
                            <strong>{{ $norma['clave'] }}</strong>
                            <p class="mb-0 small">{{ $norma['descripcion'] }}</p>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No hay normas registradas</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas de uso -->
<div class="row">
    <div class="col-md-3">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body text-center">
                <h3>{{ $producto['tanques_count'] ?? 0 }}</h3>
                <h6>Tanques Asignados</h6>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white mb-4">
            <div class="card-body text-center">
                <h3>{{ $producto['registros_count'] ?? 0 }}</h3>
                <h6>Registros Volumétricos</h6>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white mb-4">
            <div class="card-body text-center">
                <h3>{{ $producto['cfdi_count'] ?? 0 }}</h3>
                <h6>CFDI Relacionados</h6>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white mb-4">
            <div class="card-body text-center">
                <h3>{{ $producto['dictamenes_count'] ?? 0 }}</h3>
                <h6>Dictámenes</h6>
            </div>
        </div>
    </div>
</div>

<!-- Últimos registros volumétricos con este producto -->
@if(!empty($producto['ultimos_registros']))
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Últimos Registros Volumétricos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>N° Registro</th>
                                <th>Instalación</th>
                                <th>Tanque</th>
                                <th>Volumen</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($producto['ultimos_registros'] as $registro)
                                <tr>
                                    <td>{{ $registro['fecha'] }}</td>
                                    <td>{{ $registro['numero_registro'] }}</td>
                                    <td>{{ $registro['instalacion']['nombre'] ?? '' }}</td>
                                    <td>{{ $registro['tanque']['identificador'] ?? '' }}</td>
                                    <td>{{ number_format($registro['volumen_operacion'], 3) }} L</td>
                                    <td>
                                        @php
                                            $estadoClass = [
                                                'VALIDADO' => 'success',
                                                'PROCESADO' => 'info',
                                                'PENDIENTE' => 'warning'
                                            ][$registro['estado']] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $estadoClass }}">{{ $registro['estado'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Botón de eliminar (solo si no tiene registros asociados) -->
@if(($producto['tanques_count'] ?? 0) == 0 && ($producto['registros_count'] ?? 0) == 0)
<form method="POST" action="{{ route('productos.destroy', $producto['id']) }}" class="d-inline"
      onsubmit="return confirm('¿Está seguro de eliminar este producto? Esta acción no se puede deshacer.');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">
        <i class="bi bi-trash"></i> Eliminar Producto
    </button>
</form>
@endif
@endsection