@extends('layouts.app')

@section('title', 'Historial de Calibraciones')
@section('header', 'Historial de Calibraciones del Medidor')

@section('actions')
<a href="{{ route('medidores.show', $medidor_id) }}" class="btn btn-sm btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver al Medidor
</a>
<button type="button" class="btn btn-sm btn-primary" onclick="mostrarFormularioCalibracion()">
    <i class="bi bi-plus-circle"></i> Registrar Calibración
</button>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Certificado</th>
                        <th>Laboratorio</th>
                        <th>Precisión</th>
                        <th>Desviación</th>
                        <th>Resultado</th>
                        <th>Próxima Calibración</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historial as $calibracion)
                        <tr>
                            <td>{{ $calibracion['fecha'] }}</td>
                            <td>
                                @if(!empty($calibracion['certificado_url']))
                                    <a href="{{ $calibracion['certificado_url'] }}" target="_blank">
                                        <i class="bi bi-file-pdf text-danger"></i> {{ $calibracion['certificado'] }}
                                    </a>
                                @else
                                    {{ $calibracion['certificado'] }}
                                @endif
                            </td>
                            <td>{{ $calibracion['laboratorio'] }}</td>
                            <td>{{ $calibracion['precision'] }}%</td>
                            <td>
                                @php
                                    $desviacionClass = $calibracion['desviacion'] > 1 ? 'danger' : ($calibracion['desviacion'] > 0.5 ? 'warning' : 'success');
                                @endphp
                                <span class="text-{{ $desviacionClass }}">{{ $calibracion['desviacion'] }}%</span>
                            </td>
                            <td>
                                @if($calibracion['exitosa'])
                                    <span class="badge bg-success">Exitosa</span>
                                @else
                                    <span class="badge bg-danger">Fallida</span>
                                @endif
                            </td>
                            <td>{{ $calibracion['proxima_calibracion'] ?? 'No programada' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="#" class="btn btn-sm btn-info" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-success" title="Descargar Certificado">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                No hay calibraciones registradas para este medidor.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de registro de calibración -->
<div class="modal fade" id="calibracionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Registrar Nueva Calibración</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('medidores.registrar-calibracion', $medidor_id) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_calibracion" class="form-label">Fecha de Calibración *</label>
                            <input type="date" class="form-control datepicker" id="fecha_calibracion" 
                                   name="fecha_calibracion" value="{{ now()->toDateString() }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="fecha_proxima_calibracion" class="form-label">Próxima Calibración *</label>
                            <input type="date" class="form-control datepicker" id="fecha_proxima_calibracion" 
                                   name="fecha_proxima_calibracion" value="{{ now()->addYear()->toDateString() }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="certificado_calibracion" class="form-label">Certificado *</label>
                            <input type="text" class="form-control" id="certificado_calibracion" 
                                   name="certificado_calibracion" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="laboratorio_calibracion" class="form-label">Laboratorio *</label>
                            <input type="text" class="form-control" id="laboratorio_calibracion" 
                                   name="laboratorio_calibracion" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="precision" class="form-label">Precisión (%) *</label>
                            <input type="number" step="0.01" min="0" class="form-control" 
                                   id="precision" name="precision" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="desviacion" class="form-label">Desviación (%) *</label>
                            <input type="number" step="0.01" min="0" class="form-control" 
                                   id="desviacion" name="desviacion" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="exitosa" class="form-label">Resultado</label>
                            <select class="form-select" id="exitosa" name="exitosa">
                                <option value="1">Exitosa</option>
                                <option value="0">Fallida</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="archivo_certificado" class="form-label">Archivo del Certificado</label>
                        <input type="file" class="form-control" id="archivo_certificado" 
                               name="archivo_certificado" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" 
                                  rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar Calibración</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        language: 'es',
        autoclose: true
    });
});

function mostrarFormularioCalibracion() {
    new bootstrap.Modal(document.getElementById('calibracionModal')).show();
}
</script>
@endpush