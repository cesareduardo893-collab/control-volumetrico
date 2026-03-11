@extends('layouts.app')

@section('title', 'Nuevo Dispensario')
@section('header', 'Registrar Nuevo Dispensario')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Información del Dispensario</h5>
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
                
                <form method="POST" action="{{ route('dispensarios.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="instalacion_id" class="form-label">Instalación *</label>
                            <select class="form-select select2" id="instalacion_id" name="instalacion_id" required>
                                <option value="">Seleccione...</option>
                                @foreach($instalaciones as $instalacion)
                                    <option value="{{ $instalacion['id'] }}" {{ old('instalacion_id') == $instalacion['id'] ? 'selected' : '' }}>
                                        {{ $instalacion['nombre'] }} ({{ $instalacion['clave_instalacion'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="clave" class="form-label">Clave *</label>
                            <input type="text" class="form-control" id="clave" name="clave" 
                                   value="{{ old('clave') }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" 
                                  rows="2">{{ old('descripcion') }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="modelo" class="form-label">Modelo</label>
                            <input type="text" class="form-control" id="modelo" name="modelo" 
                                   value="{{ old('modelo') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="fabricante" class="form-label">Fabricante</label>
                            <input type="text" class="form-control" id="fabricante" name="fabricante" 
                                   value="{{ old('fabricante') }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="numero_serie" class="form-label">Número de Serie</label>
                            <input type="text" class="form-control" id="numero_serie" name="numero_serie" 
                                   value="{{ old('numero_serie') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="">Seleccione...</option>
                                <option value="OPERATIVO" {{ old('estado', 'OPERATIVO') == 'OPERATIVO' ? 'selected' : '' }}>Operativo</option>
                                <option value="MANTENIMIENTO" {{ old('estado') == 'MANTENIMIENTO' ? 'selected' : '' }}>Mantenimiento</option>
                                <option value="FUERA_SERVICIO" {{ old('estado') == 'FUERA_SERVICIO' ? 'selected' : '' }}>Fuera de Servicio</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="fecha_instalacion" class="form-label">Fecha de Instalación</label>
                            <input type="date" class="form-control datepicker" id="fecha_instalacion" 
                                   name="fecha_instalacion" value="{{ old('fecha_instalacion') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_ultimo_mantenimiento" class="form-label">Último Mantenimiento</label>
                            <input type="date" class="form-control datepicker" id="fecha_ultimo_mantenimiento" 
                                   name="fecha_ultimo_mantenimiento" value="{{ old('fecha_ultimo_mantenimiento') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_proximo_mantenimiento" class="form-label">Próximo Mantenimiento</label>
                            <input type="date" class="form-control datepicker" id="fecha_proximo_mantenimiento" 
                                   name="fecha_proximo_mantenimiento" value="{{ old('fecha_proximo_mantenimiento') }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="capacidad_maxima" class="form-label">Capacidad Máxima (L/min)</label>
                            <input type="number" step="0.1" min="0" class="form-control" 
                                   id="capacidad_maxima" name="capacidad_maxima" value="{{ old('capacidad_maxima') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="presion_operacion" class="form-label">Presión de Operación (psi)</label>
                            <input type="number" step="0.1" min="0" class="form-control" 
                                   id="presion_operacion" name="presion_operacion" value="{{ old('presion_operacion') }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1"
                                   {{ old('activo', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">Dispensario Activo</label>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dispensarios.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Dispensario
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