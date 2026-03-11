@extends('layouts.app')

@section('title', 'Nuevo Medidor')
@section('header', 'Registrar Nuevo Medidor')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Información del Medidor</h5>
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
                
                <form method="POST" action="{{ route('medidores.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="instalacion_id" class="form-label">Instalación *</label>
                            <select class="form-select select2" id="instalacion_id" name="instalacion_id" required>
                                <option value="">Seleccione...</option>
                                @foreach($instalaciones as $instalacion)
                                    <option value="{{ $instalacion['id'] }}" {{ old('instalacion_id') == $instalacion['id'] ? 'selected' : '' }}>
                                        {{ $instalacion['nombre'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="tanque_id" class="form-label">Tanque</label>
                            <select class="form-select select2" id="tanque_id" name="tanque_id">
                                <option value="">Seleccione (opcional)</option>
                                @foreach($tanques as $tanque)
                                    <option value="{{ $tanque['id'] }}" {{ old('tanque_id') == $tanque['id'] ? 'selected' : '' }}>
                                        {{ $tanque['identificador'] }} - {{ $tanque['instalacion']['nombre'] ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Opcional si es medidor de tanque</small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="numero_serie" class="form-label">Número de Serie *</label>
                            <input type="text" class="form-control" id="numero_serie" name="numero_serie" 
                                   value="{{ old('numero_serie') }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="clave" class="form-label">Clave *</label>
                            <input type="text" class="form-control" id="clave" name="clave" 
                                   value="{{ old('clave') }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="modelo" class="form-label">Modelo</label>
                            <input type="text" class="form-control" id="modelo" name="modelo" 
                                   value="{{ old('modelo') }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="fabricante" class="form-label">Fabricante</label>
                            <input type="text" class="form-control" id="fabricante" name="fabricante" 
                                   value="{{ old('fabricante') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="elemento_tipo" class="form-label">Tipo de Elemento *</label>
                            <select class="form-select" id="elemento_tipo" name="elemento_tipo" required>
                                <option value="">Seleccione...</option>
                                <option value="primario" {{ old('elemento_tipo') == 'primario' ? 'selected' : '' }}>Primario</option>
                                <option value="secundario" {{ old('elemento_tipo') == 'secundario' ? 'selected' : '' }}>Secundario</option>
                                <option value="terciario" {{ old('elemento_tipo') == 'terciario' ? 'selected' : '' }}>Terciario</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="tipo_medicion" class="form-label">Tipo de Medición *</label>
                            <select class="form-select" id="tipo_medicion" name="tipo_medicion" required>
                                <option value="">Seleccione...</option>
                                <option value="estatica" {{ old('tipo_medicion') == 'estatica' ? 'selected' : '' }}>Estática</option>
                                <option value="dinamica" {{ old('tipo_medicion') == 'dinamica' ? 'selected' : '' }}>Dinámica</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tecnologia_id" class="form-label">Tecnología</label>
                            <input type="text" class="form-control" id="tecnologia_id" name="tecnologia_id" 
                                   value="{{ old('tecnologia_id') }}" placeholder="Ej: ULTRASONIDO">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="protocolo_comunicacion" class="form-label">Protocolo Comunicación</label>
                            <input type="text" class="form-control" id="protocolo_comunicacion" name="protocolo_comunicacion" 
                                   value="{{ old('protocolo_comunicacion') }}" placeholder="Ej: MODBUS">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="precision" class="form-label">Precisión (%) *</label>
                            <input type="number" step="0.01" min="0" class="form-control" 
                                   id="precision" name="precision" value="{{ old('precision', '0.5') }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="capacidad_maxima" class="form-label">Capacidad Máxima *</label>
                            <div class="input-group">
                                <input type="number" step="0.1" min="0" class="form-control" 
                                       id="capacidad_maxima" name="capacidad_maxima" value="{{ old('capacidad_maxima') }}" required>
                                <span class="input-group-text">L/min</span>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="presion_maxima" class="form-label">Presión Máxima</label>
                            <div class="input-group">
                                <input type="number" step="0.1" min="0" class="form-control" 
                                       id="presion_maxima" name="presion_maxima" value="{{ old('presion_maxima') }}">
                                <span class="input-group-text">psi</span>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="temperatura_maxima" class="form-label">Temperatura Máxima</label>
                            <div class="input-group">
                                <input type="number" step="0.1" min="0" class="form-control" 
                                       id="temperatura_maxima" name="temperatura_maxima" value="{{ old('temperatura_maxima') }}">
                                <span class="input-group-text">°C</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="fecha_instalacion" class="form-label">Fecha de Instalación</label>
                            <input type="date" class="form-control datepicker" id="fecha_instalacion" 
                                   name="fecha_instalacion" value="{{ old('fecha_instalacion') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_ultima_calibracion" class="form-label">Última Calibración</label>
                            <input type="date" class="form-control datepicker" id="fecha_ultima_calibracion" 
                                   name="fecha_ultima_calibracion" value="{{ old('fecha_ultima_calibracion') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_proxima_calibracion" class="form-label">Próxima Calibración</label>
                            <input type="date" class="form-control datepicker" id="fecha_proxima_calibracion" 
                                   name="fecha_proxima_calibracion" value="{{ old('fecha_proxima_calibracion') }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="certificado_calibracion" class="form-label">Certificado Calibración</label>
                            <input type="text" class="form-control" id="certificado_calibracion" name="certificado_calibracion" 
                                   value="{{ old('certificado_calibracion') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="">Seleccione...</option>
                                <option value="OPERATIVO" {{ old('estado', 'OPERATIVO') == 'OPERATIVO' ? 'selected' : '' }}>Operativo</option>
                                <option value="CALIBRACION" {{ old('estado') == 'CALIBRACION' ? 'selected' : '' }}>Calibración</option>
                                <option value="MANTENIMIENTO" {{ old('estado') == 'MANTENIMIENTO' ? 'selected' : '' }}>Mantenimiento</option>
                                <option value="FUERA_SERVICIO" {{ old('estado') == 'FUERA_SERVICIO' ? 'selected' : '' }}>Fuera de Servicio</option>
                                <option value="FALLA_COMUNICACION" {{ old('estado') == 'FALLA_COMUNICACION' ? 'selected' : '' }}>Falla Comunicación</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="form-check mt-4">
                                <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1"
                                       {{ old('activo', '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">Medidor Activo</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" 
                                  rows="3">{{ old('observaciones') }}</textarea>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('medidores.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Medidor
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