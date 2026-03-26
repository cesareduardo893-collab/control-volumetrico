<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid px-3">
        <!-- Logo y marca estilo Gasolinera -->
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <div class="brand-icon">
                <i class="bi bi-fuel-pump-fill"></i>
            </div>
            <div class="brand-text">
                <span class="brand-title">GasControl</span>
                <span class="brand-subtitle">Sistema de Gestión de Gasolinera</span>
            </div>
        </a>
        
        <!-- Botón toggle para móviles -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="bi bi-list fs-4 text-white"></i>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Menú izquierdo -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
            </ul>
            
            <!-- Menú derecho -->
            <ul class="navbar-nav align-items-center">
                <!-- Indicador de conexión -->
                <li class="nav-item me-3">
                    <span class="connection-badge" id="connectionStatus">
                        <span class="connection-dot"></span>
                        <span class="d-none d-sm-inline">Conectado</span>
                    </span>
                </li>
                
                <!-- Notificaciones -->
                <li class="nav-item dropdown me-2">
                    <a class="nav-link notification-trigger" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell"></i>
                        <span class="notification-badge" id="notificationCount" style="display: none;">
                            0
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end notifications-dropdown" aria-labelledby="notificationsDropdown">
                        <div class="dropdown-header">
                            <span class="fw-semibold text-white">Notificaciones</span>
                            <small class="text-white-50" id="notifTime"></small>
                        </div>
                        <div id="notificationsList">
                            <div class="dropdown-item text-center text-muted py-4">
                                <i class="bi bi-bell-slash fs-3 mb-2 d-block opacity-50"></i>
                                <small>Sin notificaciones</small>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-center view-all-link" href="{{ route('alarmas.index') }}">
                            <small>Ver todas las alarmas</small>
                        </a>
                    </div>
                </li>
                
                <!-- Usuario -->
                <li class="nav-item dropdown">
                    <a class="nav-link user-dropdown" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <span class="d-none d-md-inline user-name text-white">{{ session('user_name', 'Usuario') }}</span>
                        <i class="bi bi-chevron-down ms-1 dropdown-arrow text-white"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end user-menu" aria-labelledby="userDropdown">
                        <li>
                            <div class="dropdown-item-text user-info">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-large me-3">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ session('user_name', 'Usuario') }}</div>
                                        <small class="text-muted">{{ session('user_email', 'N/A') }}</small>
                                        <div class="mt-1">
                                            @foreach(session('user_roles', []) as $role)
                                                <span class="badge bg-danger me-1" style="background: linear-gradient(135deg, #FF6B35 0%, #F7C331 100%) !important;">{{ $role }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item menu-item" href="{{ route('auth.password.change.form') }}">
                                <i class="bi bi-key me-2"></i>Cambiar Contraseña
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item menu-item text-danger" href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
.navbar {
    background: linear-gradient(90deg, #FF6B35 0%, #F7C331 50%, #FF6B35 100%);
    box-shadow: 0 4px 20px rgba(255, 107, 53, 0.4);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1000;
    padding: 0.5rem 0;
}

.navbar:hover {
    box-shadow: 0 6px 25px rgba(255, 107, 53, 0.5);
}

.navbar-brand {
    font-weight: 700;
    color: white !important;
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
}

.navbar-brand:hover {
    transform: scale(1.02);
}

.brand-icon {
    width: 50px;
    height: 50px;
    background: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.brand-icon::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
    transform: rotate(45deg);
    animation: shine 3s infinite;
}

@keyframes shine {
    0% { transform: translateX(-100%) rotate(45deg); }
    100% { transform: translateX(100%) rotate(45deg); }
}

.navbar-brand:hover .brand-icon {
    transform: rotate(-5deg) scale(1.05);
}

.brand-icon i {
    font-size: 1.6rem;
    color: #FF6B35;
}

.brand-text {
    display: flex;
    flex-direction: column;
}

.brand-title {
    font-size: 1.4rem;
    font-weight: 800;
    line-height: 1.1;
    color: white;
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.brand-subtitle {
    font-size: 0.75rem;
    font-weight: 500;
    opacity: 0.95;
    color: rgba(255,255,255,0.95);
}

.navbar .nav-link {
    color: rgba(255,255,255,0.9) !important;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.navbar .nav-link:hover {
    background: rgba(255,255,255,0.15);
    color: white !important;
}

.navbar-toggler {
    padding: 0.5rem;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.navbar-toggler:hover {
    background: rgba(255,255,255,0.1);
}

.navbar-toggler:focus {
    box-shadow: none;
}

/* Connection Badge */
.connection-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    color: white;
    transition: all 0.3s ease;
}

.connection-badge:hover {
    background: rgba(255,255,255,0.25);
    transform: translateY(-1px);
}

.connection-dot {
    width: 8px;
    height: 8px;
    background: #28a745;
    border-radius: 50%;
    animation: pulse 2s infinite;
    box-shadow: 0 0 8px #28a745;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.7;
        transform: scale(1.1);
    }
}

/* Notification Trigger */
.notification-trigger {
    position: relative;
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white !important;
    transition: all 0.3s ease;
    text-decoration: none;
    background: rgba(255,255,255,0.1);
}

.notification-trigger:hover {
    background: rgba(255,255,255,0.2);
    transform: translateY(-2px);
}

.notification-trigger i {
    font-size: 1.2rem;
}

.notification-badge {
    position: absolute;
    top: 2px;
    right: 2px;
    min-width: 20px;
    height: 20px;
    background: #F7C331;
    color: #1a1a2e;
    font-size: 0.7rem;
    font-weight: 700;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: bounceIn 0.5s ease-out;
    box-shadow: 0 2px 8px rgba(247, 195, 49, 0.4);
}

@keyframes bounceIn {
    0% { opacity: 0; transform: scale(0.3); }
    50% { opacity: 1; transform: scale(1.1); }
    100% { opacity: 1; transform: scale(1); }
}

/* Notifications Dropdown */
.notifications-dropdown {
    width: 350px;
    max-height: 400px;
    overflow-y: auto;
    border: none;
    border-radius: 16px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    background: rgba(255, 255, 255, 0.98);
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.dropdown-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    background: linear-gradient(135deg, #FF6B35 0%, #F7C331 100%);
    border-radius: 16px 16px 0 0;
    box-shadow: 0 2px 10px rgba(255, 107, 53, 0.2);
}

.view-all-link {
    color: #CE1126;
    font-weight: 500;
    transition: all 0.3s ease;
}

.view-all-link:hover {
    background: rgba(206, 17, 38, 0.1);
    color: #a50d1f;
}

/* User Dropdown */
.user-dropdown {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 12px;
    color: white !important;
    text-decoration: none;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.1);
}

.user-dropdown:hover {
    background: rgba(255,255,255,0.2);
}

.user-avatar {
    width: 38px;
    height: 38px;
    background: white;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #CE1126;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.user-dropdown:hover .user-avatar {
    transform: scale(1.1) rotate(5deg);
}

.user-name {
    font-weight: 500;
    font-size: 0.9rem;
}

.dropdown-arrow {
    font-size: 0.7rem;
    transition: transform 0.3s ease;
}

.user-dropdown[aria-expanded="true"] .dropdown-arrow {
    transform: rotate(180deg);
}

/* User Menu */
.user-menu {
    width: 300px;
    border: none;
    border-radius: 16px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    background: rgba(255, 255, 255, 0.98);
    animation: slideDown 0.3s ease-out;
}

.user-info {
    padding: 1.25rem;
    background: linear-gradient(135deg, rgba(206, 17, 38, 0.05) 0%, rgba(255, 107, 53, 0.05) 100%);
}

.user-avatar-large {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #FF6B35 0%, #F7C331 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.4rem;
    box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
}

.menu-item {
    padding: 0.75rem 1.25rem;
    border-radius: 8px;
    margin: 0.25rem 0.75rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.menu-item:hover {
    background: rgba(206, 17, 38, 0.1);
    transform: translateX(5px);
}

.menu-item.text-danger:hover {
    background: rgba(206, 17, 38, 0.1);
    color: #CE1126 !important;
}

/* Responsive */
@media (max-width: 768px) {
    .navbar-brand {
        font-size: 1.1rem;
    }
    
    .brand-icon {
        width: 40px;
        height: 40px;
    }
    
    .brand-icon i {
        font-size: 1.2rem;
    }
    
    .brand-subtitle {
        display: none;
    }
    
    .connection-badge {
        padding: 4px 8px;
        font-size: 0.75rem;
    }
    
    .notification-trigger {
        width: 36px;
        height: 36px;
    }
    
    .user-avatar {
        width: 32px;
        height: 32px;
    }
}
</style>

<script>
function loadNotifications() {
    $.get('{{ route("api.notificaciones") }}', function(response) {
        if (response.success && response.data) {
            let count = response.data.length;
            const badge = $('#notificationCount');
            const list = $('#notificationsList');
            
            if (count > 0) {
                badge.text(count).show();
                let html = '';
                response.data.slice(0, 5).forEach(function(notif) {
                    html += `
                        <a class="dropdown-item border-bottom" href="${notif.url || '#'}">
                            <div class="d-flex w-100 justify-content-between">
                                <small class="text-danger fw-bold">${notif.tipo || 'Notificación'}</small>
                                <small class="text-muted">${notif.fecha || ''}</small>
                            </div>
                            <p class="mb-1 text-truncate" style="max-width: 300px;">${notif.mensaje || ''}</p>
                        </a>`;
                });
                list.html(html);
            } else {
                badge.hide();
                list.html(`
                    <div class="dropdown-item text-center text-muted py-3">
                        <i class="bi bi-bell-slash fs-4 mb-2 d-block"></i>
                        <small>Sin notificaciones</small>
                    </div>`);
            }
        }
    }).fail(function() {
        $('#notificationCount').hide();
    });
}

$(document).ready(function() {
    loadNotifications();
    setInterval(loadNotifications, 60000);
});
</script>
