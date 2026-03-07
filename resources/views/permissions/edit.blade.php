@extends('layouts.app')

@section('title', 'Editar Permiso')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Editar Permiso: {{ $permiso['display_name'] }}</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('permissions.update', $permiso['id']) }}" id="permissionForm">
                    @csrf
                    @method('PUT')
                    
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
                                       value="{{ old('name', $permiso['name']) }}" 
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
                                       value="{{ old('display_name', $permiso['display_name']) }}" 
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
                                          rows="3">{{ old('description', $permiso['description']) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar
                            </button>
                            <a href="{{ route('permissions.show', $permiso['id']) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> Ver
                            </a>
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
        
        if ($('#name').val() === '{{ $permiso["name"] }}') {
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