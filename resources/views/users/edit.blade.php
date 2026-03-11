@extends('layouts.app')

@section('title', 'Editar Usuario')
@section('header', 'Editar Usuario')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="card-title mb-0">Editar Usuario</h5>
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
                
                <form method="POST" action="{{ route('users.update', $user['id']) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="identificacion" class="form-label">Identificación</label>
                            <input type="text" class="form-control" id="identificacion" name="identificacion" 
                                   value="{{ old('identificacion', $user['identificacion'] ?? '') }}" maxlength="18" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email', $user['email']) }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombres" class="form-label">Nombres</label>
                            <input type="text" class="form-control" id="nombres" name="nombres" 
                                   value="{{ old('nombres', $user['nombres']) }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="apellidos" class="form-label">Apellidos</label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos" 
                                   value="{{ old('apellidos', $user['apellidos']) }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" 
                                   value="{{ old('telefono', $user['telefono'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" 
                                   value="{{ old('direccion', $user['direccion'] ?? '') }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="roles" class="form-label">Roles</label>
                        <select class="form-select select2" id="roles" name="roles[]" multiple>
                            @foreach($roles as $rol)
                                @php
                                    $selected = in_array($rol['id'], old('roles', collect($user['roles'] ?? [])->pluck('id')->toArray()));
                                @endphp
                                <option value="{{ $rol['id'] }}" {{ $selected ? 'selected' : '' }}>
                                    {{ $rol['nombre'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1"
                                       {{ old('activo', $user['activo']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">Usuario Activo</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="force_password_change" 
                                       name="force_password_change" value="1"
                                       {{ old('force_password_change', $user['force_password_change'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="force_password_change">
                                    Forzar cambio de contraseña en el próximo inicio
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        Si desea cambiar la contraseña del usuario, utilice la opción de "Cambiar Contraseña" en el perfil del usuario.
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('users.show', $user['id']) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Actualizar Usuario
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
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
});
</script>
@endpush