@extends('layouts.app')

@section('title', 'Cumplimiento del Contribuyente')
@section('header', 'Análisis de Cumplimiento')

@section('actions')
<a href="{{ route('contribuyentes.show', $contribuyente_id) }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver al Contribuyente
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Resumen de Cumplimiento</h5>
            </div>
            <div class="card-body text-center">
                @php
                    $porcentaje = $cumplimiento['porcentaje_general'] ?? 0;
                    $badgeClass = $porcentaje >= 80 ? 'success' : ($porcentaje >= 60 ? 'warning' : 'danger');
                @endphp
                
                <h1 class="display-1 text-{{ $badgeClass }}">{{ $porcentaje }}%</h1>
                <p class="lead">Cumplimiento General</p>
                
                <div class="progress mb-3" style="height: 20px;">
                    <div class="progress-bar bg-{{ $badgeClass }}" role="progressbar" 
                         style="width: {{ $porcentaje }}%"></div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-6">
                        <h3>{{ $cumplimiento['cumple'] ?? 0 }}</h3>
                        <small class="text-success">Cumple</small>
                    </div>
                    <div class="col-6">
                        <h3>{{ $cumplimiento['no_cumple'] ?? 0 }}</h3>
                        <small class="text-danger">No Cumple</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Detalle por Categoría</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Categoría</th>
                                <th>Estatus</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cumplimiento['detalle'] ?? [] as $categoria => $detalle)
                                <tr>
                                    <td><strong>{{ ucfirst(str_replace('_', ' ', $categoria)) }}</strong></td>
                                    <td>
                                        @if($detalle['cumple'])
                                            <span class="badge bg-success">Cumple</span>
                                        @else
                                            <span class="badge bg-danger">No Cumple</span>
                                        @endif
                                    </td>
                                    <td>{{ $detalle['observaciones'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Documentación</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @forelse($cumplimiento['documentacion'] ?? [] as $doc)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-file-pdf text-danger me-2"></i>
                                {{ $doc['nombre'] }}
                                <br>
                                <small class="text-muted">Vence: {{ $doc['fecha_vencimiento'] ?? 'No definida' }}</small>
                            </div>
                            @if($doc['vigente'] ?? false)
                                <span class="badge bg-success">Vigente</span>
                            @else
                                <span class="badge bg-danger">Vencido</span>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted">No hay documentos registrados</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="card-title mb-0">Pendientes y Observaciones</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @forelse($cumplimiento['pendientes'] ?? [] as $pendiente)
                        <div class="list-group-item">
                            <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                            {{ $pendiente['descripcion'] }}
                            <br>
                            <small class="text-muted">Prioridad: {{ $pendiente['prioridad'] }}</small>
                        </div>
                    @empty
                        <p class="text-success">
                            <i class="bi bi-check-circle"></i> No hay pendientes
                        </p>
                    @endforelse
                </div>
                
                <hr>
                
                <h6>Recomendaciones:</h6>
                <ul class="list-unstyled">
                    @forelse($cumplimiento['recomendaciones'] ?? [] as $recomendacion)
                        <li class="mb-2">
                            <i class="bi bi-lightbulb text-info me-2"></i>
                            {{ $recomendacion }}
                        </li>
                    @empty
                        <li class="text-muted">No hay recomendaciones</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection