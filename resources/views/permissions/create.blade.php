@extends('layouts.app')

@section('title', 'Nuevo Permiso')
@section('header', 'Crear Nuevo Permiso')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Información del Permiso</h5>
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
                
                <form method="POST" action="{{ route('permissions.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Permiso *</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name') }}" required>
                        <small class="text-muted">Ej: Ver usuarios, Crear roles, etc.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug *</label>
                        <input type="text" class="form-control" id="slug" name="slug" 
                               value="{{ old('slug') }}" required>
                        <small class="text-muted">Identificador único. Ej: users.view, roles.create</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modulo" class="form-label">Módulo *</label>
                        <input type="text" class="form-control" id="modulo" name="modulo" 
                               value="{{ old('modulo') }}" required>
                        <small class="text-muted">Ej: usuarios, roles, instalaciones, etc.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="3">{{ old('description') }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1"
                                   {{ old('activo', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">Permiso Activo</label>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('permissions.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Crear Permiso
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
    // Generar slug automáticamente desde el nombre
    $('#name').on('blur', function() {
        if ($('#slug').val() === '') {
            let name = $(this).val();
            let slug = name.toLowerCase()
                .replace(/[áäàâ]/g, 'a')
                .replace(/[éëèê]/g, 'e')
                .replace(/[íïìî]/g, 'i')
                .replace(/[óöòô]/g, 'o')
                .replace(/[úüùû]/g, 'u')
                .replace(/[^a-z0-9\s]/g, '')
                .replace(/\s+/g, '.');
            $('#slug').val(slug);
        }
    });
});
</script>
@endpush