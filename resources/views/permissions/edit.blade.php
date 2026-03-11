@extends('layouts.app')

@section('title', 'Editar Permiso')
@section('header', 'Editar Permiso')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="card-title mb-0">Editar Permiso</h5>
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
                
                <form method="POST" action="{{ route('permissions.update', $permiso['id']) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Permiso *</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name', $permiso['name']) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug *</label>
                        <input type="text" class="form-control" id="slug" name="slug" 
                               value="{{ old('slug', $permiso['slug']) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modulo" class="form-label">Módulo *</label>
                        <input type="text" class="form-control" id="modulo" name="modulo" 
                               value="{{ old('modulo', $permiso['modulo'] ?? '') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="3">{{ old('description', $permiso['description'] ?? '') }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1"
                                   {{ old('activo', $permiso['activo']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">Permiso Activo</label>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('permissions.show', $permiso['id']) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Actualizar Permiso
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection