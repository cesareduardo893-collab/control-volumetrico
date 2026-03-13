@extends('layouts.app')

@section('title', 'Editar Rol')
@section('header', 'Editar Rol')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Información del Rol</h5>
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
                
                <form method="POST" action="{{ route('roles.update', $role['id']) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="nombre" class="form-label">Nombre del Rol *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="{{ old('nombre', $role['nombre']) }}" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="nivel_jerarquico" class="form-label">Nivel Jerárquico *</label>
                            <input type="number" class="form-control" id="nivel_jerarquico" name="nivel_jerarquico" 
                                   value="{{ old('nivel_jerarquico', $role['nivel_jerarquico']) }}" min="1" max="100" required>
                            <small class="text-muted">1-100 (mayor = más alto)</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" 
                                  rows="3">{{ old('descripcion', $role['descripcion'] ?? '') }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="es_administrador" 
                                       name="es_administrador" value="1" 
                                       {{ old('es_administrador', $role['es_administrador'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="es_administrador">
                                    Este rol es de administrador
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="activo" 
                                       name="activo" value="1" 
                                       {{ old('activo', $role['activo'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">
                                    Rol activo
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h6 class="mb-3">Permisos del Rol</h6>
                    
                    <div class="row">
                        @foreach($modulos as $modulo)
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-header bg-light py-2">
                                        <strong>{{ $modulo['modulo'] }}</strong>
                                    </div>
                                    <div class="card-body p-2">
                                        @foreach($modulo['permisos'] as $permiso)
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" 
                                                       id="permiso_{{ $permiso['id'] }}" 
                                                       name="permisos[]" 
                                                       value="{{ $permiso['id'] }}"
                                                       {{ in_array($permiso['id'], old('permisos', $permisosActuales)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permiso_{{ $permiso['id'] }}">
                                                    {{ $permiso['name'] }}
                                                    <small class="text-muted d-block">{{ $permiso['slug'] }}</small>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('roles.show', $role['id']) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Actualizar Rol
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
    // Toggle checkboxes when clicking on the label area
    $('.form-check-label').on('click', function(e) {
        if ($(e.target).is('small')) {
            e.preventDefault();
            $(this).prev('.form-check-input').trigger('click');
        }
    });
    
    // Toggle all checkboxes in a module when clicking the header
    $('.card-header').on('click', function() {
        let checkboxes = $(this).parent().find('.form-check-input');
        let allChecked = checkboxes.filter(':checked').length === checkboxes.length;
        checkboxes.prop('checked', !allChecked);
    });
});
</script>
@endpush
