<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Recuperar Contraseña - Sistema de Gestión</title>
    
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
        
        .forgot-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 400px;
            width: 90%;
        }
        
        .forgot-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .forgot-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .forgot-header i {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .forgot-body {
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
        
        .btn-forgot {
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
        
        .btn-forgot:hover {
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
        
        .info-text {
            text-align: center;
            color: #6c757d;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="forgot-card">
        <div class="forgot-header">
            <i class="fas fa-key"></i>
            <h2>Recuperar Contraseña</h2>
            <p class="mb-0">Enviaremos instrucciones a tu correo</p>
        </div>
        
        <div class="forgot-body">
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
            
            <div class="info-text">
                <i class="fas fa-info-circle me-2"></i>
                Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
            </div>
            
            <form id="forgotForm" method="POST" action="{{ route('password.email') }}">
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
                
                <button type="submit" class="btn-forgot" id="btnForgot">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="btn-text">Enviar Instrucciones</span>
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
            $('#forgotForm').on('submit', function(e) {
                e.preventDefault();
                
                const $form = $(this);
                const $btn = $('#btnForgot');
                const $spinner = $btn.find('.spinner-border');
                const $btnText = $btn.find('.btn-text');
                
                // Deshabilitar botón
                $btn.prop('disabled', true);
                $spinner.removeClass('d-none');
                $btnText.text('Enviando...');
                
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
                                title: '¡Correo enviado!',
                                text: response.message || 'Se han enviado las instrucciones a tu correo electrónico',
                                showConfirmButton: false,
                                timer: 3000
                            }).then(function() {
                                window.location.href = '/login';
                            });
                        } else {
                            showError(response.message || 'Error al enviar el correo');
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
                const $btn = $('#btnForgot');
                const $spinner = $btn.find('.spinner-border');
                const $btnText = $btn.find('.btn-text');
                
                $btn.prop('disabled', false);
                $spinner.addClass('d-none');
                $btnText.text('Enviar Instrucciones');
            }
        });
    </script>
</body>
</html>