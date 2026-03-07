@extends('layouts.app')

@section('title', 'Editar Rol')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Editar Rol: {{ $role['display_name'] }}</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('roles.update', $role['id']) }}" id="roleForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Información Básica -->
                    <h5 class="mb-3">Información del Rol</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $role['name']) }}" 
                                       required>
                                <small class="text-muted">Identificador único (ej: admin, supervisor)</small>
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
                                       value="{{ old('display_name', $role['display_name']) }}" 
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
                                          rows="3">{{ old('description', $role['description']) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Permisos -->
                    <h5 class="mb-3 mt-4">Permisos</h5>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="selectAll">
                                            Seleccionar Todos
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">
                                            Deseleccionar Todos
                                        </button>
                                    </div>
                                    
                                    <div class="row">
                                        @foreach($permisos as $permiso)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input type="checkbox" 
                                                       class="form-check-input permiso-checkbox" 
                                                       name="permission_ids[]" 
                                                       value="{{ $permiso['id'] }}"
                                                       id="permiso_{{ $permiso['id'] }}"
                                                       {{ in_array($permiso['id'], old('permission_ids', collect($role['permissions'] ?? [])->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permiso_{{ $permiso['id'] }}">
                                                    {{ $permiso['display_name'] }}
                                                </label>
                                                <br>
                                                <small class="text-muted">{{ $permiso['name'] }}</small>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar
                            </button>
                            <a href="{{ route('roles.show', $role['id']) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
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
            .replace(/[^a-z0-9]/g, '_')
            .replace(/_+/g, '_')
            .replace(/^_|_$/g, '');
        
        if ($('#name').val() === '{{ $role["name"] }}') {
            $('#name').val(name);
        }
    });
    
    // Seleccionar/Deseleccionar todos los permisos
    $('#selectAll').click(function() {
        $('.permiso-checkbox').prop('checked', true);
    });
    
    $('#deselectAll').click(function() {
        $('.permiso-checkbox').prop('checked', false);
    });
    
    // Validar que el nombre solo contenga letras minúsculas, números y guiones bajos
    $('#name').on('input', function() {
        var value = $(this).val();
        var valid = value.replace(/[^a-z0-9_]/g, '');
        $(this).val(valid);
    });
});
</script>
@endpush