<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión - GasControl</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --pemex-red: #CE1126;
            --pemex-red-dark: #a50d1f;
            --pemex-green: #006847;
            --fuel-orange: #FF6B35;
            --fuel-yellow: #F7C331;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Background Pattern */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(206, 17, 38, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 107, 53, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(247, 195, 49, 0.08) 0%, transparent 30%);
            pointer-events: none;
        }

        /* Animated Fuel Drops */
        .fuel-drop {
            position: absolute;
            width: 10px;
            height: 10px;
            background: rgba(206, 17, 38, 0.3);
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            animation: fall linear infinite;
        }

        @keyframes fall {
            0% {
                transform: translateY(-100vh) rotate(-45deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(-45deg);
                opacity: 0;
            }
        }

        .login-container {
            width: 100%;
            max-width: 1000px;
            display: flex;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.4);
            position: relative;
            z-index: 1;
        }

        /* Left Panel - Branding */
        .login-branding {
            flex: 1;
            background: linear-gradient(135deg, #FF6B35 0%, #F7C331 100%);
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .login-branding::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse-bg 4s ease-in-out infinite;
        }

        @keyframes pulse-bg {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .brand-logo {
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
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

        .brand-logo i {
            font-size: 3.5rem;
            color: #FF6B35;
            position: relative;
            z-index: 1;
        }

        .brand-title {
            color: white;
            font-size: 2.2rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
            letter-spacing: 1px;
        }

        .brand-subtitle {
            color: rgba(255,255,255,0.95);
            font-size: 1.1rem;
            text-align: center;
            position: relative;
            z-index: 1;
            line-height: 1.6;
            font-weight: 500;
        }

        .brand-features {
            margin-top: 40px;
            position: relative;
            z-index: 1;
        }

        .brand-feature {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.9);
            margin-bottom: 15px;
        }

        .brand-feature i {
            width: 30px;
            height: 30px;
            background: rgba(255,255,255,0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }

        /* Right Panel - Form */
        .login-form-container {
            flex: 1;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 35px;
        }

        .form-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 8px;
        }

        .form-subtitle {
            color: #6c757d;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .input-group {
            position: relative;
        }

        .input-group-text {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.1) 0%, rgba(247, 195, 49, 0.1) 100%);
            border: 2px solid #e9ecef;
            border-right: none;
            color: #FF6B35;
            border-radius: 12px 0 0 12px;
            padding: 0 15px;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 0 12px 12px 0;
            padding: 14px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #FF6B35;
            box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
        }

        .form-control:focus + .input-group-text,
        .input-group:focus-within .input-group-text {
            border-color: #FF6B35;
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.15) 0%, rgba(247, 195, 49, 0.15) 100%);
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            z-index: 10;
            padding: 5px;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #FF6B35;
        }

        .form-check {
            margin: 20px 0;
        }

        .form-check-input:checked {
            background-color: #FF6B35;
            border-color: #FF6B35;
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.15);
        }

        .btn-login {
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(255, 107, 53, 0.5);
            background: linear-gradient(135deg, #F7C331 0%, #FF6B35 100%);
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-login.loading i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% { transform: rotate(360deg); }
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: #adb5bd;
            font-size: 0.85rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, #dee2e6, transparent);
        }

        .divider span {
            padding: 0 15px;
        }

        .btn-google {
            width: 100%;
            padding: 16px;
            font-size: 1.1rem;
            font-weight: 700;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            background: white;
            color: #1a1a2e;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .btn-google:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.2);
            border-color: #FF6B35;
            background: rgba(255, 107, 53, 0.05);
        }

        .google-icon {
            width: 24px;
            height: 24px;
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
        }

        .register-link a {
            color: #FF6B35;
            text-decoration: none;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: rgba(255, 107, 53, 0.05);
            border: 2px solid rgba(255, 107, 53, 0.2);
        }

        .register-link a:hover {
            background: rgba(255, 107, 53, 0.1);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.2);
        }

        /* Alert Styles */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border-left: 4px solid #dc3545;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border-left: 4px solid #28a745;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .login-container {
                flex-direction: column;
                max-width: 450px;
            }

            .login-branding {
                padding: 40px 30px;
            }

            .brand-logo {
                width: 100px;
                height: 100px;
            }

            .brand-logo i {
                font-size: 2.5rem;
            }

            .brand-title {
                font-size: 1.5rem;
            }

            .brand-features {
                display: none;
            }

            .login-form-container {
                padding: 40px 30px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .login-branding {
                padding: 30px 20px;
            }

            .login-form-container {
                padding: 30px 20px;
            }

            .form-title {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Fuel Drops -->
    <div class="fuel-drop" style="left: 10%; animation-duration: 8s; animation-delay: 0s;"></div>
    <div class="fuel-drop" style="left: 20%; animation-duration: 10s; animation-delay: 1s;"></div>
    <div class="fuel-drop" style="left: 30%; animation-duration: 7s; animation-delay: 2s;"></div>
    <div class="fuel-drop" style="left: 50%; animation-duration: 9s; animation-delay: 0.5s;"></div>
    <div class="fuel-drop" style="left: 70%; animation-duration: 11s; animation-delay: 1.5s;"></div>
    <div class="fuel-drop" style="left: 80%; animation-duration: 8s; animation-delay: 2.5s;"></div>
    <div class="fuel-drop" style="left: 90%; animation-duration: 10s; animation-delay: 0.8s;"></div>

    <div class="login-container">
        <!-- Left Panel - Branding -->
        <div class="login-branding">
            <div class="brand-logo">
                <i class="bi bi-fuel-pump-fill"></i>
            </div>
            <h1 class="brand-title">Control Volumétrico</h1>
            <p class="brand-subtitle">
                Sistema integral de gestión y control volumétrico para estaciones de servicio
            </p>
            
            <div class="brand-features">
                <div class="brand-feature">
                    <i class="bi bi-shield-check"></i>
                    <span>Cumplimiento SAT garantizado</span>
                </div>
                <div class="brand-feature">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span>Control en tiempo real</span>
                </div>
                <div class="brand-feature">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Reportes automáticos</span>
                </div>
                <div class="brand-feature">
                    <i class="bi bi-bell"></i>
                    <span>Alertas inteligentes</span>
                </div>
            </div>
        </div>

        <!-- Right Panel - Form -->
        <div class="login-form-container">
            <div class="form-header">
                <h2 class="form-title">Bienvenido</h2>
                <p class="form-subtitle">Ingresa tus credenciales para acceder al sistema</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf
                
                <div class="form-group">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               placeholder="correo@ejemplo.com"
                               required 
                               autofocus>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Tu contraseña"
                               required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        Recordar sesión
                    </label>
                </div>

                <button type="submit" class="btn-login" id="submitBtn">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Iniciar Sesión
                </button>
            </form>

            <div class="divider">
                <span>O continúa con</span>
            </div>

            <div class="google-login-container">
                <button type="button" class="btn-google" id="googleSignInBtn" onclick="handleGoogleSignIn()">
                    <svg class="google-icon" viewBox="0 0 24 24" width="24" height="24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continuar con Google
                </button>
            </div>

            <div class="register-link">
                <a href="{{ route('register.form') }}">
                    <i class="bi bi-person-plus"></i>
                    Crear una cuenta nueva
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }

        function handleGoogleSignIn() {
            const btn = document.getElementById('googleSignInBtn');
            btn.innerHTML = '<i class="bi bi-arrow-repeat spin me-2"></i> Conectando...';
            btn.disabled = true;
            
            window.location.href = 'http://localhost:8000/auth/google/redirect';
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            btn.classList.add('loading');
            btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Iniciando sesión...';
        });

        // Check for error parameter
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('error') === 'google_auth_failed') {
            alert('Error al autenticar con Google. Por favor, intenta de nuevo.');
        }
    </script>
</body>
</html>
