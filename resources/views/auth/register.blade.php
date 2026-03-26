<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registro - GasControl</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --pemex-red: #CE1126;
            --pemex-red-dark: #a50d1f;
            --fuel-orange: #FF6B35;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .register-container {
            width: 100%;
            max-width: 700px;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.4);
        }
        .register-header {
            background: linear-gradient(135deg, #FF6B35 0%, #F7C331 100%);
            padding: 40px;
            text-align: center;
            color: white;
        }
        .brand-logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }
        .brand-logo::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 107, 53, 0.1), transparent);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }
        .brand-logo i { font-size: 2.5rem; color: #FF6B35; position: relative; z-index: 1; }
        .register-header h1 { font-size: 1.8rem; font-weight: 800; margin-bottom: 8px; text-shadow: 0 2px 4px rgba(0,0,0,0.2); }
        .register-header p { opacity: 0.95; font-weight: 500; font-size: 1.1rem; }
        .register-body { padding: 40px; }
        .form-label { font-weight: 600; color: #1a1a2e; font-size: 0.9rem; }
        .input-group-text {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.1) 0%, rgba(247, 195, 49, 0.1) 100%);
            border: 2px solid #e9ecef;
            color: #FF6B35;
        }
        .form-control { border: 2px solid #e9ecef; }
        .form-control:focus { border-color: #FF6B35; box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1); }
        .btn-register {
            width: 100%;
            padding: 16px;
            font-size: 1.1rem;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #FF6B35 0%, #F7C331 100%);
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-register:hover { transform: translateY(-3px); box-shadow: 0 12px 35px rgba(255, 107, 53, 0.5); background: linear-gradient(135deg, #F7C331 0%, #FF6B35 100%); }
        .btn-google {
            width: 100%;
            padding: 16px;
            font-size: 1.1rem;
            font-weight: 700;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .btn-google:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(255, 107, 53, 0.2); border-color: #FF6B35; background: rgba(255, 107, 53, 0.05); }
        .divider { display: flex; align-items: center; margin: 25px 0; color: #adb5bd; font-size: 0.85rem; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: linear-gradient(90deg, transparent, #dee2e6, transparent); }
        .divider span { padding: 0 15px; }
        .btn-login { color: #FF6B35; text-decoration: none; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 8px; padding: 14px; border-radius: 12px; transition: all 0.3s ease; background: rgba(255, 107, 53, 0.05); border: 2px solid rgba(255, 107, 53, 0.2); }
        .btn-login:hover { background: rgba(255, 107, 53, 0.1); transform: translateY(-2px); box-shadow: 0 4px 15px rgba(255, 107, 53, 0.2); }
        .alert { border: none; border-radius: 12px; }
        .alert-danger { background: rgba(220, 53, 69, 0.1); color: #dc3545; border-left: 4px solid #dc3545; }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <div class="brand-logo">
                <i class="bi bi-fuel-pump-fill"></i>
            </div>
            <h1>Crear Cuenta</h1>
            <p>Únete al sistema de Gestión de Gasolinera</p>
        </div>
        
        <div class="register-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
                        
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="identificacion" class="form-label">Identificación</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                        <input type="text" class="form-control" id="identificacion" 
                                               name="identificacion" value="{{ old('identificacion') }}" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombres" class="form-label">Nombres</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control" id="nombres" 
                                               name="nombres" value="{{ old('nombres') }}" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="apellidos" class="form-label">Apellidos</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control" id="apellidos" 
                                               name="apellidos" value="{{ old('apellidos') }}" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" 
                                           name="email" value="{{ old('email') }}" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                        <input type="text" class="form-control" id="telefono" 
                                               name="telefono" value="{{ old('telefono') }}">
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                        <input type="text" class="form-control" id="direccion" 
                                               name="direccion" value="{{ old('direccion') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control" id="password" 
                                               name="password" required>
                                    </div>
                                    <small class="text-muted">Mínimo 8 caracteres</small>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control" id="password_confirmation" 
                                               name="password_confirmation" required>
                                    </div>
                                </div>
                            </div>
                            
                        <hr class="my-4">
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn-register">
                                <i class="bi bi-person-plus me-2"></i> Crear Cuenta
                            </button>
                        </div>
                    </form>
                    
                    <div class="divider">
                        <span>O regístrate con</span>
                    </div>
                    
                    <div class="d-grid">
                        <button type="button" class="btn-google" onclick="handleGoogleSignIn()">
                            <svg viewBox="0 0 24 24" width="20" height="20">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Continuar con Google
                        </button>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('login') }}" class="btn-login">
                            <i class="bi bi-arrow-left"></i> Ya tengo cuenta - Iniciar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function handleGoogleSignIn() {
            window.location.href = 'http://localhost:8000/auth/google/redirect';
        }
    </script>
</body>
</html>