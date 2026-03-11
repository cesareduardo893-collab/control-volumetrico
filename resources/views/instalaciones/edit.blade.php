@extends('layouts.app')

@section('title', 'Editar Instalación')
@section('header', 'Editar Instalación')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="card-title mb-0">Editar Instalación</h5>
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
                
                <form method="POST" action="{{ route('instalaciones.update', $instalacion['id']) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="clave_instalacion" class="form-label">Clave de Instalación</label>
                            <input type="text" class="form-control" id="clave_instalacion" name="clave_instalacion" 
                                   value="{{ old('clave_instalacion', $instalacion['clave_instalacion']) }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre de la Instalación</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="{{ old('nombre', $instalacion['nombre']) }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tipo_instalacion" class="form-label">Tipo de Instalación</label>
                            <select class="form-select" id="tipo_instalacion" name="tipo_instalacion" required>
                                <option value="estacion_servicio" {{ old('tipo_instalacion', $instalacion['tipo_instalacion']) == 'estacion_servicio' ? 'selected' : '' }}>Estación de Servicio</option>
                                <option value="terminal" {{ old('tipo_instalacion', $instalacion['tipo_instalacion']) == 'terminal' ? 'selected' : '' }}>Terminal</option>
                                <option value="planta" {{ old('tipo_instalacion', $instalacion['tipo_instalacion']) == 'planta' ? 'selected' : '' }}>Planta</option>
                                <option value="almacen" {{ old('tipo_instalacion', $instalacion['tipo_instalacion']) == 'almacen' ? 'selected' : '' }}>Almacén</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="estatus" class="form-label">Estatus</label>
                            <select class="form-select" id="estatus" name="estatus" required>
                                <option value="OPERACION" {{ old('estatus', $instalacion['estatus']) == 'OPERACION' ? 'selected' : '' }}>Operación</option>
                                <option value="SUSPENDIDA" {{ old('estatus', $instalacion['estatus']) == 'SUSPENDIDA' ? 'selected' : '' }}>Suspendida</option>
                                <option value="CANCELADA" {{ old('estatus', $instalacion['estatus']) == 'CANCELADA' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="domicilio" class="form-label">Domicilio</label>
                        <textarea class="form-control" id="domicilio" name="domicilio" 
                                  rows="2" required>{{ old('domicilio', $instalacion['domicilio']) }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="codigo_postal" class="form-label">Código Postal</label>
                            <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" 
                                   value="{{ old('codigo_postal', $instalacion['codigo_postal']) }}" maxlength="5" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="municipio" class="form-label">Municipio</label>
                            <input type="text" class="form-control" id="municipio" name="municipio" 
                                   value="{{ old('municipio', $instalacion['municipio']) }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <input type="text" class="form-control" id="estado" name="estado" 
                                   value="{{ old('estado', $instalacion['estado']) }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" 
                                   value="{{ old('telefono', $instalacion['telefono'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email', $instalacion['email'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_apertura" class="form-label">Fecha de Apertura</label>
                            <input type="date" class="form-control datepicker" id="fecha_apertura" 
                                   name="fecha_apertura" value="{{ old('fecha_apertura', $instalacion['fecha_apertura'] ?? '') }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_cierre" class="form-label">Fecha de Cierre</label>
                            <input type="date" class="form-control datepicker" id="fecha_cierre" 
                                   name="fecha_cierre" value="{{ old('fecha_cierre', $instalacion['fecha_cierre'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1"
                                       {{ old('activo', $instalacion['activo']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">Instalación Activa</label>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('instalaciones.show', $instalacion['id']) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Actualizar Instalación
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
});
</script>
@endpush