<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión - Sistema de Gestión</title>
    
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
        
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 400px;
            width: 90%;
        }
        
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .login-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .login-header i {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .login-body {
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
        
        .btn-login {
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
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        .alert {
            border-radius: 25px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="fas fa-oil-can"></i>
            <h2>Sistema de Gestión</h2>
            <p class="mb-0">Control Volumétrico</p>
        </div>
        
        <div class="login-body">
            <div id="alertContainer"></div>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <form id="loginForm" method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               placeholder="Correo electrónico"
                               value="{{ old('email') }}"
                               required 
                               autofocus>
                    </div>
                    @error('email')
                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Contraseña"
                               required>
                    </div>
                    @error('password')
                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Recordarme</label>
                </div>
                
                <button type="submit" class="btn-login" id="btnLogin">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="btn-text">Iniciar Sesión</span>
                </button>
                
                <div class="register-link">
                    <a href="{{ route('register') }}">¿No tienes cuenta? Regístrate</a>
                </div>
                
                <div class="text-center mt-3">
                    <a href="{{ route('forgot-password') }}" class="text-muted">¿Olvidaste tu contraseña?</a>
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
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                
                const $form = $(this);
                const $btn = $('#btnLogin');
                const $spinner = $btn.find('.spinner-border');
                const $btnText = $btn.find('.btn-text');
                
                // Deshabilitar botón
                $btn.prop('disabled', true);
                $spinner.removeClass('d-none');
                $btnText.text('Iniciando sesión...');
                
                // Limpiar alertas previas
                $('#alertContainer').empty();
                
                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                window.location.href = response.redirect || '/dashboard';
                            });
                        } else {
                            showError(response.message || 'Error al iniciar sesión');
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
                const $btn = $('#btnLogin');
                const $spinner = $btn.find('.spinner-border');
                const $btnText = $btn.find('.btn-text');
                
                $btn.prop('disabled', false);
                $spinner.addClass('d-none');
                $btnText.text('Iniciar Sesión');
            }
        });
    </script>
</body>
</html>