@extends('layouts.app')

@section('title', 'Cambiar Contraseña')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Cambiar Contraseña</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.change-password') }}" id="passwordForm">
                    @csrf
                    
                    <!-- Contraseña Actual -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="current_password" class="form-label">Contraseña Actual <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('current_password') is-invalid @enderror" 
                                           id="current_password" 
                                           name="current_password" 
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleCurrent">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Nueva Contraseña -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-label">Nueva Contraseña <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleNew">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="password-strength" class="mt-2"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('password_confirmation') is-invalid @enderror" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirm">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Requisitos de contraseña -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">Requisitos de la contraseña:</h6>
                                <ul class="mb-0" id="password-requirements">
                                    <li id="req-length" class="text-danger">
                                        <i class="fas fa-times-circle"></i> Mínimo 8 caracteres
                                    </li>
                                    <li id="req-lowercase" class="text-danger">
                                        <i class="fas fa-times-circle"></i> Al menos una letra minúscula
                                    </li>
                                    <li id="req-uppercase" class="text-danger">
                                        <i class="fas fa-times-circle"></i> Al menos una letra mayúscula
                                    </li>
                                    <li id="req-number" class="text-danger">
                                        <i class="fas fa-times-circle"></i> Al menos un número
                                    </li>
                                    <li id="req-special" class="text-danger">
                                        <i class="fas fa-times-circle"></i> Al menos un carácter especial
                                    </li>
                                    <li id="req-match" class="text-danger">
                                        <i class="fas fa-times-circle"></i> Las contraseñas coinciden
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                <i class="fas fa-key"></i> Cambiar Contraseña
                            </button>
                            <a href="{{ route('profile.edit') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver al Perfil
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
    // Toggle password visibility
    $('#toggleCurrent').click(function() {
        togglePassword('current_password', $(this));
    });
    
    $('#toggleNew').click(function() {
        togglePassword('password', $(this));
    });
    
    $('#toggleConfirm').click(function() {
        togglePassword('password_confirmation', $(this));
    });
    
    function togglePassword(inputId, button) {
        var input = $('#' + inputId);
        var icon = button.find('i');
        
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    }
    
    // Validar fortaleza de contraseña
    $('#password, #password_confirmation').on('input', function() {
        var password = $('#password').val();
        var confirm = $('#password_confirmation').val();
        var allValid = true;
        
        // Longitud mínima
        if (password.length >= 8) {
            $('#req-length').removeClass('text-danger').addClass('text-success');
            $('#req-length i').removeClass('fa-times-circle').addClass('fa-check-circle');
        } else {
            $('#req-length').removeClass('text-success').addClass('text-danger');
            $('#req-length i').removeClass('fa-check-circle').addClass('fa-times-circle');
            allValid = false;
        }
        
        // Minúsculas
        if (/[a-z]/.test(password)) {
            $('#req-lowercase').removeClass('text-danger').addClass('text-success');
            $('#req-lowercase i').removeClass('fa-times-circle').addClass('fa-check-circle');
        } else {
            $('#req-lowercase').removeClass('text-success').addClass('text-danger');
            $('#req-lowercase i').removeClass('fa-check-circle').addClass('fa-times-circle');
            allValid = false;
        }
        
        // Mayúsculas
        if (/[A-Z]/.test(password)) {
            $('#req-uppercase').removeClass('text-danger').addClass('text-success');
            $('#req-uppercase i').removeClass('fa-times-circle').addClass('fa-check-circle');
        } else {
            $('#req-uppercase').removeClass('text-success').addClass('text-danger');
            $('#req-uppercase i').removeClass('fa-check-circle').addClass('fa-times-circle');
            allValid = false;
        }
        
        // Números
        if (/[0-9]/.test(password)) {
            $('#req-number').removeClass('text-danger').addClass('text-success');
            $('#req-number i').removeClass('fa-times-circle').addClass('fa-check-circle');
        } else {
            $('#req-number').removeClass('text-success').addClass('text-danger');
            $('#req-number i').removeClass('fa-check-circle').addClass('fa-times-circle');
            allValid = false;
        }
        
        // Caracteres especiales
        if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
            $('#req-special').removeClass('text-danger').addClass('text-success');
            $('#req-special i').removeClass('fa-times-circle').addClass('fa-check-circle');
        } else {
            $('#req-special').removeClass('text-success').addClass('text-danger');
            $('#req-special i').removeClass('fa-check-circle').addClass('fa-times-circle');
            allValid = false;
        }
        
        // Coincidencia
        if (password && confirm && password === confirm) {
            $('#req-match').removeClass('text-danger').addClass('text-success');
            $('#req-match i').removeClass('fa-times-circle').addClass('fa-check-circle');
        } else {
            $('#req-match').removeClass('text-success').addClass('text-danger');
            $('#req-match i').removeClass('fa-check-circle').addClass('fa-times-circle');
            allValid = false;
        }
        
        // Habilitar/deshabilitar botón
        $('#submitBtn').prop('disabled', !allValid);
    });
});
</script>
@endpush