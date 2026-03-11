@extends('layouts.app')

@section('title', 'Nueva Instalación')
@section('header', 'Registrar Nueva Instalación')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Información de la Instalación</h5>
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
                
                <form method="POST" action="{{ route('instalaciones.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contribuyente_id" class="form-label">Contribuyente *</label>
                            <select class="form-select select2" id="contribuyente_id" name="contribuyente_id" required>
                                <option value="">Seleccione...</option>
                                @foreach($contribuyentes as $contribuyente)
                                    <option value="{{ $contribuyente['id'] }}" {{ old('contribuyente_id') == $contribuyente['id'] ? 'selected' : '' }}>
                                        {{ $contribuyente['razon_social'] }} ({{ $contribuyente['rfc'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="clave_instalacion" class="form-label">Clave de Instalación *</label>
                            <input type="text" class="form-control" id="clave_instalacion" name="clave_instalacion" 
                                   value="{{ old('clave_instalacion') }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre de la Instalación *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="{{ old('nombre') }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="tipo_instalacion" class="form-label">Tipo de Instalación *</label>
                            <select class="form-select" id="tipo_instalacion" name="tipo_instalacion" required>
                                <option value="">Seleccione...</option>
                                <option value="estacion_servicio" {{ old('tipo_instalacion') == 'estacion_servicio' ? 'selected' : '' }}>Estación de Servicio</option>
                                <option value="terminal" {{ old('tipo_instalacion') == 'terminal' ? 'selected' : '' }}>Terminal</option>
                                <option value="planta" {{ old('tipo_instalacion') == 'planta' ? 'selected' : '' }}>Planta</option>
                                <option value="almacen" {{ old('tipo_instalacion') == 'almacen' ? 'selected' : '' }}>Almacén</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="domicilio" class="form-label">Domicilio *</label>
                        <textarea class="form-control" id="domicilio" name="domicilio" 
                                  rows="2" required>{{ old('domicilio') }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="codigo_postal" class="form-label">Código Postal *</label>
                            <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" 
                                   value="{{ old('codigo_postal') }}" maxlength="5" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="municipio" class="form-label">Municipio *</label>
                            <input type="text" class="form-control" id="municipio" name="municipio" 
                                   value="{{ old('municipio') }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <input type="text" class="form-control" id="estado" name="estado" 
                                   value="{{ old('estado') }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" 
                                   value="{{ old('telefono') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="estatus" class="form-label">Estatus *</label>
                            <select class="form-select" id="estatus" name="estatus" required>
                                <option value="">Seleccione...</option>
                                <option value="OPERACION" {{ old('estatus', 'OPERACION') == 'OPERACION' ? 'selected' : '' }}>Operación</option>
                                <option value="SUSPENDIDA" {{ old('estatus') == 'SUSPENDIDA' ? 'selected' : '' }}>Suspendida</option>
                                <option value="CANCELADA" {{ old('estatus') == 'CANCELADA' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_apertura" class="form-label">Fecha de Apertura</label>
                            <input type="date" class="form-control datepicker" id="fecha_apertura" 
                                   name="fecha_apertura" value="{{ old('fecha_apertura') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="fecha_cierre" class="form-label">Fecha de Cierre</label>
                            <input type="date" class="form-control datepicker" id="fecha_cierre" 
                                   name="fecha_cierre" value="{{ old('fecha_cierre') }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1"
                                   {{ old('activo', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">Instalación Activa</label>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('instalaciones.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Instalación
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