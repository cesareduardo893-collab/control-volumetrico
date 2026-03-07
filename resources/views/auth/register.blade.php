<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registro - Sistema de Gestión</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        
        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }
        
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .register-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .register-header i {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .register-body {
            padding: 30px;
        }
        
        .form-control, .form-select {
            border-radius: 25px;
            padding: 10px 15px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: none;
        }
        
        .input-group-text {
            border-radius: 25px 0 0 25px;
            border: 2px solid #e0e0e0;
            border-right: none;
            background: white;
        }
        
        .input-group .form-control {
            border-left: none;
            border-radius: 0 25px 25px 0;
        }
        
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 12px;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
            width: 100%;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .password-requirements {
            font-size: 0.85rem;
            margin-top: 5px;
            padding-left: 20px;
        }
        
        .password-requirements li {
            list-style: none;
            margin-bottom: 2px;
        }
        
        .password-requirements li.valid {
            color: #28a745;
        }
        
        .password-requirements li.invalid {
            color: #dc3545;
        }
        
        .password-requirements i {
            width: 20px;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="register-header">
            <i class="fas fa-user-plus"></i>
            <h2>Crear Cuenta</h2>
            <p class="mb-0">Registro de nuevo usuario</p>
        </div>
        
        <div class="register-body">
            <div id="alertContainer"></div>
            
<form id="registerForm" method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="identificacion" class="form-label">Identificación</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-id-card"></i>
                        </span>
                        <input type="text" 
                               class="form-control @error('identificacion') is-invalid @enderror" 
                               id="identificacion" 
                               name="identificacion" 
                               value="{{ old('identificacion') }}"
                               placeholder="CURP, RFC o ID"
                               required>
                    </div>
                    @error('identificacion')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
<div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombres" class="form-label">Nombre(s)</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('nombres') is-invalid @enderror" 
                                       id="nombres" 
                                       name="nombres" 
                                       value="{{ old('nombres') }}"
                                       required>
                            </div>
                            @error('nombres')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="apellidos" class="form-label">Apellidos</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('apellidos') is-invalid @enderror" 
                                       id="apellidos" 
                                       name="apellidos" 
                                       value="{{ old('apellidos') }}"
                                       required>
                            </div>
                            @error('apellidos')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                
<div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               required>
                    </div>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
<div class="row mb-3">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
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
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
<!-- Requisitos de contraseña -->
                <div class="mb-3">
                    <ul class="password-requirements" id="passwordRequirements">
                        <li id="req-length" class="invalid">
                            <i class="fas fa-times-circle"></i> Mínimo 8 caracteres
                        </li>
                        <li id="req-lowercase" class="invalid">
                            <i class="fas fa-times-circle"></i> Al menos una letra minúscula
                        </li>
                        <li id="req-uppercase" class="invalid">
                            <i class="fas fa-times-circle"></i> Al menos una letra mayúscula
                        </li>
                        <li id="req-number" class="invalid">
                            <i class="fas fa-times-circle"></i> Al menos un número
                        </li>
                        <li id="req-special" class="invalid">
                            <i class="fas fa-times-circle"></i> Al menos un carácter especial
                        </li>
                        <li id="req-match" class="invalid">
                            <i class="fas fa-times-circle"></i> Las contraseñas coinciden
                        </li>
                    </ul>
                </div>
                
<div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            Acepto los <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">términos y condiciones</a>
                        </label>
                    </div>
                </div>
                
<button type="submit" class="btn-register" id="btnRegister" disabled>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="btn-text">Registrarse</span>
                </button>
                
                <div class="login-link">
                    <a href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal Términos y Condiciones -->
    <div class="modal fade" id="termsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Términos y Condiciones</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6>1. Aceptación de términos</h6>
                    <p>Al registrarse en el Sistema de Gestión Volumétrica, usted acepta cumplir con estos términos y condiciones.</p>
                    
                    <h6>2. Uso del sistema</h6>
                    <p>El sistema está diseñado para el control y gestión volumétrica de hidrocarburos. El uso indebido del sistema puede resultar en la suspensión de la cuenta.</p>
                    
                    <h6>3. Privacidad</h6>
                    <p>Sus datos personales serán tratados de acuerdo con nuestra política de privacidad y la normativa aplicable.</p>
                    
                    <h6>4. Responsabilidad</h6>
                    <p>El usuario es responsable de mantener la confidencialidad de sus credenciales de acceso.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $(document).ready(function() {
            // Toggle password visibility
            $('#togglePassword').click(function() {
                togglePassword('password', $(this));
            });
            
            $('#togglePasswordConfirm').click(function() {
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
            
// Validar contraseña
            function validatePassword() {
                var password = $('#password').val();
                var confirm = $('#password_confirmation').val();
                var allValid = true;

                // Longitud
                if (password.length >= 8) {
                    $('#req-length').removeClass('invalid').addClass('valid');
                    $('#req-length i').removeClass('fa-times-circle').addClass('fa-check-circle');
                } else {
                    $('#req-length').removeClass('valid').addClass('invalid');
                    $('#req-length i').removeClass('fa-check-circle').addClass('fa-times-circle');
                    allValid = false;
                }

                // Coincidencia
                if (password && confirm && password === confirm) {
                    $('#req-match').removeClass('invalid').addClass('valid');
                    $('#req-match i').removeClass('fa-times-circle').addClass('fa-check-circle');
                } else {
                    $('#req-match').removeClass('valid').addClass('invalid');
                    $('#req-match i').removeClass('fa-check-circle').addClass('fa-times-circle');
                    allValid = false;
                }

                return allValid;
            }
            
            $('#password, #password_confirmation').on('input', function() {
                var valid = validatePassword();
                $('#btnRegister').prop('disabled', !valid || !$('#terms').is(':checked'));
            });
            
            $('#terms').change(function() {
                var valid = validatePassword();
                $('#btnRegister').prop('disabled', !valid || !$(this).is(':checked'));
            });
            
            // Envío del formulario
            $('#registerForm').on('submit', function(e) {
                e.preventDefault();
                
                if (!validatePassword()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'La contraseña no cumple con los requisitos de seguridad'
                    });
                    return;
                }
                
                const $form = $(this);
                const $btn = $('#btnRegister');
                const $spinner = $btn.find('.spinner-border');
                const $btnText = $btn.find('.btn-text');
                
                $btn.prop('disabled', true);
                $spinner.removeClass('d-none');
                $btnText.text('Registrando...');
                
$.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Registro exitoso!',
                                text: 'Por favor inicia sesión con tus credenciales',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(function() {
                                window.location.href = '/login';
                            });
                        } else {
                            showError(response.message || 'Error en el registro');
                            resetButton();
                        }
                    },
                    error: function(xhr) {
                        let message = 'Error en el servidor';
                        
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            } else if (xhr.responseJSON.errors) {
                                const errors = xhr.responseJSON.errors;
                                message = Object.values(errors).flat().join('<br>');
                            }
                        }
                        
                        showError(message);
                        resetButton();
                    }
                });
            });
            
            function showError(message) {
                $('#alertContainer').html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
            }
            
            function resetButton() {
                const $btn = $('#btnRegister');
                const $spinner = $btn.find('.spinner-border');
                const $btnText = $btn.find('.btn-text');
                
                $btn.prop('disabled', false);
                $spinner.addClass('d-none');
                $btnText.text('Registrarse');
            }
        });
    </script>
</body>
</html>