@extends('layouts.app')

@section('title', 'Editar Medidor')
@section('header', 'Editar Medidor')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="card-title mb-0">Editar Medidor</h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('medidores.update', $medidor['id']) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="numero_serie" class="form-label">Número de Serie</label>
                            <input type="text" class="form-control" id="numero_serie" 
                                   value="{{ $medidor['numero_serie'] }}" disabled readonly>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="clave" class="form-label">Clave</label>
                            <input type="text" class="form-control" id="clave" 
                                   value="{{ $medidor['clave'] }}" disabled readonly>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="modelo" class="form-label">Modelo</label>
                            <input type="text" class="form-control" id="modelo" name="modelo" 
                                   value="{{ old('modelo', $medidor['modelo'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fabricante" class="form-label">Fabricante</label>
                            <input type="text" class="form-control" id="fabricante" name="fabricante" 
                                   value="{{ old('fabricante', $medidor['fabricante'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="tecnologia_id" class="form-label">Tecnología</label>
                            <input type="text" class="form-control" id="tecnologia_id" name="tecnologia_id" 
                                   value="{{ old('tecnologia_id', $medidor['tecnologia_id'] ?? '') }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="protocolo_comunicacion" class="form-label">Protocolo Comunicación</label>
                            <input type="text" class="form-control" id="protocolo_comunicacion" name="protocolo_comunicacion" 
                                   value="{{ old('protocolo_comunicacion', $medidor['protocolo_comunicacion'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="precision" class="form-label">Precisión (%)</label>
                            <input type="number" step="0.01" min="0" class="form-control" 
                                   id="precision" name="precision" value="{{ old('precision', $medidor['precision']) }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="capacidad_maxima" class="form-label">Capacidad Máxima (L/min)</label>
                            <input type="number" step="0.1" min="0" class="form-control" 
                                   id="capacidad_maxima" name="capacidad_maxima" value="{{ old('capacidad_maxima', $medidor['capacidad_maxima']) }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="presion_maxima" class="form-label">Presión Máxima (psi)</label>
                            <input type="number" step="0.1" min="0" class="form-control" 
                                   id="presion_maxima" name="presion_maxima" value="{{ old('presion_maxima', $medidor['presion_maxima'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="temperatura_maxima" class="form-label">Temperatura Máxima (°C)</label>
                            <input type="number" step="0.1" min="0" class="form-control" 
                                   id="temperatura_maxima" name="temperatura_maxima" value="{{ old('temperatura_maxima', $medidor['temperatura_maxima'] ?? '') }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tanque_id" class="form-label">Tanque Asignado</label>
                            <select class="form-select select2" id="tanque_id" name="tanque_id">
                                <option value="">Sin asignar</option>
                                @foreach($tanques as $tanque)
                                    <option value="{{ $tanque['id'] }}" 
                                        {{ old('tanque_id', $medidor['tanque_id']) == $tanque['id'] ? 'selected' : '' }}>
                                        {{ $tanque['identificador'] }} - {{ $tanque['instalacion']['nombre'] ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="OPERATIVO" {{ old('estado', $medidor['estado']) == 'OPERATIVO' ? 'selected' : '' }}>Operativo</option>
                                <option value="CALIBRACION" {{ old('estado', $medidor['estado']) == 'CALIBRACION' ? 'selected' : '' }}>Calibración</option>
                                <option value="MANTENIMIENTO" {{ old('estado', $medidor['estado']) == 'MANTENIMIENTO' ? 'selected' : '' }}>Mantenimiento</option>
                                <option value="FUERA_SERVICIO" {{ old('estado', $medidor['estado']) == 'FUERA_SERVICIO' ? 'selected' : '' }}>Fuera de Servicio</option>
                                <option value="FALLA_COMUNICACION" {{ old('estado', $medidor['estado']) == 'FALLA_COMUNICACION' ? 'selected' : '' }}>Falla Comunicación</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="form-check mt-4">
                                <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1"
                                       {{ old('activo', $medidor['activo']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">Medidor Activo</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="fecha_ultima_calibracion" class="form-label">Última Calibración</label>
                            <input type="date" class="form-control datepicker" id="fecha_ultima_calibracion" 
                                   name="fecha_ultima_calibracion" value="{{ old('fecha_ultima_calibracion', $medidor['fecha_ultima_calibracion'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_proxima_calibracion" class="form-label">Próxima Calibración</label>
                            <input type="date" class="form-control datepicker" id="fecha_proxima_calibracion" 
                                   name="fecha_proxima_calibracion" value="{{ old('fecha_proxima_calibracion', $medidor['fecha_proxima_calibracion'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="certificado_calibracion" class="form-label">Certificado</label>
                            <input type="text" class="form-control" id="certificado_calibracion" name="certificado_calibracion" 
                                   value="{{ old('certificado_calibracion', $medidor['certificado_calibracion'] ?? '') }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" 
                                  rows="3">{{ old('observaciones', $medidor['observaciones'] ?? '') }}</textarea>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('medidores.show', $medidor['id']) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Actualizar Medidor
                        </button>
                    </div>
                </form>
            </div>
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
    
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
});
</script>
@endpush