@extends('layouts.app')

@section('title', 'Cumplimiento del Contribuyente')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Cumplimiento Normativo</h6>
                <a href="{{ route('contribuyentes.show', $id) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
            <div class="card-body">
                <!-- Resumen de cumplimiento -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h6 class="card-title">Cumplimiento General</h6>
                                <h3>{{ $cumplimiento['porcentaje_general'] ?? 0 }}%</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h6 class="card-title">Instalaciones al día</h6>
                                <h3>{{ $cumplimiento['instalaciones_al_dia'] ?? 0 }}/{{ $cumplimiento['total_instalaciones'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h6 class="card-title">Reportes pendientes</h6>
                                <h3>{{ $cumplimiento['reportes_pendientes'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h6 class="card-title">Incumplimientos</h6>
                                <h3>{{ $cumplimiento['incumplimientos'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Detalle por instalación -->
                <h5 class="mb-3">Detalle por Instalación</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Instalación</th>
                                <th>Último Reporte</th>
                                <th>Dictamen Vigente</th>
                                <th>Certificado</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cumplimiento['detalle_instalaciones'] ?? [] as $detalle)
                            <tr>
                                <td>{{ $detalle['instalacion'] }}</td>
                                <td>{{ $detalle['ultimo_reporte'] ?? 'N/A' }}</td>
                                <td>
                                    @if($detalle['dictamen_vigente'])
                                        <span class="badge bg-success">Vigente</span>
                                    @else
                                        <span class="badge bg-danger">Vencido</span>
                                    @endif
                                </td>
                                <td>
                                    @if($detalle['certificado_vigente'])
                                        <span class="badge bg-success">Vigente</span>
                                    @else
                                        <span class="badge bg-danger">Vencido</span>
                                    @endif
                                </td>
                                <td>
                                    @if($detalle['estado'] == 'cumple')
                                        <span class="badge bg-success">Cumple</span>
                                    @elseif($detalle['estado'] == 'parcial')
                                        <span class="badge bg-warning">Cumple Parcial</span>
                                    @else
                                        <span class="badge bg-danger">Incumple</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Gráficas -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <canvas id="cumplimientoChart"></canvas>
                    </div>
                    <div class="col-md-6">
                        <canvas id="reportesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Gráfica de cumplimiento
    const ctx1 = document.getElementById('cumplimientoChart').getContext('2d');
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: ['Cumple', 'Parcial', 'Incumple'],
            datasets: [{
                data: [
                    {{ $cumplimiento['instalaciones_cumplen'] ?? 0 }},
                    {{ $cumplimiento['instalaciones_parcial'] ?? 0 }},
                    {{ $cumplimiento['instalaciones_incumplen'] ?? 0 }}
                ],
                backgroundColor: ['#28a745', '#ffc107', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Estado de Instalaciones'
                }
            }
        }
    });
    
    // Gráfica de reportes
    const ctx2 = document.getElementById('reportesChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [{
                label: 'Reportes enviados',
                data: [12, 19, 15, 17, 14, 18],
                backgroundColor: 'rgba(54, 162, 235, 0.5)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Reportes por Mes'
                }
            }
        }
    });
});
</script>
@endpush