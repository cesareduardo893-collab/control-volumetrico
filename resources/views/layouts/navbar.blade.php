<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid px-3">
        <!-- Logo y marca -->
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('dashboard') }}">
            <div class="brand-icon me-2">
                <i class="bi bi-fuel-pump-fill"></i>
            </div>
            <span class="d-none d-sm-inline">Control Volumétrico</span>
            <span class="d-sm-none">C.V.</span>
        </a>
        
        <!-- Botón toggle para móviles -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="bi bi-list fs-4"></i>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Menú izquierdo (vacío por ahora) -->
            <ul class="navbar-nav me-auto">
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
                            <span class="fw-semibold">Notificaciones</span>
                            <small class="text-muted" id="notifTime"></small>
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
                        <span class="d-none d-md-inline user-name">{{ session('user_name', 'Usuario') }}</span>
                        <i class="bi bi-chevron-down ms-1 dropdown-arrow"></i>
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
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1000;
}

.navbar:hover {
    box-shadow: 0 15px 35px rgba(31, 38, 135, 0.2);
}

.navbar-brand {
    font-weight: 700;
    font-size: 1.3rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
}

.navbar-brand:hover {
    transform: scale(1.05);
}

.brand-icon {
    width: 35px;
    height: 35px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.1rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.navbar-brand:hover .brand-icon {
    transform: rotate(10deg) scale(1.1);
}

.navbar-toggler {
    padding: 0.5rem;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.navbar-toggler:hover {
    background: rgba(102, 126, 234, 0.1);
}

.navbar-toggler:focus {
    box-shadow: none;
}

/* Connection Badge */
.connection-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: linear-gradient(135deg, rgba(79, 172, 254, 0.1) 0%, rgba(0, 242, 254, 0.1) 100%);
    border: 1px solid rgba(79, 172, 254, 0.2);
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    color: #0dcaf0;
    transition: all 0.3s ease;
}

.connection-badge:hover {
    background: linear-gradient(135deg, rgba(79, 172, 254, 0.2) 0%, rgba(0, 242, 254, 0.2) 100%);
    transform: translateY(-1px);
}

.connection-dot {
    width: 8px;
    height: 8px;
    background: #0dcaf0;
    border-radius: 50%;
    animation: pulse 2s infinite;
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
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    transition: all 0.3s ease;
    text-decoration: none;
}

.notification-trigger:hover {
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
    transform: translateY(-2px);
}

.notification-trigger i {
    font-size: 1.2rem;
}

.notification-badge {
    position: absolute;
    top: 2px;
    right: 2px;
    min-width: 18px;
    height: 18px;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    font-size: 0.65rem;
    font-weight: 600;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: bounceIn 0.5s ease-out;
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        opacity: 1;
        transform: scale(1.1);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

/* Notifications Dropdown */
.notifications-dropdown {
    width: 320px;
    max-height: 400px;
    overflow-y: auto;
    border: none;
    border-radius: 16px;
    box-shadow: 0 15px 35px rgba(31, 38, 135, 0.2);
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.notifications-dropdown::-webkit-scrollbar {
    width: 6px;
}

.notifications-dropdown::-webkit-scrollbar-track {
    background: transparent;
}

.notifications-dropdown::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.1);
    border-radius: 3px;
}

.dropdown-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    background: rgba(102, 126, 234, 0.05);
}

.view-all-link {
    color: #667eea;
    font-weight: 500;
    transition: all 0.3s ease;
}

.view-all-link:hover {
    background: rgba(102, 126, 234, 0.1);
    color: #764ba2;
}

/* User Dropdown */
.user-dropdown {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 12px;
    color: #495057;
    text-decoration: none;
    transition: all 0.3s ease;
}

.user-dropdown:hover {
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
}

.user-avatar {
    width: 35px;
    height: 35px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
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
    width: 280px;
    border: none;
    border-radius: 16px;
    box-shadow: 0 15px 35px rgba(31, 38, 135, 0.2);
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    animation: slideDown 0.3s ease-out;
}

.user-info {
    padding: 1.25rem;
    background: rgba(102, 126, 234, 0.05);
}

.user-avatar-large {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
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
    background: rgba(102, 126, 234, 0.1);
    transform: translateX(5px);
}

.menu-item.text-danger:hover {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545 !important;
}

/* Responsive */
@media (max-width: 768px) {
    .navbar-brand {
        font-size: 1.1rem;
    }
    
    .brand-icon {
        width: 30px;
        height: 30px;
        font-size: 0.9rem;
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
                                <small class="text-primary">${notif.tipo || 'Notificación'}</small>
                                <small class="text-muted">${notif.fecha || ''}</small>
                            </div>
                            <p class="mb-1 text-truncate" style="max-width: 280px;">${notif.mensaje || ''}</p>
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