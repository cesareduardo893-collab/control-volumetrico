<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow">
    <div class="container-fluid px-3">
        <!-- Logo y marca -->
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('dashboard') }}">
            <i class="bi bi-fuel-pump-fill me-2"></i>
            <span class="d-none d-sm-inline">Control Volumétrico</span>
            <span class="d-sm-none">C.V.</span>
        </a>
        
        <!-- Botón toggle para móviles -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Menú izquierdo (vacío por ahora) -->
            <ul class="navbar-nav me-auto">
            </ul>
            
            <!-- Menú derecho -->
            <ul class="navbar-nav align-items-center">
                <!-- Indicador de conexión -->
                <li class="nav-item me-3">
                    <span class="badge bg-success-subtle text-success" id="connectionStatus">
                        <i class="bi bi-wifi me-1"></i>Conectado
                    </span>
                </li>
                
                <!-- Notificaciones -->
                <li class="nav-item dropdown me-2">
                    <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge" id="notificationCount" style="display: none;">
                            0
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 notifications-dropdown" aria-labelledby="notificationsDropdown" style="width: 320px; max-height: 400px; overflow-y: auto;">
                        <div class="dropdown-header d-flex justify-content-between align-items-center bg-light">
                            <span class="fw-semibold">Notificaciones</span>
                            <small class="text-muted" id="notifTime"></small>
                        </div>
                        <div id="notificationsList">
                            <div class="dropdown-item text-center text-muted py-3">
                                <i class="bi bi-bell-slash fs-4 mb-2 d-block"></i>
                                <small>Sin notificaciones</small>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-center text-primary" href="{{ route('alarmas.index') }}">
                            <small>Ver todas las alarmas</small>
                        </a>
                    </div>
                </li>
                
                <!-- Usuario -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-circle me-2">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <span class="d-none d-md-inline">{{ session('user_name', 'Usuario') }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="userDropdown">
                        <li>
                            <div class="dropdown-item-text bg-light">
                                <small class="text-muted d-block">Correo:</small>
                                <span class="fw-semibold">{{ session('user_email', 'N/A') }}</span>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('auth.password.change.form') }}">
                                <i class="bi bi-key me-2"></i>Cambiar Contraseña
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}" 
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
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%) !important;
}

.navbar-brand {
    font-size: 1.1rem;
}

.avatar-circle {
    width: 32px;
    height: 32px;
    background-color: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-circle i {
    font-size: 1rem;
}

.notification-badge {
    font-size: 0.65rem;
    min-width: 18px;
    height: 18px;
    display: flex !important;
    align-items: center;
    justify-content: center;
}

.notifications-dropdown::-webkit-scrollbar {
    width: 6px;
}

.notifications-dropdown::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.notifications-dropdown::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

#notificationsDropdown:hover {
    transform: scale(1.1);
    transition: transform 0.2s;
}

#userDropdown:hover {
    background-color: rgba(255,255,255,0.1);
    border-radius: 4px;
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