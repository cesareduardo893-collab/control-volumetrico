@extends('layouts.app')

@section('title', 'Nuevo Permiso')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Nuevo Permiso</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('permissions.store') }}" id="permissionForm">
                    @csrf
                    
                    <!-- Información Básica -->
                    <h5 class="mb-3">Información del Permiso</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required>
                                <small class="text-muted">Identificador único (ej: users.create, roles.edit)</small>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="display_name" class="form-label">Nombre para mostrar <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('display_name') is-invalid @enderror" 
                                       id="display_name" 
                                       name="display_name" 
                                       value="{{ old('display_name') }}" 
                                       required>
                                @error('display_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description" class="form-label">Descripción</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sugerencias de nombres de permisos -->
                    <h5 class="mb-3 mt-4">Sugerencias</h5>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-2">Formatos comunes para nombres de permisos:</p>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h6>Operaciones CRUD:</h6>
                                            <ul class="list-unstyled">
                                                <li><code>users.view</code> - Ver usuarios</li>
                                                <li><code>users.create</code> - Crear usuarios</li>
                                                <li><code>users.edit</code> - Editar usuarios</li>
                                                <li><code>users.delete</code> - Eliminar usuarios</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-4">
                                            <h6>Módulos específicos:</h6>
                                            <ul class="list-unstyled">
                                                <li><code>reports.generate</code> - Generar reportes</li>
                                                <li><code>reports.export</code> - Exportar reportes</li>
                                                <li><code>config.manage</code> - Gestionar configuración</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-4">
                                            <h6>Acciones especiales:</h6>
                                            <ul class="list-unstyled">
                                                <li><code>alarms.acknowledge</code> - Atender alarmas</li>
                                                <li><code>validate.records</code> - Validar registros</li>
                                                <li><code>backup.create</code> - Crear backups</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                            <a href="{{ route('permissions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
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
    // Generar name basado en display_name
    $('#display_name').on('input', function() {
        var displayName = $(this).val();
        var name = displayName.toLowerCase()
            .replace(/[áàäâ]/g, 'a')
            .replace(/[éèëê]/g, 'e')
            .replace(/[íìïî]/g, 'i')
            .replace(/[óòöô]/g, 'o')
            .replace(/[úùüû]/g, 'u')
            .replace(/[^a-z0-9]/g, '.')
            .replace(/\.+/g, '.')
            .replace(/^\.|\.$/g, '');
        
        if (!$('#name').val()) {
            $('#name').val(name);
        }
    });
    
    // Validar que el nombre solo contenga letras minúsculas, números, puntos y guiones bajos
    $('#name').on('input', function() {
        var value = $(this).val();
        var valid = value.replace(/[^a-z0-9._]/g, '');
        $(this).val(valid);
    });
});
</script>
@endpush