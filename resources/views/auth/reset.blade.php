<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Restablecer Contraseña - Sistema de Gestión</title>
    
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
        }
        
        .reset-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 450px;
            width: 90%;
        }
        
        .reset-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .reset-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .reset-header i {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .reset-body {
            padding: 30px;
        }
        
        .form-control {
            border-radius: 25px;
            padding: 12px 20px;
            height: auto;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }
        
        .form-control:focus {
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
        
        .btn-reset {
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
        
        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .back-link a:hover {
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
    <div class="reset-card">
        <div class="reset-header">
            <i class="fas fa-lock-open"></i>
            <h2>Restablecer Contraseña</h2>
            <p class="mb-0">Ingresa tu nueva contraseña</p>
        </div>
        
        <div class="reset-body">
            <div id="alertContainer"></div>
            
            <form id="resetForm" method="POST" action="{{ route('password.update') }}">
                @csrf
                
                <input type="hidden" name="token" value="{{ $token ?? request()->route('token') }}">
                
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
                               value="{{ old('email', $email ?? '') }}"
                               required 
                               readonly>
                    </div>
                    @error('email')
                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Nueva Contraseña</label>
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
                
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
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
                
                <button type="submit" class="btn-reset" id="btnReset" disabled>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="btn-text">Restablecer Contraseña</span>
                </button>
                
                <div class="back-link">
                    <a href="{{ route('login') }}">
                        <i class="fas fa-arrow-left me-2"></i>Volver al inicio de sesión
                    </a>
                </div>
            </form>
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
                
                // Minúsculas
                if (/[a-z]/.test(password)) {
                    $('#req-lowercase').removeClass('invalid').addClass('valid');
                    $('#req-lowercase i').removeClass('fa-times-circle').addClass('fa-check-circle');
                } else {
                    $('#req-lowercase').removeClass('valid').addClass('invalid');
                    $('#req-lowercase i').removeClass('fa-check-circle').addClass('fa-times-circle');
                    allValid = false;
                }
                
                // Mayúsculas
                if (/[A-Z]/.test(password)) {
                    $('#req-uppercase').removeClass('invalid').addClass('valid');
                    $('#req-uppercase i').removeClass('fa-times-circle').addClass('fa-check-circle');
                } else {
                    $('#req-uppercase').removeClass('valid').addClass('invalid');
                    $('#req-uppercase i').removeClass('fa-check-circle').addClass('fa-times-circle');
                    allValid = false;
                }
                
                // Números
                if (/[0-9]/.test(password)) {
                    $('#req-number').removeClass('invalid').addClass('valid');
                    $('#req-number i').removeClass('fa-times-circle').addClass('fa-check-circle');
                } else {
                    $('#req-number').removeClass('valid').addClass('invalid');
                    $('#req-number i').removeClass('fa-check-circle').addClass('fa-times-circle');
                    allValid = false;
                }
                
                // Caracteres especiales
                if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                    $('#req-special').removeClass('invalid').addClass('valid');
                    $('#req-special i').removeClass('fa-times-circle').addClass('fa-check-circle');
                } else {
                    $('#req-special').removeClass('valid').addClass('invalid');
                    $('#req-special i').removeClass('fa-check-circle').addClass('fa-times-circle');
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
                $('#btnReset').prop('disabled', !valid);
            });
            
            // Envío del formulario
            $('#resetForm').on('submit', function(e) {
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
                const $btn = $('#btnReset');
                const $spinner = $btn.find('.spinner-border');
                const $btnText = $btn.find('.btn-text');
                
                $btn.prop('disabled', true);
                $spinner.removeClass('d-none');
                $btnText.text('Restableciendo...');
                
                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Contraseña restablecida!',
                                text: response.message || 'Tu contraseña ha sido actualizada correctamente',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(function() {
                                window.location.href = '/login';
                            });
                        } else {
                            showError(response.message || 'Error al restablecer la contraseña');
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
                const $btn = $('#btnReset');
                const $spinner = $btn.find('.spinner-border');
                const $btnText = $btn.find('.btn-text');
                
                $btn.prop('disabled', false);
                $spinner.addClass('d-none');
                $btnText.text('Restablecer Contraseña');
            }
        });
    </script>
</body>
</html>