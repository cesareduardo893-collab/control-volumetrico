@extends('layouts.app')

@section('title', 'Verificación de Vigencia')
@section('header', 'Resultado de Verificación de Vigencia')

@section('actions')
<a href="{{ route('dictamenes.show', $resultado['dictamen_id']) }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver al Dictamen
</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header 
                @if($resultado['vigente']) bg-success 
                @elseif($resultado['proximo_vencer']) bg-warning 
                @else bg-danger 
                @endif text-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-calendar-check"></i> 
                    Resultado de Verificación de Vigencia
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    @if($resultado['vigente'])
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                        <h3 class="text-success mt-3">DICTAMEN VIGENTE</h3>
                    @elseif($resultado['proximo_vencer'])
                        <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 5rem;"></i>
                        <h3 class="text-warning mt-3">PRÓXIMO A VENCER</h3>
                    @else
                        <i class="bi bi-x-circle-fill text-danger" style="font-size: 5rem;"></i>
                        <h3 class="text-danger mt-3">DICTAMEN VENCIDO</h3>
                    @endif
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Información del Dictamen</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <th>Folio:</th>
                                        <td>{{ $resultado['folio'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Número de Lote:</th>
                                        <td>{{ $resultado['numero_lote'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Fecha de Emisión:</th>
                                        <td>{{ $resultado['fecha_emision'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Producto:</th>
                                        <td>{{ $resultado['producto'] }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Detalles de Vigencia</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <th>Días desde emisión:</th>
                                        <td>{{ $resultado['dias_desde_emision'] ?? 0 }} días</td>
                                    </tr>
                                    <tr>
                                        <th>Días hasta vencimiento:</th>
                                        <td>
                                            @if(isset($resultado['dias_hasta_vencimiento']))
                                                @if($resultado['dias_hasta_vencimiento'] > 0)
                                                    <span class="text-success">{{ $resultado['dias_hasta_vencimiento'] }} días</span>
                                                @else
                                                    <span class="text-danger">{{ abs($resultado['dias_hasta_vencimiento']) }} días vencido</span>
                                                @endif
                                            @else
                                                <span class="text-muted">No aplica</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Fecha de vencimiento:</th>
                                        <td>{{ $resultado['fecha_vencimiento'] ?? 'No definida' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Estado actual:</th>
                                        <td>
                                            @php
                                                $estadoClass = [
                                                    'VIGENTE' => 'success',
                                                    'CADUCADO' => 'warning',
                                                    'CANCELADO' => 'secondary'
                                                ][$resultado['estado']] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $estadoClass }}">{{ $resultado['estado'] }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if(isset($resultado['recomendaciones']) && count($resultado['recomendaciones']) > 0)
                <div class="alert alert-info mt-3">
                    <h6><i class="bi bi-info-circle"></i> Recomendaciones:</h6>
                    <ul class="mb-0">
                        @foreach($resultado['recomendaciones'] as $recomendacion)
                            <li>{{ $recomendacion }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <hr>
                
                <div class="d-flex justify-content-center">
                    <a href="{{ route('dictamenes.show', $resultado['dictamen_id']) }}" class="btn btn-primary me-2">
                        <i class="bi bi-eye"></i> Ver Dictamen
                    </a>
                    <a href="{{ route('dictamenes.index') }}" class="btn btn-secondary">
                        <i class="bi bi-list"></i> Ir a Listado
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection