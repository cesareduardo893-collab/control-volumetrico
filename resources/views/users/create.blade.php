@extends('layouts.app')

@section('title', 'Nuevo Usuario')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Nuevo Usuario</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('usuarios.store') }}" id="userForm">
                    @csrf
                    
                    <!-- Información Personal -->
                    <h5 class="mb-3">Información Personal</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contraseña -->
                    <h5 class="mb-3 mt-4">Contraseña</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Mínimo 8 caracteres</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('password_confirmation') is-invalid @enderror" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Roles y Permisos -->
                    <h5 class="mb-3 mt-4">Roles y Permisos</h5>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="roles" class="form-label">Roles</label>
                                <select class="form-select select2 @error('roles') is-invalid @enderror" 
                                        id="roles" 
                                        name="roles[]" 
                                        multiple>
                                    @foreach($roles as $role)
                                        <option value="{{ $role['id'] }}" {{ in_array($role['id'], old('roles', [])) ? 'selected' : '' }}>
                                            {{ $role['display_name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('roles')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Seleccione uno o más roles</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información de Contacto -->
                    <h5 class="mb-3 mt-4">Información de Contacto</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" 
                                       class="form-control @error('telefono') is-invalid @enderror" 
                                       id="telefono" 
                                       name="telefono" 
                                       value="{{ old('telefono') }}">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" 
                                       class="form-control @error('direccion') is-invalid @enderror" 
                                       id="direccion" 
                                       name="direccion" 
                                       value="{{ old('direccion') }}">
                                @error('direccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estado -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Usuario Activo</label>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
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
    // Inicializar Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Seleccione roles...'
    });
    
    // Toggle password visibility
    $('#togglePassword').click(function() {
        var passwordInput = $('#password');
        var icon = $(this).find('i');
        
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
    
    $('#togglePasswordConfirm').click(function() {
        var passwordInput = $('#password_confirmation');
        var icon = $(this).find('i');
        
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
    
    // Validar fortaleza de contraseña
    $('#password').on('input', function() {
        var password = $(this).val();
        var strength = 0;
        
        if (password.length >= 8) strength += 1;
        if (password.match(/[a-z]+/)) strength += 1;
        if (password.match(/[A-Z]+/)) strength += 1;
        if (password.match(/[0-9]+/)) strength += 1;
        if (password.match(/[$@#&!]+/)) strength += 1;
        
        var strengthText = '';
        var strengthClass = '';
        
        switch(strength) {
            case 0:
            case 1:
                strengthText = 'Muy débil';
                strengthClass = 'bg-danger';
                break;
            case 2:
                strengthText = 'Débil';
                strengthClass = 'bg-warning';
                break;
            case 3:
                strengthText = 'Media';
                strengthClass = 'bg-info';
                break;
            case 4:
                strengthText = 'Fuerte';
                strengthClass = 'bg-primary';
                break;
            case 5:
                strengthText = 'Muy fuerte';
                strengthClass = 'bg-success';
                break;
        }
        
        var strengthHtml = '<div class="progress mt-2" style="height: 5px;">' +
            '<div class="progress-bar ' + strengthClass + '" role="progressbar" style="width: ' + (strength * 20) + '%"></div>' +
            '</div>' +
            '<small class="text-muted">' + strengthText + '</small>';
        
        if ($('#password-strength').length) {
            $('#password-strength').html(strengthHtml);
        } else {
            $('#password').after('<div id="password-strength">' + strengthHtml + '</div>');
        }
    });
});
</script>
@endpush